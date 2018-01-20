<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialMediaUsers extends Model
{
    protected $fillable = ['user_id', 'username', 'site', 'active'];

}
