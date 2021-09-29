<?php

namespace App\Http\Middleware;

use \App\Model\Entity\User;
use \Firebase\JWT\JWT;

class JWTAuth{

    private function getJWTAuthUser($request){  
        //HEADERS
        $headers = $request->getHeaders();
        //TOKEN ORIGINAL JWT
        $jwt = isset($headers['Authorization']) ? str_replace('Bearer ','', $headers['Authorization']) : '';

        try {
            //DECODE
            $decode = (array)JWT::decode($jwt, getenv('JWT_KEY'), ['HS256']);
        } catch (\Exception $e) {
            throw new \Exception("Token invalido", 403);
        }
        
        //EMAIL
        $email = $decode['email'] ?? '';

        $obUser = User::getUserByEmail($email);

        //RETORNA USUARIO
        return $obUser instanceof User ? $obUser : false;
    }

    //VALIDA EL ACCESO VIA JWT
    private function auth($request){
        if($obUser = $this->getJWTAuthUser($request)){
            $request->user = $obUser;
            return true;
        }

        throw new \Exception("Acceso denegado.", 404);
    }

    /**
     * EJECUTA UN MIDDLEWARE
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request, $next){
        //REALIZA LA VALIDACION DE ACCESO VIA JWT
        $this->auth($request);        

        //EJECUTA EL PROXIMO NIVEL DEL MIDDLEWARE
        return $next($request);
    }

}