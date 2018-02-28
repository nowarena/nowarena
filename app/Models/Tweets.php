<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use \DB;


class Tweets extends Feed
{
    protected $fillable = ['id', 'user_id', 'site', 'screen_name', 'text', 'urls', 'media', 'in_reply_to_status_id', 'in_reply_to_user_id', 'created_at'];

    protected $friendsArr = [];

    public function saveFriends()
    {
        $objArr = [];
        $cursor = -1;
        $paramArr = [
            'screen_name' => $this->screenName,
            'skip_status' => true,
            'include_user_entities' => false,
            'cursor' => $cursor,
            'count' => 200
        ];
        \DB::enableQueryLog();
        do {

            $r = \Twitter::getFriends($paramArr);
            echo printR($r);
            if (isset($r->users)) {
                foreach($r->users as $obj) {
                    $q = "INSERT INTO social_media_accounts (source_user_id, username, site, avatar, created_at, updated_at) 
                          VALUES (?, ?, ?, ?, NOW(), NOW())
                          ON DUPLICATE KEY UPDATE avatar = ?, username = ?";
                    Db::insert($q, [$obj->id, $obj->screen_name, 'twitter.com', $obj->profile_image_url, $obj->profile_image_url, $obj->screen_name]);

                    //$arr = [
                    //    "source_id" => $obj->id,
                    //    "username" => $obj->screen_name,
                    //    "site" => "twitter.com",
                    //    "avatar" => $obj->profile_image_url
                    //];
                    //SocialMediaAccounts::updateOrCreate($arr);
                    //$matchArr = array('source_id' => $obj->id, 'site' => 'twitter.com');
                    //$objArr[] = SocialMediaAccounts::updateOrCreate($matchArr, ['updated_at' => 'NOW()']);
                }
            }
        } while($r->next_cursor_str > 0);

        $r = dd( \DB::getQueryLog() );
    }

    public function getFeed()
    {

        // get max id from 'tweets' table (not 'social_media' table)
        $since_id = $this->max('id');
        $paramArr = [
            'count' => 200,
            'include_entities' => 1
        ];
        if ($since_id) {
            $paramArr['since_id'] = $since_id;
        }
        echo "Fetching tweets since " . $since_id . "<br>";
        $r = \Twitter::getHomeTimeline($paramArr);
        echo "Tweets found: " . count($r) . "<br>";
        return $r;

    }

    public function saveFeed(array $tweetsArr)
    {

        $objArr = [];
        foreach($tweetsArr as $tweetObj) {
            $date = date("Y-m-d H:i:s", strtotime($tweetObj->created_at));
            $arr = [
                'id' => $tweetObj->id,
                'user_id' => $tweetObj->user->id,
                'screen_name' => $tweetObj->user->screen_name,
                'text' => $tweetObj->text,
                'urls' => $this->getUrlsJson($tweetObj),
                'media' => $this->getMediaJson($tweetObj),
                'in_reply_to_status_id' => $tweetObj->in_reply_to_status_id,
                'in_reply_to_user_id' => $tweetObj->in_reply_to_user_id,
                'created_at' => $date
            ];
            $objArr[] = Tweets::updateOrCreate($arr);
        }
        echo "Tweets saved: " . count($objArr) . "<br>";

    }

    /*
     * Convert short urls to full, add hyperlinks to @ and #, convert smart quotes, etc
     */
    public function convertFeedToSocialMedia()
    {

        $objArr = [];
        $r = $this->getUnconvertedFeed();
        foreach($r as $tweetDBObj) {
            //echo "\nBEFORE:".printR($tweetDBObj);
            $tweetDBObj->text = Utility::cleanText($tweetDBObj->text);
            $tweetDBObj->text = Utility::tighten($tweetDBObj->text);
            $tweetDBObj = $this->parseUrls($tweetDBObj);
            $tweetDBObj = $this->parseHashtags($tweetDBObj);
            $tweetDBObj = $this->parseAt($tweetDBObj);
            $tweetDBObj = $this->parseMedia($tweetDBObj);
            //echo "\nAFTER: " . printR($tweetDBObj);            echo "<hr>\n<br>";
            $objArr[] = $tweetDBObj;
        }
        $this->saveConvertedFeedToSocialMedia($objArr);

    }

    private function saveConvertedFeedToSocialMedia($objArr)
    {
        if (!count($objArr)) {
            return;
        }
        foreach($objArr as $obj) {
            $arr = [
                'source_user_id' => $obj->user_id,
                'source_id' => $obj->id,
                'username' => $obj->screen_name,
                'site' => 'twitter.com',
                'link' => 'https://twitter.com/' . $obj->screen_name . '/status/' . $obj->id,
                'text' => iconv("UTF-8", "UTF-8//IGNORE", $obj->text),
                'created_at' => $obj->created_at
            ];
            $objArr[] = SocialMedia::updateOrCreate($arr);
        }

    }

