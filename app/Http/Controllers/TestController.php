<?php

namespace App\Http\Controllers;



class TestController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function __invoke()
    {
        exit('hi');
    }
}