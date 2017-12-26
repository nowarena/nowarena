<?php

namespace App\Http\Controllers;
use Auth;

use Input;
use App\Cats;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatsController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        DB::enableQueryLog();
        $request->validate([
            'search' => 'nullable|min:3|max:255|regex:/^[a-zA-Z0-9_ -]+$/'
        ]);
        $search = $request->search;
        $sort = $request->sort;
        $cats = new Cats();
        if ($sort == 'old') {
            $cats = $cats->orderBy('created_at', 'asc');
        } elseif ($sort == 'asc') {
            $cats = $cats->orderBy('title', 'asc');
        } elseif ($sort == 'desc') {
            $cats = $cats->orderBy('title', 'desc');
        } else {
            $cats = $cats->orderBy('created_at', 'desc');
        }
        if (!empty($search)) {
            $cats = $cats->where("title", "like", "%" . $search . "%");
        }

        $cats = $cats->paginate(3);

        return view('cats.index', compact('cats', 'sort', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cats.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:3|unique:cats|max:30|regex:/^[a-zA-Z0-9_ -]+$/',
            'description' => 'nullable|regex:/^[a-zA-Z0-9_ -]+$/'
        ]);
        Cats::create(['title' => $request->title,'description' => $request->description]);
        return redirect(route('cats.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cats  $cats
     * @return \Illuminate\Http\Response
     */
    public function show(Cats $cats)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cats  $cats
     * @return \Illuminate\Http\Response
     */
    public function edit(Cats $cats)
    {
        return view('cats.edit', compact('cats'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cats  $cats
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cats $cats)
    {

        $uniqueTitleValidation = '';
        if (trim(strtolower($request->title_old)) != trim(strtolower($request->title))) {
            $uniqueTitleValidation = '|unique:cats';
        }
        $request->validate([
            'title' => 'required|min:3|max:30|regex:/^[a-zA-Z0-9_ -]+$/' . $uniqueTitleValidation,
            'description' => 'nullable|regex:/^[a-zA-Z0-9_ -]+$/'
        ]);
        $cats->title = $request->title;
        $cats->description = $request->description;
        $cats->update();
        $page = $request->input('on_page');
        if (empty($page)) {
            $arr = array();
        } else {
            $arr = ['page' => $page];
        }

        return redirect()->route('cats.index', $arr);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cats  $cats
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cats $cats)
    {
        $cats->delete();
        return redirect(route('cats.index'));
    }
}
