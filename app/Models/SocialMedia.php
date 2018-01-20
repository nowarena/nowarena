<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    protected $fillable = ['username', 'user_id', 'text', 'site', 'created_at', 'source_id', 'link'];
}
