<?php

namespace App\Http\Controllers;
use Auth;

use Input;
use App\Models\Items;
use App\Models\ItemsCats;
use App\Models\SocialMediaAccounts;
use App\Models\CatsPandC;
use App\Models\Cats;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemsController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
    }

    /*
     * 'listsocialmediaaccounts' web route using 'create'
     */
    public function create(Request $request)
    {
        $itemsObj = new Items();
        $itemsColl = $itemsObj->getItemsColl($request);
        $socialMediaAccountsObj = new SocialMediaAccounts();
        //\DB::enableQueryLog();
        $socialMediaAssocAccountsArr = $socialMediaAccountsObj->getAssocAccountsArr($itemsColl, $itemsObj);
        //$r = dd( \DB::getQueryLog() );
        //$socialMediaAccountsColl = $socialMediaAccountsObj->all()->sortByDesc('title');
        $socialMediaAccountsColl = SocialMediaAccounts::orderBy('username', 'asc')->get();
        $catsArr = DB::table('cats')->select()->pluck('title', 'id')->toArray();
        $searchCatsId = 0;
        if (!empty($request->cats_id)) {
            $searchCatsId = $request->cats_id;
        }
        //echo printR($socialMediaAccountsColl);
        //echo printR($socialMediaAssocAccountsArr);
        //exit;
        $search = $request->search;
        $sort = $request->sort;

        return view(
            'items.listsocialmediaaccounts',
            compact('itemsColl', 'sort', 'search', 'socialMediaAssocAccountsArr', 'socialMediaAccountsColl', 'catsArr', 'searchCatsId')
        );
    }

    /*
     * 'updatesocialmediaaccounts' web route using 'edit'
     */
    public function updatesocialmediaaccounts(Request $request)
    {

        // TODO validation
//        $request->validate([
//            'title' => "required|min:3|unique:items|max:30|regex:/^[a-zA-Z0-9_ -']+$/",
//            'description' => 'nullable|regex:/^[a-zA-Z0-9_ -]+$/'
//        ]);

        if ($request->action == 'add') {
            $arr = [
                'source_user_id' => $request->source_user_id,
                'username' => $request->username,
                'site' => $request->site,
                'is_active' => $request->is_active,
                'is_primary' => $request->is_primary,
                'use_avatar' => $request->use_avatar,
                'avatar' => $request->avatar
            ];

            SocialMediaAccounts::create($arr);
            return redirect()->route('items.listsocialmediaaccounts', array('search' => $request->username));

        }

        //\DB::enableQueryLog();
        SocialMediaAccounts::updateRow($request);

        $page = $request->input('on_page');
        if (empty($page)) {
            $paramArr = array();
        } else {
            $paramArr = ['page' => $page];
        }

        $searchCatsId = 0;
        if (!empty($request->cats_id)) {
            $searchCatsId = $request->cats_id;
        }
        $search = $request->search;
        $sort = $request->sort;
        if (!empty($search) || !empty($sort) || !empty($searchCatsId)) {
            $paramArr = array('search' => $search, 'sort' => $sort, 'cats_id' => $searchCatsId);
        }

        return redirect()->route('items.listsocialmediaaccounts', $paramArr);

    }



    /*
     * Associate an items_id with a cats_id in the items_cats join table
     */
    public function updateItemCat(Request $request)
    {
        $request->validate([
            'cats_id' => 'integer',
            'items_id' => 'integer',
            'items_cats_id' => 'integer'
        ]);
        $catsId = $request->cats_id;
        $itemsId = $request->items_id;
        $itemsCatsId = $request->items_cats_id;
        $itemsCats = new ItemsCats();
        $date= date('Y-m-d H:i:s');
        if ($catsId == 0) {
            $itemsCats->id = $itemsCatsId;
            $r = $itemsCats->destroy($itemsCatsId);
        }elseif ($itemsCatsId == 0) {
            $itemsCatsId = $itemsCats->insertGetId([
                'cats_id' => $catsId,
                'items_id' => $request->items_id,
                'created_at' => $date,
                'updated_at' => $date

                ]);
        } else {
            $itemsCats->where('id', $itemsCatsId)
                ->update(['items_id' => $itemsId, 'cats_id' => $catsId, 'updated_at' => $date]);
        }

        return response()->json(array('cats_id' => $catsId, 'items_id' => $itemsId, 'items_cats_id' => $itemsCatsId));

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        DB::enableQueryLog();


//        $o = \App\Models\ItemsCats::with(['items_cats', 'items'])->where('cats_id','=', 4 )->get();
//        echo printR($o);return;

        $itemsObj = new Items();
        $searchCatsId = 0;
        if (!empty($request->cats_id)) {
            $searchCatsId = $request->cats_id;
        }
        $itemsColl = $itemsObj->getItemsColl($request, 3);

        $itemsCatsObj = new ItemsCats();
        $itemsCatsColl = $itemsCatsObj->getItemsCats($itemsColl, $itemsObj);

        $socialMediaAccountsObj = new SocialMediaAccounts();
        $socialMediaAssocAccountsArr = $socialMediaAccountsObj->getAssocAccountsArr($itemsColl, $itemsObj);

        // Build lookup table of items_id that points to related cats_ids for that items_id
        $itemsCatsArr = array();
        $itemsArr = [];

        $catsArr = DB::table('cats')->select()->pluck('title', 'id')->toArray();

        $catsPandCObj = new CatsPandC();
        $catsObj = new Cats();
        $catsCollArr = $catsObj->pluck('title', 'id')->all();
        //$parentChildArr = $catsPandCObj->getParentChildArr();
        $parentChildFlattenedArr = $catsPandCObj->getFlattenedHier($catsCollArr);


        $parentChildHierArr = $catsPandCObj->getHierarchy();
        $search = $request->search;
        $sort = $request->sort;

        return view(
            'items.index',
            compact('itemsColl', 'sort', 'search', 'catsArr', 'itemsArr', 'itemsCatsArr', 'itemsCatsColl', 'parentChildHierArr', 'parentChildFlattenedArr', 'catsCollArr', 'socialMediaAssocAccountsArr', 'searchCatsId')
        );
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
            'title' => "required|min:3|unique:items|max:30|regex:/^[a-zA-Z0-9_ -']+$/",
            'description' => 'nullable|regex:/^[a-zA-Z0-9_ -]+$/'
        ]);
        Items::create(['title' => $request->title,'description' => $request->description]);
        return redirect(route('items.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Items  $items
     * @return \Illuminate\Http\Response
     */
    public function show(Items $items) {}


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Items  $items
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Items $items)
    {

        $uniqueTitleValidation = '';
        if (trim(strtolower($request->title_old)) != trim(strtolower($request->title))) {
            $uniqueTitleValidation = '|unique:items';
        }
        $request->validate([
            'title' => "required|min:3|max:30|regex:/^[a-zA-Z0-9_ -']+$/" . $uniqueTitleValidation,
            'description' => 'nullable|regex:/^[a-zA-Z0-9_ -]+$/'
        ]);
        $items->title = $request->title;
        $itemsId = $request->input('items_id');
        $items->description = $request->description;
        $items->update();
        $page = $request->input('on_page');
        $searchCatsId = 0;
        if (!empty($request->cats_id)) {
            $searchCatsId = $request->cats_id;
        }
        if (empty($page)) {
            $arr = array();
        } else {
            $arr = ['page' => $page];
        }
        $arr['cats_id'] = $searchCatsId;

        // update categories
        $request->validate([
            'catsIdArr.*' => 'nullable|integer'
        ]);
        $catsIdArr = $request->catsIdArr;

        // Delete existing cats for item
        DB::delete("DELETE FROM items_cats WHERE items_id = $itemsId");
        if (is_array($catsIdArr) && count($catsIdArr)) {
            // add submitted cats
            foreach($catsIdArr as $catsId) {
                Db::insert("INSERT INTO items_cats (items_id, cats_id, created_at, updated_at) VALUES (?, ?, NOW(), NOW())", [$itemsId, $catsId]);
            }
        }

        return redirect()->route('items.index', $arr);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Items  $items
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Items $items)
    {
        $page = $request->page;
        $items->delete();
        return redirect()->route('items.index', ['page' => $page]);
    }
}
