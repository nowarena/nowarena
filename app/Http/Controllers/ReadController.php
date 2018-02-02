<?php

namespace App\Http\Controllers;

use App\Models\SocialMedia;
use App\Models\Read;
use App\Models\Cats;
use Illuminate\Http\Request;


class ReadController extends Controller
{
    /**
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SocialMedia  $socialMediaObj
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, SocialMedia $socialMediaObj)
    {

        $o = new \App\Models\CatsPandC();


        //$x = $o->getFlattenedHier();

//\DB::enableQueryLog();
//dd(\DB::getQueryLog());
//echo printR($x);
$catsId = $request->cats_id;
$catsId = 32;
$itemsId = 38;
$offset = 0;
$limit = 3;


        if (!empty($itemsId)) {
            // get all social media for items_id eg. Dallas Cowboyws
            $itemsArr = \App\Models\Read::getItemsArrWithItemsId($itemsId, $offset, $limit);
            echo printR($itemsArr);
            if (count($itemsArr) == 0) {
                exit ("not finding items_id $itemsId");
            }
            $itemsArr = Read::getSocialMediaWithItemsArr($itemsArr);
            echo printR($itemsArr);
        } else if (empty($catsId)) {
            // if no cats_id passed in, get top level cats
            $r = Read::getTopLevel();
        } else {
            // get all children, if any, of cats_id
            $r = Read::getChildrenCategories($catsId);
            echo "child cats:";
            echo printR($r);
            // if there are no children and $r is a scalar, then get items under $catsId
            if (!is_array($r) && $r !== false) {
                // get items of single category
                $itemsArr = Read::getItemsArrWithCatsId($r);
                $itemsArr = Read::getSocialMediaWithItemsArr($itemsArr);
                echo printR($itemsArr);

            }
        }

        return;

        $catsObj = new Cats();
        $catsCollArr = $catsObj->pluck('title', 'id')->all();
        echo printR($catsCollArr);

        return;
        return response()->json(array($r));

    }

}