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
        $parentCatsArr = self::getParentOnlyCats();
        echo __METHOD__ . printR($parentCatsArr);
        foreach($parentCatsArr as $obj) {
            $dataArr[$obj->id]['title'] = $obj->title;
            $dataArr[$obj->id]['cats_id'] = $obj->id;
            $dataArr = self::getItems($obj, $dataArr);
            $childrenArr = self::getChildren($obj, $dataArr[$obj->id]);
            if (count($childrenArr)) {
                $dataArr[$obj->id]['children'] = $childrenArr;
            }
        }
        return $dataArr;
    }

    public static function getCatsWithChildId($id)
    {
        $q = "SELECT cats.id, cats.title FROM cats
              INNER JOIN cats_p_and_c ON cats.id = cats_p_and_c.child_id
              AND cats_p_and_c.parent_id = ? 
              ";
        $r = \DB::select($q, [$id]);
        echo preg_replace("~ = \?~", " = $id", $q) . ";<br>";
        //echo $q."|$id|<br>";
        return $r;
    }

    public static function getChildren($obj, $childDataArr, $parentId = 0)
    {
        $catsArr = self::getCatsWithChildId($obj->id);
        //echo __METHOD__ . printR($catsArr) . " num:" . count($catsArr)."|x";
        if (count($catsArr)) {
            foreach($catsArr as $catsObj) {
                $childrenArr = self::getChildren($catsObj, $childDataArr);
                if (count($childDataArr)) {
                    $childDataArr[$catsObj->id]['children'] = $childrenArr;
                } else {
                    $childDataArr[$catsObj->id]['children'] = [];
                }
            }
        } else {

        }

        return $childDataArr;
    }

    public static function getItems($obj, $dataArr)
    {
        $dataArr[$obj->id]['items'] = self::getItemsWithCatsId($obj->id);
        if (count($dataArr[$obj->id]['items'])) {
            foreach($dataArr[$obj->id]['items'] as $i => $itemsObj) {
                $dataArr[$obj->id]['items'][$i]->social_media = self::getSocialMediaWithItemsId($itemsObj->items_id);
            }
        }
        return $dataArr;
    }

    public static function getParentOnlyCats()
    {
        return self::getCatsWithParentId(0);
    }

    public static function getCatsWithParentId($id)
    {
        $q = "SELECT cats.id, cats.title FROM cats
              INNER JOIN cats_p_and_c ON cats.id = cats_p_and_c.child_id
              AND cats_p_and_c.parent_id = ?";
        $r = \DB::select($q, [$id]);
        echo preg_replace("~ = \?~", " = $id", $q) . ";<br>";
        //echo $q."|$id|<br>";
        return $r;
    }

    public static function getItemsWithCatsId($id)
    {
        $q = "SELECT i.id as items_id, i.title as items_title, i.description as items_description 
              FROM items i
	          INNER JOIN items_cats ic ON i.id = ic.items_id 
	          WHERE cats_id = ?";
        //echo $q."|$id|<br>";
        $r = \DB::select($q, [$id]);

        return $r;
    }

    public static function getSocialMediaWithItemsId($itemsId, $offset = 0, $limit = 2)
    {
        $q = "SELECT sma.username, sma.avatar, sm.site, sm.link, sm.text, sm.created_at 
              FROM social_media_accounts sma 
              INNER JOIN social_media sm ON sma.source_user_id = sm.source_user_id  
              WHERE 1 = 1 
              AND sma.items_id = ? 
              AND sma.is_active = 1  
              ORDER BY sm.created_at DESC 
              LIMIT $limit OFFSET $offset";
        $r = \DB::select($q, [$itemsId]);
        return $r;
    }

}
