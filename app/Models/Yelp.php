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

    }

    public function getFeed() {
        $this->yelpFusion = new YelpFusion();
        $r = SocialMediaAccounts::where('site', '=', 'yelp.com')->get()->toArray();
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

/*
Array
(
    [68] => stdClass Object
        (
            [reviews] => Array
                (
                    [0] => stdClass Object
                        (
                            [id] => cqoDrGf39AwHr72nRZtQKw
                            [url] => https://www.yelp.com/biz/gjelina-venice-2?hrid=cqoDrGf39AwHr72nRZtQKw&adjust_creative=LCdZAbtdm-RwIyToOI_ZaQ&utm_campaign=yelp_api_v3&utm_medium=api_v3_business_reviews&utm_source=LCdZAbtdm-RwIyToOI_ZaQ
                            [text] => General:
I hesitated to come because of the 3.5 stars, but a friend insisted that I try it out, so I came & am so glad that I did! About an hour long wait...
                            [rating] => 4
                            [user] => stdClass Object
                                (
                                    [image_url] => https://s3-media2.fl.yelpcdn.com/photo/aQA2opBwV7SDVNJHf2sM5Q/o.jpg
                                    [name] => Ang L.
                                )

                            [time_created] => 2018-02-10 17:13:57
                        )

                    [1] => stdClass Object
                        (
                            [id] => xgRYWnWOHvsEj5TkgqjQ5Q
                            [url] => https://www.yelp.com/biz/gjelina-venice-2?hrid=xgRYWnWOHvsEj5TkgqjQ5Q&adjust_creative=LCdZAbtdm-RwIyToOI_ZaQ&utm_campaign=yelp_api_v3&utm_medium=api_v3_business_reviews&utm_source=LCdZAbtdm-RwIyToOI_ZaQ
                            [text] => I've never had a worse experience. My friend has a life threatening allergy and they won't accommodate. Why, oh why, will they not drop the last step of...
                            [rating] => 1
                            [user] => stdClass Object
                                (
                                    [image_url] => https://s3-media1.fl.yelpcdn.com/photo/YUNqFny8iCFQjC00nx-zSg/o.jpg
                                    [name] => Matt S.
                                )

                            [time_created] => 2018-02-20 21:25:16
                        )

                    [2] => stdClass Object
                        (
                            [id] => kZepK8omUBwl4JR7aqGT8g
                            [url] => https://www.yelp.com/biz/gjelina-venice-2?hrid=kZepK8omUBwl4JR7aqGT8g&adjust_creative=LCdZAbtdm-RwIyToOI_ZaQ&utm_campaign=yelp_api_v3&utm_medium=api_v3_business_reviews&utm_source=LCdZAbtdm-RwIyToOI_ZaQ
                            [text] => Had the Moroccan Baked Eggs on Saturday and got food poisoning.  Violently ill and still not well as of this writing.  I did call the restaurant to give a...
                            [rating] => 1
                            [user] => stdClass Object
                                (
                                    [image_url] => https://s3-media3.fl.yelpcdn.com/photo/l2J5G5Q41hm2aUcCBXd-OA/o.jpg
                                    [name] => Joan B.
                                )

                            [time_created] => 2018-02-20 10:45:56
                        )

                )

            [total] => 4079
            [possible_languages] => Array
                (
                    [0] => fr
                    [1] => en
                    [2] => it
                    [3] => sv
                    [4] => cs
                    [5] => ja
                    [6] => es
                )

            [biz_id] => gjelina-venice-2
        )

)
 */