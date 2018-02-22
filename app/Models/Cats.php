<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cats extends Model
{
    protected $fillable = ['title', 'description'];

    protected $table = 'cats';

    public function items()
    {
        return $this->hasMany('App\Models\Items');
    }


    /*
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getCats($search, $sort, $perPage = 3)
    {
        $obj = $this;
        if ($sort == 'old') {
            $obj = $obj->orderBy('created_at', 'asc');
        } elseif ($sort == 'asc') {
            $obj = $obj->orderBy('title', 'asc');
        } elseif ($sort == 'desc') {
            $obj = $obj->orderBy('title', 'desc');
        } else {
            $obj = $obj->orderBy('created_at', 'desc');
        }
        if (!empty($search)) {
            $obj = $obj->where("title", "like", "%" . $search . "%");
        }
        return $obj->paginate($perPage);
    }

    public function getChildCats(\Illuminate\Pagination\LengthAwarePaginator $catsPaginator) {

    }

}
