<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\YelpTrait;
use App\Models\YelpFusion;
use App\Models\SocialMediaAccounts;
use App\Models\Items;
use \DB;

class Yelp  extends Feed
{

    protected $table = 'yelp';
    protected $fillable = ['id', 'biz_id', 'items_id', 'rating', 'text', 'review_url', 'created_at'];

    public function __construct()
    {
        $this->yelpFusion = new YelpFusion();
    }

    public function updateContactInfo()
    {
        $savedArr = [];
        $contactArr = [];
        $r = SocialMediaAccounts::where('site', '=', 'yelp.com')->where('is_active', '=', 1)->get()->toArray();
        foreach($r as $arr) {
            $yelpObj = $this->yelpFusion->bizlookup($arr['username'], $this->yelpFusion->getOauthToken(), 0);
            $contactArr[$arr['items_id']] = $yelpObj;
            $r = $this->saveContactInfo($contactArr);
            if (!empty($r)) {
                $savedArr[] = $r;
            }
        }
        return $savedArr;
    }

    public function saveContactInfo(array $contactArr)
    {

        $finalArr = [];
        foreach($contactArr as $itemsId => $yelpObj) {

            $r = DB::table('contact_info')
                ->where(function($q) use ($yelpObj, $itemsId) {
                    $q->where('biz_id', $yelpObj->id)
                        ->where('items_id', $itemsId);
                })
                ->get();
            if ($r->count()) {
                continue;
            }

            $hoursJson = $this->formatHours($yelpObj);
            $arr = [
                'biz_id' => $yelpObj->id,
                'items_id' => $itemsId,
                'business' => $yelpObj->name,
                'address' => $yelpObj->location->display_address[0],
                'address2' => $yelpObj->location->display_address[1],
                'city' => $yelpObj->location->city,
                'state' => $yelpObj->location->state,
                'postal_code' => $yelpObj->location->zip_code,
                'phone_number' => $yelpObj->phone,
                'lat' => $yelpObj->coordinates->latitude,
                'lon' => $yelpObj->coordinates->longitude,
                'hours' => $hoursJson
            ];

            DB::table('contact_info')->insert($arr);

            $finalArr[] = $arr;

        }

        return $finalArr;

    }

    private function formatHours($yelpObj)
    {

        $hoursJson = '';
        if (isset($yelpObj->hours[0]->open)) {
            $hoursArr = $yelpObj->hours[0]->open;
            $formattedHours = [];
            foreach($hoursArr as $i => $obj) {
                $day = $this->formatDay($obj->day);
                $hours = $this->formatTime($obj->start);
                $end = $this->formatTime($obj->end);
                if ($end) {
                    $hours.= " - " . $end;
                }
                $formattedHours[$i] = $day . " " . $hours;
            }

            $hoursJson = json_encode($formattedHours);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $hoursJson = '';
            }
        }

        return $hoursJson;

    }

    // 0 to 6, representing day of the week from Monday to Sunday
    private function formatDay($num)
    {
        if ($num == 0) {
            return "Mon";
        } else if ($num == 1) {
            return "Tue";
        } else if ($num == 2) {
            return "Wed";
        } else if ($num == 3) {
            return "Thu";
        } else if ($num == 4) {
            return "Fri";
        } else if ($num == 5) {
            return "Sat";
        } else if ($num == 6) {
            return "Sun";
        }
        return $num;
    }

    private function formatTime($str)
    {
        if ($str == "0000" || empty($str)) {
            return "";
        }
        $hour = substr($str, 0, 2);
        $min = substr($str, 2, 4);
        $ut = mktime($hour, $min, 0, date("m"), date("d"), date("Y"));
        $hour = date("h:i a", $ut);
        return $hour;

    }

    public function getFeed() {
        $this->yelpFusion = new YelpFusion();
        $r = SocialMediaAccounts::where('site', '=', 'yelp.com')
            ->where('is_active', '=', 1)
            ->get()->toArray();
        foreach($r as $arr) {
            $yelpFeedObj = $this->yelpFusion->bizlookup($arr['username'], $this->yelpFusion->getOauthToken(), 1);
            $yelpFeedArr[$arr['items_id']] = $yelpFeedObj;
            $yelpFeedArr[$arr['items_id']]->biz_id = $arr['username'];
            $this->saveFeed($yelpFeedArr);
        }
    }

    public function saveFeed(array $feedArr) {

        foreach($feedArr as $itemsId => $feedObj) {
            $bizId = $feedObj->biz_id;
            foreach($feedObj->reviews as $obj) {
                $r = DB::table('yelp')->where('id', '=', $obj->id)->get();
                if ($r->count()) {
                    continue;
                }
                $arr = [
                    'id' => $obj->id,
                    'biz_id' => $bizId,
                    'rating' => $obj->rating,
                    'items_id' => $itemsId,
                    'text' => $obj->text,
                    'review_url' => $obj->url,
                    'created_at' => $obj->time_created,
                    'updated_at' => $obj->time_created
                ];
                // error reference YelpFusion when trying to do $this->create($arr);
                DB::table('yelp')->insert($arr);

            }
        }
    }

    public function getUnconvertedFeed()
    {
        $q = "SELECT yelp.* FROM yelp
              LEFT JOIN
              social_media ON social_media.source_id = yelp.id
              AND social_media.site = 'yelp.com' 
              WHERE social_media.id is null";
        $r = DB::select($q);
        //echo "unconverted rows:".count($r)."|";
        return $r;
    }

    /*
     * Convert short urls to full, add hyperlinks to @ and #, convert smart quotes, etc
     */
    public function convertFeedToSocialMedia()
    {

        $objArr = [];
        $r = $this->getUnconvertedFeed();
        foreach($r as $dbObj) {
            $dbObj->text = Utility::cleanText($dbObj->text);
            $dbObj->text = Utility::tighten($dbObj->text);
            $dbObj->text = "Yelp review: " . $dbObj->text;
            $rating = "Rating: " . $dbObj->rating . " stars";
            $dbObj->text.= " <a href='" . $dbObj->review_url . "' target='_blank'>$rating</a>";
            $objArr[] = $dbObj;
        }
        $this->saveConvertedFeedToSocialMedia($objArr);
        return $objArr;

    }

    private function saveConvertedFeedToSocialMedia($objArr)
    {
        if (!count($objArr)) {
            return;
        }
        foreach($objArr as $obj) {
            $arr = [
                'source_user_id' => $obj->biz_id,
                'source_id' => $obj->id,
                'username' => $obj->biz_id,
                'site' => 'yelp.com',
                'link' => $obj->review_url,
                'text' => iconv("UTF-8", "UTF-8//IGNORE", $obj->text),
                'created_at' => $obj->created_at
            ];
            $objArr[] = SocialMedia::updateOrCreate($arr);
        }

        return $objArr;

    }


}