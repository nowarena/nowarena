<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Instagram;
use MetzWeb\Instagram\Instagram as InstagramApi;
use Illuminate\Support\Facades\Config;


class InstagramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

       exit("gen access token. it should already be in .env");


//    $instagram = new Instagram(array(
//        'apiKey'      => '2488647005a54267a3fb19dc243e3efa',
//        'apiSecret'   => '37f827c4ad214e23a58d54e530781921',
//        'apiCallback' => ''//YOUR_APP_CALLBACK
//    ));
//1536088989.2488647.59a123b109674e81af1f0525bce5bdd4

        $providerKey = Config::get('services.instagram');

        //print_r($providerKey);exit;
        if (0 && empty($request->code)) {

            $url = "https://api.instagram.com/oauth/authorize/?client_id=" . $providerKey['client_id'];
            $url.="&redirect_uri=" . $providerKey['redirect'] . "&response_type=code";
            $url.="&scope=basic+public_content";
            //$url.="&scope=basic+public_content+follower_list+comments+relationships+likes";
            echo "<a href=$url>$url</a>";
        } else {
$code='226fa5e5aa8049eb81e93ac512e48d45';
            //echo "code:" . $request->code;
            //curl -F 'client_id=[clientID]' -F 'client_secret=[clientSecret]' -F 'grant_type=authorization_code'
            // -F 'redirect_uri=[redirectURI]' -F 'code=[code]' https://api.instagram.com/oauth/access_token

            $client = new \GuzzleHttp\Client();
            $res = $client->request('POST', 'https://api.instagram.com/oauth/access_token', [
                'form_params' => [
                    'client_id' => $providerKey['client_id'],
                    'client_secret' => $providerKey['client_secret'],
                    'grant_type' => 'authorization_code',
                    'redirect_uri' => $providerKey['redirect'],
                    'code' => $code
                ]
            ]);

            $jsonStr = $res->getBody();
            //print_r(json_decode($jsonStr));exit;
            $obj = json_decode($jsonStr);
            echo printR($obj);
            echo "<br>access_token:<br>";
            echo $obj->access_token;

        }

        //$user = Socialite::driver('instagram')->user();
        //$accessTokenResponseBody = $user->accessTokenResponseBody;
        //var_dump($user->token);
        //var_dump($accessTokenResponseBody);
        return;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //exit('exploring api');
        $providerKey = Config::get('services.instagram');
        echo printR($providerKey);
        $instaUrl = 'https://api.instagram.com/v1/tags/nofilter/media/recent?min_tag_id=0&max_tag_id=100000000&access_token=' . $providerKey['access_token'];
        echo "<a target=_blank href='$instaUrl'>insta</a>";

        echo "<br>";
        echo "<a href='https://api.instagram.com/v1/media/Bdqh_UgAn71?access_token=" . $providerKey['access_token'] . "' target=_blank>media</a>";
        echo "<br>";

//BdqVvaen5qe
        echo "<a href='https://api.instagram.com/v1/media/shortcode/Bdqh_UgAn71?access_token=" . $providerKey['access_token'] . "' target=_blank>shortcode</a>";
        echo "<br>";

        https://api.instagram.com/v1/users/self/media/liked?access_token=ACCESS-TOKEN

        echo "<a href='https://api.instagram.com/v1/users/self/media/recent?access_token=" . $providerKey['access_token'] . "' target=_blank>self feed</a>";
        echo "<br>";
        echo "<a href='https://api.instagram.com/v1/users/self/media/liked?access_token=" . $providerKey['access_token'] . "' target=_blank>liked</a>";

        echo "<br>";
        echo "<a href='https://api.instagram.com/v1/users/thebrigvenice?access_token=" . $providerKey['access_token'] . "' target=_blank>thebrigvenice</a>";


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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $providerKey = Config::get('services.instagram');
        $instagram = new InstagramApi(array(
            'apiKey'      => $providerKey['client_id'],
            'apiSecret'   => $providerKey['client_secret'],
            'apiCallback' => ''//YOUR_APP_CALLBACK
        ));
print_r($instagram->getUserFeed(1000));
        //if you only want to access public data:
        //new Instagram('YOUR_APP_KEY');



        exit('hi');
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
