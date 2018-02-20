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
        //echo __METHOD__ . printR($parentCatsArr);
        foreach($parentCatsArr as $obj) {
            $dataArr[$obj->id]['title'] = $obj->title;
            $dataArr[$obj->id]['cats_id'] = $obj->id;
            //$dataArr = self::getItems($obj, $dataArr);
            //$childrenArr = self::getChildren($obj, $dataArr[$obj->id]);
            //if (count($childrenArr)) {
            //    $dataArr[$obj->id]['children'] = $childrenArr;
            //}
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
        //echo preg_replace("~ = \?~s", " = $id", $q) . ";<br>";
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

    public static function getChildrenCategories($catsId)
    {
        $o = new \App\Models\CatsPandC();
        $catsArr = $o->getFlattenedHier();
        //echo printR($catsArr);
        $r = self::processChildrenCategories($catsArr, $catsId);
//        echo "<hr>";
//        echo printR($r);
//        echo "<hr>";
        return $r;
    }

    private static function processChildrenCategories($catsArr, $currentCatsId)
    {

        if (!is_array($catsArr)) {
            return false;
        }

        foreach($catsArr as $id => $arr) {

            if ($id == $currentCatsId) {
                return $arr;
            }
            if (!is_array($arr)) {
                continue;
            }
            if (is_array($arr)) {
                $r = self::processChildrenCategories($arr, $currentCatsId);
                if ($r !== false) {
                    return $r;
                }
            }

        }

        return false;

    }

//    public static function getItemsArrWithCatsId($catsId)
//    {
//
//        $itemsArr = self::getItemsArrWithCatsId($catsId);
//        foreach($itemsArr as $obj) {
//            $itemsIdArr[$obj->items_id] = $obj->title;
//        }
//        echo "<hr>itemsIdArr:<br>";
//        echo printR($itemsIdArr);
//
//    }

    public static function getItemsArrWithCatsId($catsId)
    {

        $q = "SELECT title, description, items_id FROM items
              INNER JOIN items_cats ON items.id = items_cats.items_id 
              WHERE cats_id = ?";
        $r = \DB::select($q, [$catsId]);
        return $r;
//        $newArr = [];
//        foreach($r as $obj) {
//            $newArr[$obj->items_id] = $obj->title;
//        }
//        return $newArr;
    }

    public static function getItemsArrWithItemsId($itemsId)
    {

        $q = "SELECT id as items_id, title, description  
              FROM items
              WHERE id = ?";
        $r = \DB::select($q, [$itemsId]);
        return $r;
    }

    public static function getSocialMediaWithItemsArr($itemsArr, $offset = 0, $limit = 3)
    {

        $dbArr = self::qSocialMediaWithItemsArr($itemsArr, $offset, $limit);
        $finalItemsArr = self::sortFinalItemsArr($itemsArr, $dbArr);

        return $finalItemsArr;

    }

    public static function qSocialMediaWithItemsArr($itemsArr, $offset, $limit)
    {

        //$itemsIdArr = array_filter(array_map(function($n) { return $n->items_id; }, $itemsArr));
        if (count($itemsArr) == 0) {
            return false;
        }
        //echo __METHOD__ . printR($itemsArr);

        $dbArr = [];
        foreach($itemsArr as $key => $obj) {
            $q = "SELECT sm.*, UNIX_TIMESTAMP(sm.created_at) as created_at_ut, sma.items_id, sma.username, sma.avatar, sma.site  
                  FROM social_media sm 
                  INNER JOIN social_media_accounts sma on sma.source_user_id = sm.source_user_id 
                  WHERE 1 =1 
                  AND sma.items_id = ?  
                  AND is_active = 1  
                  ORDER BY sm.created_at DESC 
                  LIMIT $limit OFFSET $offset";
            $r = \DB::select($q, array($obj->items_id));
            if (count($r)) {
                $dbArr[] = $r;
            }

        }

        return $dbArr;

    }

    private static function sortFinalItemsArr($itemsArr, $dbArr)
    {

        $sortArr = [];
        $newItemsArr = [];
        foreach($itemsArr as $itemObj) {
            foreach($dbArr as $dbRow) {
                if ($dbRow[0]->items_id == $itemObj->items_id) {
                    $newItemsArr[$itemObj->items_id] = $itemObj;
                    $newItemsArr[$itemObj->items_id]->social_media = $dbRow;
                    $sortArr[$itemObj->items_id] = strtotime($dbRow[0]->created_at);
                    break;
                }
            }
        }


        $finalItemsArr = [];
        if (!empty($sortArr)) {
            arsort($sortArr);
            echo printR($sortArr);
            foreach($sortArr as $ut) { echo date("Y-M-d H:i:s", $ut)."<br>";}
            foreach($sortArr as $itemsId => $ut) {
                $finalItemsArr[$itemsId] = $newItemsArr[$itemsId];
            }
        } else {
            $finalItemsArr = $newItemsArr;
        }

        return $finalItemsArr;

    }

    public static function getSocialMediaWithItemId($itemsId, $offset = 0, $limit = 3)
    {

        $itemsObj = new \stdClass();
        $itemsObj->items_id = $itemsId;
        $itemsArr = array($itemsObj);
        return self::getSocialMediaWithItemsArr($itemsArr, $offset, $limit);

    }

}
