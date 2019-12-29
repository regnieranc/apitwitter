<?php
namespace App\Http\Controllers;

use App\User;
use Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt', ['except' => ['login', 'register']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }
    
    public function payload()
    {
        return response()->json(auth()->payload());
    }
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user(),
        ]);
    }

    public function register(Request $req){
    	try{
            $validator = Validator::make($req->all(), [
                'email' => 'required|max:255',
                'name' => 'required|max:255',
                'surname' => 'required|max:255',
                'password' => 'required|max:255'
            ]);
            if($validator->fails()){
                 $mensaje = [
                    'status' => '0',
                    'mensajes' => $validator->errors()
                 ];
                return json_encode($mensaje);
            }else{
                $user=User::where('email', $req['email'])->first();
                if(!$user){
                    $user=User::create([
                        'email' => $req['email'],
                        'name' => $req['name'],
                        'surname' => $req['surname'],
                        'password' => Hash::make($req['password']),
                    ]);
                    $mensaje = [
                        'status' => '1',
                        'user' => $user,
                        'mensajes' => 'Gracias por registrarte!!'
                     ];
                }else{
                    $mensaje = [
                        'status' => '-1',
                        'mensajes' => 'El usuario '.$req['email'].' ya existe, intenta con otros datos'
                     ];
                }
                
                return json_encode($mensaje);
            }
        }catch(Exception $e){
             $mensaje = [
                'status' => '0',
                'mensajes' => 'Ocurrio un error en la validacion'
             ];
            return json_encode($mensaje);
         
            }
    }
}