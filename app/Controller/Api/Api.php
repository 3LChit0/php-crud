<?php

namespace App\Controller\Api;

class Api{

    public static function getDetails($request){
        return [
            'nombre'  => 'API - MINSAL',
            'version' => 'v1.0.0',
            'autor'   => 'José Guzmán',
            'email'   => 'jguzman@mail.com'
        ];
    }

    protected static function getPagination($request, $obPagination){
        $queryParams = $request->getQueryParams();

        $pages = $obPagination->getPages();

        return [
            'paginaActual' => isset($queryParams['page']) ? (int)$queryParams['page'] : 1,
            'cantidadPaginas' => !empty($pages) ? count($pages) : 1
        ];

    }

}