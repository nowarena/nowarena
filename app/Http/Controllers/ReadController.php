<?php

namespace App\Http\Controllers;

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
    public function __invoke(Request $request)
    {

        $o = new \App\Models\CatsPandC();


        //$x = $o->getFlattenedHier();

//\DB::enableQueryLog();
//dd(\DB::getQueryLog());
//echo printR($x);
//$catsId = $request->cats_id;
$catsId = 4;
//$itemsId = 16;
$offset = 1;
$limit = 3;



        if (!empty($itemsId)) {
            // get all social media for items_id eg. Dallas Cowboyws
            $itemsArr = Read::getItemsArrWithItemsId($itemsId);
            //echo printR($itemsArr);
            if (count($itemsArr) == 0) {
                exit ("not finding items_id $itemsId");
            }
            $itemsArr = Read::getSocialMediaWithItemsArr($itemsArr, $offset, $limit);
            $r = $itemsArr;
            //echo printR($itemsArr);
        } else if (empty($catsId)) {
            // if no cats_id passed in, get top level cats
            $r = Read::getTopLevel();
        } else {
            // get all children, if any, of cats_id
            $r = Read::getChildrenCategories($catsId);
            //echo "child cats:";
            //echo printR($r);
            // if there are no children and $r is a scalar, then get items under $catsId
            if (!is_array($r) && $r !== false) {
                // get items of single category
                $itemsArr = Read::getItemsArrWithCatsId($r);
                $itemsArr = Read::getSocialMediaWithItemsArr($itemsArr, $offset, $limit);
                //echo printR($itemsArr);
                $catTitleArr = \DB::table('cats')->where('id', $catsId)->pluck('title');
                if (empty($catTitleArr)) {
                    return;
                }
                $cat = strtolower(str_replace(" ", "_", $catTitleArr[0]));
                error_reporting(E_ALL);


                file_put_contents("/var/www/html/json/" . $cat . ".json", json_encode($itemsArr));
                return;
            } else {
                // no children of cats_id
                return;
            }
        }

        //return;

        //$catsObj = new Cats();
        //$catsCollArr = $catsObj->pluck('title', 'id')->all();
        //echo printR($catsCollArr);

        //return;
        return response()->json($r);

    }

}