<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use \stdClass;
use App\Tweet;
use App\Like;
use DB;
use \DateTime;

class TweetsController extends Controller{

    public function show(Request $request){
    	$rsl=new stdClass;
    	$rsl->errores=[];
    	$rsl->proceso=1;
    	$rsl->codigo=null;
    	$rsl->data=null;
    	$validator = Validator::make($request->all(), [
    		'id' => 'required|integer'
    	]);
    	if($validator->fails()){
    		$rsl->errores=$validator->errors();
    		$rsl->proceso=0;
    	}else{
            $id=$request['id'];
            $rsl->data= Tweet::getTweet("where usuario_id=$id");
    	}
        return json_encode($rsl);
    }

    public function guardar(Request $request){
    	$rsl=new stdClass();
    	$rsl->proceso=1;
    	$rsl->errores=[];
    	$validator = Validator::make($request->all(), [
    		'tweet' => 'required|max:250'
    	]);

    	if($validator->fails()){
    		$rsl->errores=$validator->errors();
    		$rsl->proceso=0;
        }else{
        	Tweet::create([
        		'usuario_id' => $request['usuario_id'],
    			'tweet' => $request['tweet']
    		]);
        }
        return json_encode($rsl);
    }

    public function reaccion(Request $request){
        $rsl=new stdClass();
        $rsl->proceso=1;
        $rsl->errores=[];
        $date=new DateTime();
        $date=$date ->format('Y-m-d H:i:s');
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'usuario_id' => 'required|integer',
            'reaccion' => 'required|integer'
        ]);
        if($validator->fails()){
            $rsl->errores=$validator->errors();
            $rsl->proceso=0;
        }else{
            $id=$request['id'];
            $usuario_id=$request['usuario_id'];
            $existe=DB::select("select * from likes where tweet_id=$id and user_id=$usuario_id");
            if($existe){
                foreach ($existe as $value) {
                    DB::table('likes')->where("tweet_id", $id)->where("user_id", $usuario_id)->update(['tipo' => $request['reaccion'], 'updated_at' => $date]);
                }               
            }else{
                Like::create([
                    'user_id' => $request['usuario_id'],
                    'tweet_id' => $request['id'],
                    'tipo' => $request['reaccion']
                ]);
            }
        }
        echo json_encode($rsl);
    }
}
