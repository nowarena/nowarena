<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;


class Items extends Model
{
    protected $fillable = ['title', 'description'];

    protected $table = 'items';

    public function cats()
    {
        return $this->belongsTo('App\Models\Cats');
    }

    public function itemscats()
    {
        return $this->belongsTo('App\Models\ItemsCats');
    }

    public function getItemsColl(Request $request, $perPage = 7)
    {
        $request->validate([
            'search' => 'nullable|min:3|max:255|regex:/^[a-zA-Z0-9_ -]+$/',
            'cats_id' => 'nullable|integer',
            'sort' => 'regex:/^[a-zA-Z0-9_ -]+$/'
        ]);
        $search = $request->search;
        $sort = $request->sort;
        $items = new Items();
        if (!empty($request->cats_id)) {

            $items->select('*' )
                ->join('items_cats', 'items_cats.items_id', '=', 'items.id')
                ->join('cats', 'cats.id', '=', 'items_cats.cats_id')
                ->where("cats.id", '=', $request->cats_id)
                ->where('items_cats.cats_id', '=', $request->cats_id);

//            $q = "SELECT items.id, items.title FROM items  LEFT JOIN items_cats ON items.id = items_cats.items_id LEFT JOIN cats ON cats.id = items_cats.cats_id WHERE items_cats.cats_id = ?";
//
//            $items = \DB::select($q, [$request->cats_id]);
//            echo printR($items);exit;
        }
        if ($sort == 'old') {
            $items = $items->orderBy('items.created_at', 'asc');
        } elseif ($sort == 'asc') {
            $items = $items->orderBy('items.title', 'asc');
        } elseif ($sort == 'desc') {
            $items = $items->orderBy('items.title', 'desc');
        } else {
            $items = $items->orderBy('items.created_at', 'desc');
        }
        if (!empty($search)) {
            $items = $items->where("items.title", "like", "%" . $search . "%");
        }

        $itemsColl = $items->paginate($perPage);
//        $laQuery = \DB::getQueryLog();
//        echo printR($laQuery);
//        exit;

        return $itemsColl;

    }

    public function getIdArrFromColl($itemsColl)
    {
        $itemsIdArr = array();
        foreach ($itemsColl as $item) {
            $itemsIdArr[] = $item->id;
        }

        return $itemsIdArr;

    }
}
