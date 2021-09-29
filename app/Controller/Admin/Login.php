<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\User;
use \App\Session\Admin\Login as SessionAdminLogin;

class Login extends Page{

    public static function getLogin($request, $errorMessage = null){

        $status = !is_null($errorMessage) ? Alert::getError($errorMessage) : '';

        $content = View::render('admin/login', [
            'status' => $status
        ]);

        return parent::getPage('Login > MINSAL', $content);
    }

    public static function setLogin($request){
        $postVars = $request->getPostVars();
        $email = $postVars['email'] ?? '';
        $password = $postVars['password'] ?? '';

        //BUSCA USUARIO POR EMAIL
        $obUser = User::getUserByEmail($email);
        if(!$obUser instanceof User){
            return self::getLogin($request, 'Email o contraseña invalidos');
        }
        
        //VERIFICACION DE CONTRASEÑA
        if(!password_verify($password, $obUser->password)){
            return self::getLogin($request, 'Email o contraseña invalidos');
        }

        //CREANDO SESION DE LOGIN
        SessionAdminLogin::login($obUser);

        $request->getRouter()->redirect('/admin');

    }

    /**
     * DESLOGAR USUARIO
     * @param Request $request
     */
    public static function setLogout($request){
        SessionAdminLogin::logout();

        $request->getRouter()->redirect('/admin/login');
    }
    
}