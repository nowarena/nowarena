<?php

namespace App\Http\Controllers;

use App\Models\Tweets;
use Illuminate\Http\Request;

class TwitterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return;
        // Quick setup
        // After 'friends' or people your twitter account follows have been saved via /twitter/getfriends...
        // Save all twitter users in items table giving items.title the value of username from social_media_accounts
        $q="INSERT INTO items (title) SELECT username FROM social_media_accounts";
        $r = \DB::select($q);
        // Update social_media_accounts with items_id from items...
        $q="select items.id, username from items inner join social_media_accounts sma on items.title=sma.username";
        $r = \DB::select($q);
        foreach($r as $obj) {
            $q = "update social_media_accounts set items_id = " . $obj->id . " WHERE username='" . $obj->username . "'";
            \DB::select($q);
        }
    }

    /**
     *
     * /twitter/getfeed
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        \DB::enableQueryLog();
        $twitterObj = new Tweets();
        $r = $twitterObj->getFeed();
        $twitterObj->saveFeed($r);
        $twitterObj->convertFeedToSocialMedia();
        return;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        \DB::enableQueryLog();
        $twitterObj = new Tweets();
        $twitterObj->convertFeedToSocialMedia();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Twitter  $twitter
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $twitterObj = new Tweets();
        $twitterObj->saveFriends();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Twitter  $twitter
     * @return \Illuminate\Http\Response
     */
    public function edit(Twitter $twitter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Twitter  $twitter
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Twitter $twitter)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Twitter  $twitter
     * @return \Illuminate\Http\Response
     */
    public function destroy(Twitter $twitter)
    {
        //
    }
}
