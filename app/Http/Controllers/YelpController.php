<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\YelpFusion;
use App\Models\Yelp;
use Illuminate\Support\Facades\Config;

class YelpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->action == 'reviews') {

            $yelpObj = new Yelp();

            \DB::enableQueryLog();
            $r = $yelpObj->getFeed();
            $r = $yelpObj->convertFeedToSocialMedia();

        } elseif ($request->action == 'details') {
            // phone
            // avatar aka image_url
            // address
            // lat/long
            // hours
           // $yelpObj = $yelp->bizlookup('gjelina-venice-2', $oauthToken, 0);


        }
        echo printR($r);
        return;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
