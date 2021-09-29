<?php

use \App\Http\Response;
use \App\Controller\Api;

//RUTA RAIZ DE API
$obRouter->get('/api/v1', [
    'middlewares' => [
        'api'
    ],
    function($request){
        return new Response(200, Api\Api::getDetails($request), 'application/json');
    }
]);