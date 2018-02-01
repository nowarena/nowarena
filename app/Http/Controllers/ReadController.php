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
$catsId = 1;

        // if no cats_id passed in, get top level cats
        if (empty($catsId)) {
            $r = Read::getTopLevel();
        } else {
            $r = Read::getLastCategories($catsId);
            // if there are no last categories, then get items under $catsId
        }
echo printR($r);
        return;

        $catsObj = new Cats();
        $catsCollArr = $catsObj->pluck('title', 'id')->all();
        echo printR($catsCollArr);

        return;
        return response()->json(array($r));

    }
}