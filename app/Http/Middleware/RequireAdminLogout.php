<?php

namespace App\Http\Middleware;

use \App\Session\Admin\Login as SessionAdminLogin;

class RequireAdminLogout{

    public function handle($request, $next){
        //verifica si el usuario esta logado
        if(SessionAdminLogin::isLogged()){
            $request->getRouter()->redirect('/admin');
        }

        //Contina la ejecucion
        return $next($request);
    }

}