    private function parseMedia($tweetDBObj)
    {
        $mediaArr = json_decode($tweetDBObj->media);
        if (json_last_error() !== JSON_ERROR_NONE || count($mediaArr) == 0) {
            return $tweetDBObj;
        }
        //Array (
        //    [https://t.co/QrQrmWEpjR] => Array     (
        //        [expanded_url] => https://twitter.com/SIPerfumes/status/954836363114774528/photo/1
        //        [media_url] => http://pbs.twimg.com/media/DUBCm74VAAAQ6NL.jpg
        //    )
        //)
        $count = 0;
        foreach($mediaArr as $shortUrl => $obj) {
            $expandedUrl = $obj->expanded_url;
            $mediaUrl = $obj->media_url;
            $thumb = "<img src='" . $mediaUrl . ":thumb' class='socialMediaThumb'>";
            $replace = "<a class='imageThumbLink' target='_blank' href='$expandedUrl'>$thumb</a>";
            if ($count == 0) {
                $replace = "<a class='imageThumbLink firstImage' target='_blank' href='$expandedUrl'>$thumb</a>";
            }
            //$text = str_replace($shortUrl, $replace, $tweetDBObj->text);
            $text = str_replace($shortUrl, '', $tweetDBObj->text);
            $text= $replace . $text;
            $count++;
        }
        $tweetDBObj->text = $text;
        return $tweetDBObj;

    }

    private function parseUrls($obj)
    {
        $urlsArr = json_decode($obj->urls);
        if (json_last_error() !== JSON_ERROR_NONE || count($urlsArr) == 0) {
            return $obj;
        }
        foreach($urlsArr as $short => $full) {
            $domain = parse_url($full, PHP_URL_HOST);
            if ($domain == 'twitter.com') {
                continue;
            }
            $domain = str_replace("www.", "", $domain);
            $obj->text = str_replace($short, "<a class='siteLink' target='_blank' href='$full'>$domain</a>", $obj->text);
        }
        return $obj;

    }

    private function parseHashtags($obj)
    {
        if (!strstr($obj->text, "#")) {
            return $obj;
        }
        preg_match_all("~#([a-zA-Z0-9_])+~", $obj->text, $arr);
        if (!count($arr[0])) {
            return $obj;
        }
        foreach($arr[0] as $match) {
            $replaceWith = "<a class='hashtagLink' target='_blank' href='https://twitter.com/hashtag/" . str_replace("#", "", $match) . "'>";
            $replaceWith.= $match . "</a>";
            $obj->text = preg_replace("~" . $match . "~is", $replaceWith, $obj->text);
        }
        return $obj;
        
    }

    private function parseAt($obj)
    {

        if (!strstr($obj->text, "@")) {
            return $obj;
        }
        preg_match_all("~@([a-zA-Z0-9_])+~", $obj->text, $arr);
        if (!count($arr[0])) {
            return $obj;
        }
        foreach($arr[0] as $match) {
            $replaceWith = "<a class='atLink' target='_blank' href='https://twitter.com/" . str_replace("@", "", $match) . "'>$match</a>";
            $obj->text = preg_replace("~" . $match . "~is", $replaceWith, $obj->text);
        }
        return $obj;
    }

    public function getUnconvertedFeed()
    {
        $q = "SELECT tweets.* FROM tweets
              LEFT JOIN
              social_media ON social_media.source_id = tweets.id
              AND social_media.site = 'twitter.com' 
              WHERE social_media.id is null";
        $r = DB::select($q);
        //echo "unconverted tweets:".count($r)."|";
        return $r;
    }

    private function getMediaJson($tweetObj)
    {

        if (empty($tweetObj->entities->media)) {
            return '';
        }
        $mediaArr = [];
        foreach($tweetObj->entities->media as $mediaObj) {
            if (property_exists($mediaObj, 'expanded_url')) {
                $mediaArr[$mediaObj->url] = array(
                    'expanded_url' => $mediaObj->expanded_url,
                    'media_url' => $mediaObj->media_url
                );
            }
        }
        //echo printR($mediaArr);
        $mediaJson = json_encode($mediaArr);

        return $mediaJson;

    }

    private function getUrlsJson($tweetObj)
    {

        if (empty($tweetObj->entities->urls)) {
            return '';
        }
        $urlsArr = [];
        foreach($tweetObj->entities->urls as $urlObj) {
            if (property_exists($urlObj, 'expanded_url')) {
                $urlsArr[$urlObj->url] = $urlObj->expanded_url;
            }
        }
        $urlsJson = json_encode($urlsArr);

        return $urlsJson;
    }

};
