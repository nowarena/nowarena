<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;


class Items extends Model
{
    protected $fillable = ['title', 'description'];

    public function getItemsColl(Request $request, $perPage = 7)
    {
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

        $itemsColl = $items->paginate($perPage);

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
