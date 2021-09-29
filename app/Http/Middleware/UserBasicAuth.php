<?php

namespace App\Http\Middleware;

use \App\Model\Entity\User;

class UserBasicAuth{

    private function getBasicAuthUser(){
        if(!isset($_SERVER['PHP_AUTH_USER']) or !isset($_SERVER['PHP_AUTH_PW'])){
            return false;
        }

        $obUser = User::getUserByEmail($_SERVER['PHP_AUTH_USER']);

        if (!$obUser instanceof User){
            return false;
        }

        return password_verify($_SERVER['PHP_AUTH_PW'], $obUser->password) ? $obUser : false;
    }

    //VALIDA EL ACCESO VIA HTTP BASIC AUTH
    private function basicAuth($request){
        if($obUser = $this->getBasicAuthUser()){
            $request->user = $obUser;
            return true;
        }

        throw new \Exception("Usuario o contraseña invalido.", 404);
    }

    /**
     * EJECUTA UN MIDDLEWARE
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request, $next){
        //REALIZA LA VALIDACION DE ACCESO VIA BASIC AUTH
        $this->basicAuth($request);        

        //EJECUTA EL PROXIMO NIVEL DEL MIDDLEWARE
        return $next($request);
    }

}