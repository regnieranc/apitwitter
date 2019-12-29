<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = [
        'id','tweet_id', 'tipo', 'user_id'
    ];
}

