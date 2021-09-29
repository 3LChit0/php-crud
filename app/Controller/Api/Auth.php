<?php

namespace App\Controller\Api;

use \App\Model\Entity\User;
use \Firebase\JWT\JWT;

class Auth extends Api{
    public static function generateToken($request){
        $postVars = $request->getPostVars();

        if(!isset($postVars['email']) or !isset($postVars['password'])){
            throw new \Exception("Los campos 'email' y 'password' son obligatorios.", 400);
        }
        //BUSCA USUARIO POR EMAIL
        $obUser = User::getUserByEmail($postVars['email']);
        if(!$obUser instanceof User){
            throw new \Exception("El usuario o la contraseña son invalidos", 400);
        }
        //VALIDA LA CONTRASEÑA DEL USUARIO
        if(!password_verify($postVars['password'], $obUser->password)){
            throw new \Exception("El usuario o la contraseña son invalidos", 400);
        }

        //PAYLOAD
        $payload = [
            'email' => $obUser->email
        ];

        //RETORNA UN TOKEN GENERADO
        return [
            'token' => JWT::encode($payload, getenv('JWT_KEY'))
        ];
    }
}