<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cats extends Model
{
    protected $fillable = ['title', 'description'];


    /*
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getCats($search, $sort, $perPage)
    {
        if ($sort == 'old') {
            $this->orderBy('created_at', 'asc');
        } elseif ($sort == 'asc') {
            $this->orderBy('title', 'asc');
        } elseif ($sort == 'desc') {
            $this->orderBy('title', 'desc');
        } else {
            $this->orderBy('created_at', 'desc');
        }
        if (!empty($search)) {
            $this->where("title", "like", "%" . $search . "%");
        }
        return $this->paginate(3);
    }

    public function getChildCats(\Illuminate\Pagination\LengthAwarePaginator $catsPaginator) {

    }

}
