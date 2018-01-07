<?php

namespace App\Http\Controllers;
use Auth;

use Input;
use App\Models\Items;
use App\Models\ItemsCats;
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
        $request->validate([
            'search' => 'nullable|min:3|max:255|regex:/^[a-zA-Z0-9_ -]+$/'
        ]);
        $search = $request->search;
        $sort = $request->sort;
        $items = new Items();
        if ($sort == 'old') {
            $items = $items->orderBy('created_at', 'asc');
        } elseif ($sort == 'asc') {
            $items = $items->orderBy('title', 'asc');
        } elseif ($sort == 'desc') {
            $items = $items->orderBy('title', 'desc');
        } else {
            $items = $items->orderBy('created_at', 'desc');
        }
        if (!empty($search)) {
            $items = $items->where("title", "like", "%" . $search . "%");
        }

        $itemsColl = $items->paginate(3);

        $itemsIdArr = array();
        foreach ($itemsColl as $item) {
            $itemsIdArr[] = $item->id;
        }

        $itemsCatsColl = array();
        if (count($itemsIdArr)) {
            $itemsCatsColl = DB::table('items_cats')
                ->whereIn('items_id', $itemsIdArr)->get();
        }

        // Build lookup table of items_id that points to related cats_ids for that items_id
        $itemsCatsArr = array();
        $itemsArr = [];
        /*
        foreach($itemsColl as $itemsObj) {
            $hasCats = false;
            foreach($itemsCatsColl as $itemsCatsObj) {
                if ($itemsCatsObj->items_id == $itemsObj->id) {
                    $hasCats = true;
                    $itemsCatsArr[$itemsObj->id][$itemsCatsObj->cats_id][] = $itemsCatsObj->id;
                }
            }
            if ($hasCats == false) {
                // this saves on having to check for undefined before getting length of array
                $itemsCatsLookupArr[$itemsObj->id] = new \stdClass();
            }
            $itemsArr[$itemsObj->id] = $itemsObj->title;
        }
*/

        //$cats = new Cats();
        $catsArr = DB::table('cats')->select()->pluck('title', 'id');
//        print_r($catsArr);exit;
//        $newCatsArr = [];
//        foreach($catsArr as $i => $obj) {
//            $newCatsArr[$obj->id] = $obj->title;
//        }
//        $catsArr = $newCatsArr;

        $catsPandCObj = new CatsPandC();
        $catsObj = new Cats();
        $catsColl = $catsObj->pluck('title', 'id')->all();
        //$parentChildArr = $catsPandCObj->getParentChildArr();
        $parentChildFlattenedArr = $catsPandCObj->flattenHier($catsColl);
        $parentChildHierArr = $catsPandCObj->getHierarchy();



        return view(
            'items.index',
            compact('itemsColl', 'sort', 'search', 'catsArr', 'itemsArr', 'itemsCatsArr', 'itemsCatsColl', 'parentChildHierArr', 'parentChildFlattenedArr', 'catsColl')
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //return view('items.create');
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
            'title' => 'required|min:3|unique:items|max:30|regex:/^[a-zA-Z0-9_ -]+$/',
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Items  $items
     * @return \Illuminate\Http\Response
     */
    public function edit(Items $items)
    {
        return view('items.edit', compact('items'));
    }

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
            'title' => 'required|min:3|max:30|regex:/^[a-zA-Z0-9_ -]+$/' . $uniqueTitleValidation,
            'description' => 'nullable|regex:/^[a-zA-Z0-9_ -]+$/'
        ]);
        $items->title = $request->title;
        $itemsId = $request->input('items_id');
        $items->description = $request->description;
        $items->update();
        $page = $request->input('on_page');
        if (empty($page)) {
            $arr = array();
        } else {
            $arr = ['page' => $page];
        }

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
    public function destroy(Items $items)
    {
        $items->delete();
        return redirect(route('items.index'));
    }
}
