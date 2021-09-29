<?php

namespace App\Http\Middleware;

class Maintenance{

    /**
     * EJECUTA UN MIDDLEWARE
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request, $next){
        //VERIFICA EL ESTADO DE MANTENIMIENTO DE LA PAGINA
        if(getenv('MAINTENANCE') == 'true'){
            throw new \Exception("Pagina en mantenimiento, favor intente mas tarde!!!", 200);
        }

        //EJECUTA EL PROXIMO NIVEL DEL MIDDLEWARE
        return $next($request);
    }

}