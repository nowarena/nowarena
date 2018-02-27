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
            'search' => 'nullable|min:3|max:255|regex:/^[a-zA-Z0-9_ ē-]+$/',
            'cats_id' => 'nullable|integer',
            'sort' => 'regex:/^[a-zA-Z0-9_ -]+$/'
        ]);
        $search = $request->search;
        $sort = $request->sort;
        $items = new Items();
        if (!empty($request->cats_id)) {

            $q = "SELECT items_id FROM items_cats WHERE cats_id = ?";
            $r = \DB::select($q, [$request->cats_id]);
            $itemsIdArr = array_column($r, 'items_id');
            $items = $items->whereIn('id', $itemsIdArr);//->get();

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
