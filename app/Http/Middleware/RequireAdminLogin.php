<?php

namespace App\Http\Middleware;

use \App\Session\Admin\Login as SessionAdminLogin;

class RequireAdminLogin{

    public function handle($request, $next){
        //verifica si el usuario esta logado
        if(!SessionAdminLogin::isLogged()){
            $request->getRouter()->redirect('/admin/login');
        }

        //Contina la ejecucion
        return $next($request);
    }

}