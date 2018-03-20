<?php

namespace App\Http\Controllers;


class WelcomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {

        return view('welcome');
    }
    public function nbablvd()
    {

        return view('nbablvd');
    }
    public function abbotkinneybl()
    {

        return view('abbotkinneybl');
    }
}
