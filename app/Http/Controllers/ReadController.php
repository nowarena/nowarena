<?php

namespace App\Http\Controllers;

use App\Models\SocialMedia;
use App\Models\Read;
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

        $r = new \App\Models\CatsPandC();
        $x=$r->getFlattenedHier();
//\DB::enableQueryLog();
//dd(\DB::getQueryLog());
echo printR($x);
        // if no cats_id passed in, get top level cats
        if (empty($request->cats_id)) {
            $r = Read::getTopLevel();
        }
echo PrintR($r);return;
        return response()->json(array($r));

    }
}