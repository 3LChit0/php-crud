<?php

namespace App\Session\Admin;

class Login{

    //INICIA SESION
    private static function init(){
        //VERIFICA SI LA SESION ESTA ACTIVA
        if(session_status() != PHP_SESSION_ACTIVE){
            session_start();
        }
    }

    /**
     * CREA UN LOGIN DE USUARIO
     * @param User $obUser
     * @return boolean
     */
    public static function login($obUser){
        //INICIA LA SESION
        self::init();

        $_SESSION['admin']['usuario'] = [
            'id' => $obUser->id,
            'nombre' => $obUser->nombre,
            'email' => $obUser->email
        ];

        return true;
    }

    /**
     *VERIFICA SI EL USUARIO ESTA LOGADO
     *@return boolean 
     */
    public static function isLogged(){
        self::init();

        return isset($_SESSION['admin']['usuario']['id']);

    }

    //LOGOUT DE USUARIO
    public static function logout(){
        self::init();
        //DESLOGA USUARIO
        unset($_SESSION['admin']['usuario']);

        return true;
    }

}