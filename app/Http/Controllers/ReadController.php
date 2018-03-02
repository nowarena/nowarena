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

        $request->validate([
            'cats_id' => 'nullable|integer',
            'items_id' => 'nullable|integer'
        ]);

        $itemsId = !empty($request->items_id) ? $request->items_id : false;
        $catsId = !empty($request->cats_id) ? $request->cats_id : false;


//\DB::enableQueryLog();
//dd(\DB::getQueryLog());
//echo printR($x);
//$catsId = $request->cats_id;
//$catsId = 6;
//$itemsId = 16;

// number of social media entities per item. eg. 20 tweets by thebrigvenice
$offset = 0;
$limit = 20;

$catsIdArr = array(1,2,3,4,5,6,7,8,9);
//$catsIdArr = array(8);
foreach($catsIdArr as $catsId) {

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
            // if there are no children and $r is a scalar, then get items under $catsId
            if (!is_array($r) && $r !== false) {
                // get items of single category
                $itemsArr = Read::getItemsArrWithCatsId($r);
                $itemsArr = Read::getSocialMediaWithItemsArr($itemsArr, $offset, $limit);
                $itemsArr = Read::getContactInfo($itemsArr);
                if (empty($itemsArr)) {
                    return;
                }
                //echo printR($itemsArr);
                $catTitleArr = \DB::table('cats')->where('id', $catsId)->pluck('title');
                if (empty($catTitleArr)) {
                    return;
                }
                $cat = strtolower(preg_replace("~[^a-z0-9]+~is", "", $catTitleArr[0]));
                error_reporting(E_ALL);

                $itemsJson = json_encode($itemsArr);
                $filename = "/var/www/html/json/" . $cat . ".json";
                echo $filename."\n";
                echo '<pre>';
                echo htmlentities(json_encode($itemsArr, JSON_PRETTY_PRINT));
                echo '</pre>';
                file_put_contents($filename, $itemsJson);
                sleep(1);//allow watchify to see change for all

            } else {
                // no children of cats_id
                return;
            }
        }
}
        return;

        //$catsObj = new Cats();
        //$catsCollArr = $catsObj->pluck('title', 'id')->all();
        //echo printR($catsCollArr);

        //return;
        return response()->json($r);

    }

}