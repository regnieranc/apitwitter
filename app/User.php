<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

use DB;
use \stdClass;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','nick', 'foto'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
       // 'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public static function getSiguiendo($where){
        return DB::select("select c.id, c.created_at, u.foto, u.name, u.nick, u.descripcion from conectar c join users u on u.id=c.amigo_id where c.mi_id=$where");
    }

    public static function getSeguidores($where){
        return DB::select("select c.id, c.created_at, u.foto, u.name, u.nick, u.descripcion from conectar c join users u on u.id=c.mi_id where c.amigo_id=$where");
    }
    public static function getInformacion($id){
        $rsl=new stdClass();
        $rsl->tweets=DB::select("select count(*) as tweets from tweets where usuario_id=$id")[0]->tweets;
        $rsl->seguidores=DB::select("select count(*) as seguidores from conectar where amigo_id=$id")[0]->seguidores;
        $rsl->siguiendo=DB::select("select count(*) as siguiendo from conectar where mi_id=$id")[0]->siguiendo;
        return $rsl;
    }
}
