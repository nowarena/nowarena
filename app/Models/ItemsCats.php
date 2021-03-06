<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemsCats extends Model
{
    protected $fillable = ['cats_id', 'items_id'];

    protected $table = 'items_cats';

    public function items()
    {
        return $this->hasMany('App\Models\Items');
    }

    public function getItemsCats($itemsColl, $itemsObj)
    {
        $itemsIdArr = $itemsObj->getIdArrFromColl($itemsColl);
        $itemsCatsColl = array();
        if (count($itemsIdArr)) {
            $itemsCatsColl = \DB::table('items_cats')
                ->whereIn('items_id', $itemsIdArr)->get();
        }
        return $itemsCatsColl;
    }
}
