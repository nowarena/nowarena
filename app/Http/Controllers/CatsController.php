<?php

namespace App\Http\Controllers;
use Auth;

use Input;
use App\Models\Cats;
use App\Models\CatsPandC;
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
        $catsObj = new Cats();
        $catsPaginator = $catsObj->getCats($search, $sort, 3);
//        dd(DB::getQueryLog());
//        echo printR($catsPaginator);exit;
        $catsCollArr = $catsObj->pluck('title', 'id')->all();

        $catsPandCObj = new CatsPandC();
        $parentChildArr = $catsPandCObj->getParentChildArr();
        $parentChildFlattenedArr = $catsPandCObj->getFlattenedHier($catsCollArr);
        $parentChildHierArr = $catsPandCObj->getHierarchy();

        return view('cats.index', compact('catsPaginator', 'catsCollArr', 'parentChildArr', 'parentChildHierArr', 'sort', 'search', 'parentChildFlattenedArr'));
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
            'title' => 'required|min:3|unique:cats|max:30|regex:/^[a-zA-Z0-9_ -&]+$/',
            'description' => 'nullable|regex:/^[a-zA-Z0-9_ -]+$/',
            'parent_only' => 'nullable|integer'
        ]);
        Cats::create(['title' => $request->title,'description' => $request->description]);
        $id = DB::getPdo()->lastInsertId();
        if (!empty($request->parent_only)) {
            $catsPandCObj = new CatsPandC();
            $catsPandCObj->create(['parent_id' => 0, 'child_id' => $id]);
        }
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
            'title' => 'required|min:3|max:30|regex:/^[a-zA-Z0-9_,& -]+$/' . $uniqueTitleValidation,
            'description' => 'nullable|regex:/^[a-zA-Z0-9_ -]+$/',
            'child_cats_id_add' => 'nullable|integer',
            'parent_cats_id' => 'nullable|integer',
            'child_id_arr' => 'nullable',
            'parent_only' => 'nullable|integer'
        ]);
        $cats->title = $request->title;
        $cats->description = $request->description;
        $cats->update();

        $catsPandCObj = new CatsPandC();
        // Delete existing child_ids for parent_id
        $catsPandCObj->where('parent_id', '=', $request->parent_cats_id)->delete();
        //$catsPandCObj->where('child_id', '=', $request->parent_cats_id)->delete();

        // add any new child_id additions
        if (!empty($request->child_cats_id_add)) {
            DB::table('cats_p_and_c')->insert(array('child_id' => $request->child_cats_id_add, 'parent_id' => $request->parent_cats_id));
        }
        // insert child_ids that were submitted
        if (!empty($request->child_id_arr)) {
            foreach($request->child_id_arr as $childId) {
                $catsPandCObj->create(['parent_id' => $request->parent_cats_id, 'child_id' => $childId]);
            }
        }
        $catsPandCObj->where(['parent_id' => 0, 'child_id' => $request->parent_cats_id])->delete();
        if (!empty($request->parent_only)) {
            $catsPandCObj->create(['parent_id' => 0, 'child_id' => $request->parent_cats_id]);
        }

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
        CatsPandC::where('parent_id', '=', $cats->id)->delete();
        CatsPandC::where('child_id', '=', $cats->id)->delete();

        return redirect(route('cats.index'));
    }
}
