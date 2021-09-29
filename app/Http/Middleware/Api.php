<?php

namespace App\Http\Middleware;

class Api{

    /**
     * EJECUTA UN MIDDLEWARE
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request, $next){
        //EJECUTA CONTENT TYPE PARA JSON
        $request->getRouter()->setContentType('application/json');

        //EJECUTA EL PROXIMO NIVEL DEL MIDDLEWARE
        return $next($request);
    }

}