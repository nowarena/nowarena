<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Read extends Model
{

    /*
     * Array(
        [1] => Array        (
            [title] => Bar
            [cats_id] => 1
            [items] => Array (
                [0] => stdClass Object (
                        [items_id] => 5
                        [items_title] => TownhouseTweets
                        [items_description] =>
     */
    public static function getTopLevel()
    {
        $dataArr = [];
        $r = self::getParentOnlyCats();
        foreach($r as $obj) {
            $dataArr[$obj->id]['title'] = $obj->title;
            $dataArr[$obj->id]['cats_id'] = $obj->id;
            $dataArr[$obj->id]['items'] = self::getItemsWithCatsId($obj->id);
            foreach($dataArr[$obj->id]['items'] as $i => $itemsObj) {
                $dataArr[$obj->id]['items'][$i]->social_media = self::getSocialMediaWithItemsId($itemsObj->items_id);
            }

        }
        return $dataArr;
    }

    public static function getParentOnlyCats() {
        $q = "SELECT cats.id, cats.title FROM cats
              INNER JOIN cats_p_and_c ON cats.id = cats_p_and_c.child_id
              AND cats_p_and_c.parent_id = 0";
        $r = \DB::select($q);
        return $r;
    }

    public static function getItemsWithCatsId($id)
    {

        $q = "SELECT i.id as items_id, i.title as items_title, i.description as items_description FROM items i
	          INNER JOIN items_cats ic ON i.id = ic.items_id 
	          WHERE cats_id = ?";
        $r = \DB::select($q, [$id]);
        return $r;
    }

    public static function getSocialMediaWithItemsId($itemsId)
    {

        $q = "SELECT sma.username, sma.avatar, sm.site, sm.link, sm.text, sm.created_at FROM social_media_accounts sma 
              INNER JOIN social_media sm ON sma.source_user_id = sm.source_user_id  
              WHERE 1 = 1 
              AND sma.items_id = ? 
              AND sma.is_active = 1  
              ORDER BY sm.created_at DESC 
              LIMIT 10";
        $r = \DB::select($q, [$itemsId]);
        return $r;

    }

}
