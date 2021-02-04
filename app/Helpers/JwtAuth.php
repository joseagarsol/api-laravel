<?php
namespace App\Helpers;

use Firebase\JWT\JWT;
use Iluminate\support\Facades\DB;
use App\User;

class JwtAuth{

    public $key;

    public function __construct(){
        $this->key = 'esto_es_una_clave_super_secreta-115654165';
    }
    public function signup($email,$password, $getToken = null)
    {
        //Buscar existe el usuario con sus credenciales
        $user = User::where([
            'email' => $email,
            'password' => $password
        ])->first();
        //Comprobar si son correctas(objeto)
        $signup = false;
        if (is_object($user)){
            $signup = true;
        }
        //Generar el toker con los datos del usuario identificado
        if ($signup){
            $token = array(
                'sub' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'surname' => $user->surname,
                'description' => $user->description,
                'image' => $user->image,
                'iat' => time(),
                'exp' => time() + ( 7 * 24 * 60 * 60)
            );
            $jwt = JWT::encode($token,$this->key,'HS256');
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);
            if(is_null($getToken)){
                $data = $jwt;
            }else{
                $data = $decoded;
            }
        }else{
            $data = array(
                'status' => 'error',
                'message' => 'Login incorrecto'
            );
        }
        //Devolver los datos descodificados o el token, en funcion del parametro
        return $data;
    }
    public function checkToken($jwt , $getIdentify = false){
        $auth = false;
        try{
            $jwt = str_replace('"','',$jwt);
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);
        }
        catch(\UnexpectedValueException $e){
            $auth = false;
        }catch(\DomainException $e){
            $auth = false;
        }

        if(!empty($decoded) && is_object($decoded) && isset($decoded->sub)){
            $auth = true;
        }else{
            $auth = false;
        }
        if($getIdentify){
            return $decoded;
        }
        return $auth;
    }
}