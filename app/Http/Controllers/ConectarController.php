<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use \stdClass;
use \DateTime;
use App\User;
use DB;



class ConectarController extends Controller{
    public function seguir(Request $request){
        $date=new DateTime();
        $date=$date ->format('Y-m-d H:i:s');
        $rsl=new stdClass;
        $rsl->errores=[];
        $rsl->proceso=1;
        $rsl->data=null;
        $validator = Validator::make($request->all(), [
            'miid' => 'required|integer',
            'usuarioid' => 'required|integer'
        ]);
        if($validator->fails()){
            $rsl->errores=$validator->errors();
            $rsl->proceso=0;
        }else{
            $miid=$request['miid'];
            $usuarioid=$request['usuarioid'];
            if($miid!=$usuarioid){
                DB::table('conectar')->insert(["mi_id" => $miid, "amigo_id" => $usuarioid, "solicitud"=> 1, "created_at" => $date]);
            }else{
                array_push($rsl->errores, ["El usuario es el mismo"]);
                $rsl->proceso=0;
            }
            
        }
        return json_encode($rsl);
    }

    public function dejarseguir(Request $request){
        $rsl=new stdClass;
        $rsl->errores=[];
        $rsl->proceso=1;
        $rsl->data=null;
        $validator = Validator::make($request->all(), [
            'miid' => 'required|integer',
            'usuarioid' => 'required|integer'
        ]);
        if($validator->fails()){
            $rsl->errores=$validator->errors();
            $rsl->proceso=0;
        }else{
            $miid=$request['miid'];
            $usuarioid=$request['usuarioid'];
            if($miid!=$usuarioid){
                $existemiid=User::where('id', $miid);
                $existeamigoid=User::where('id', $usuarioid);
                if($existeamigoid && $existemiid){
                    $sonamigos=DB::select("select * from conectar where (mi_id=$miid and amigo_id=$usuarioid)
                                            or (amigo_id=$miid and mi_id=$usuarioid)");
                    if($sonamigos){
                        DB::table("conectar")->where('mi_id', $miid)->where('amigo_id', $usuarioid)->orWhere('mi_id', $usuarioid)->orWhere('amigo_id', $miid)->delete();
                    }else{
                        array_push($rsl->errores, ["Los usuarios no son amigos"]);
                        $rsl->proceso=0;
                    }
                }else{
                    array_push($rsl->errores, ["Hay usuarios que no existen"]);
                    $rsl->proceso=0;
                }
            }else{
                array_push($rsl->errores, ["El usuario es el mismo"]);
                $rsl->proceso=0;
            }
            
        }
        return json_encode($rsl);
    }

    
}

