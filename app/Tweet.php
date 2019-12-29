<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Tweet extends Model
{
    protected $fillable = [
        'tweet','usuario_id'
    ];

    public static function getTweet($where=1){
    	return DB::select("select 
			t.id as id,
			t.tweet as tweet,
			t.created_at as created_at,
			count(if(l.tipo=1, 1, null)) as likes,
			count(if(l.tipo=0, 1, null)) as dislikes
			from tweets t
			left join likes l on t.id=l.tweet_id $where
			group by t.id, t.tweet, t.created_at
			order by t.created_at desc");
    }
}

