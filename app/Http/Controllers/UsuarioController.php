<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use \stdClass;
use App\User;
use DB;



class UsuarioController extends Controller{
    public function register(Request $request){
    	$rsl=new stdClass;
    	$rsl->erores=[];
    	$rsl->proceso=1;
    	$validator = Validator::make($request->all(), [
    		'nombre' => 'required|email',
    		'id' => 'required|max:255'
    	]);
    	if($validator->fails()){
    		$rsl->errores=$validator->errors();
    		$rsl->proceso=0;
            return json_encode($rsl);
        }
    }

    public function quienes(Request $request){
        $rsl = new stdClass();
        $rsl->proceso=1;
        $rsl->data=null;
        $validator = Validator::make($request->all(), [
            'nickname' => 'required',//nickname de la url
            'id' => 'required',//id persona logueada
        ]);
        if($validator->fails()){
            $rsl->errores=$validator->errors();
            $rsl->proceso=0;
        }else{
            $datayo = User::where("id", $request['id'])->first();
            $dataurl = User::where('nick', $request['nickname'])->first();
            $data = new stdClass();
            if($datayo && $dataurl){
                $miamigo = DB::select("select 
                                        * from conectar
                                        where 
                                        (mi_id=$datayo->id and amigo_id=$dataurl->id) or
                                        (amigo_id=$datayo->id and mi_id=$dataurl->id)
                                    ");
                if($miamigo){
                    $data->amigo=true;
                }else{
                    $data->amigo=false;
                }
                if($datayo->id==$dataurl->id){
                    $data->soyyo=true;
                    $data->idurl=$datayo->id;
                    $data->nombre=$datayo->name;
                    $data->nick=$datayo->nick;
                    $data->descripcion=$datayo->descripcion;
                    $data->foto=$datayo->foto;
                    $data->fotofondo=$datayo->fotofondo;
                }else{
                    $data->soyyo=false;
                    $data->idurl=$dataurl->id;
                    $data->nombre=$dataurl->name;
                    $data->nick=$dataurl->nick;
                    $data->descripcion=$dataurl->descripcion;
                    $data->foto=$dataurl->foto;
                    $data->fotofondo=$dataurl->fotofondo;
                }
                $rsl->data=$data;
            }else{
                $rsl->proceso=0;
            }
            
        }
        return json_encode($rsl);
    }

    public function siguiendo(Request $request){
        $rsl = new stdClass();
        $rsl->proceso=1;
        $rsl->errores=[];
        $rsl->data=null;
         $validator = Validator::make($request->all(), [
            'id' => 'required|integer',//id persona logueada
        ]);
        if($validator->fails()){
            $rsl->errores=$validator->errors();
            $rsl->proceso=0;
        }else{
            $rsl->data= User::getSiguiendo($request['id']);
        }
        return json_encode($rsl);
    }

    public function seguidores(Request $request){
        $rsl = new stdClass();
        $rsl->proceso=1;
        $rsl->errores=[];
        $rsl->data=null;
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',//id persona logueada
        ]);
        if($validator->fails()){
            $rsl->errores=$validator->errors();
            $rsl->proceso=0;
        }else{
            $rsl->data= User::getSeguidores($request['id']);
        }
        return json_encode($rsl);
    }

    public function informacion(Request $request){
        $rsl = new stdClass();
        $rsl->proceso=1;
        $rsl->errores=[];
        $rsl->data=null;
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',//id persona logueada
        ]);
        if($validator->fails()){
            $rsl->errores=$validator->errors();
            $rsl->proceso=0;
        }else{
            $rsl->data= User::getInformacion($request['id']);
        }
        return json_encode($rsl);
    }

}

