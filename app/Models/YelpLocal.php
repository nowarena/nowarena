<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\YelpTrait;

class YelpLocal extends Model
{
    use YelpTrait;

    protected $fillable = ['biz_id', 'name', 'description'];
    //
}
