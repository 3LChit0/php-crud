<?php

use \App\Http\Response;
use \App\Controller\Admin;

 //RUTA DE LISTAR LAS DECLARACIONES
 $obRouter->get('/admin/testimonies',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Testimony::getTestimonies($request));
    }
]);

 //RUTA PARA REGISTRAR UNA NUEVA DECLARACION
 $obRouter->get('/admin/testimonies/new',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Testimony::getNewTestimony($request));
    }
]);

 //RUTA PARA REGISTRAR UNA NUEVA DECLARACION (POST)
 $obRouter->post('/admin/testimonies/new',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Testimony::setNewTestimony($request));
    }
]);

 //RUTA PARA EDITAR UNA DECLARACION
 $obRouter->get('/admin/testimonies/{id}/edit',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request, $id){
        return new Response(200, Admin\Testimony::getEditTestimony($request, $id));
    }
]);

 //RUTA PARA EDITAR UNA DECLARACION (POST)
 $obRouter->post('/admin/testimonies/{id}/edit',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request, $id){
        return new Response(200, Admin\Testimony::setEditTestimony($request, $id));
    }
]);

 //RUTA PARA ELIMINAR UNA DECLARACION
 $obRouter->get('/admin/testimonies/{id}/delete',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request, $id){
        return new Response(200, Admin\Testimony::getDeleteTestimony($request, $id));
    }
]);

//RUTA PARA ELIMINAR UNA DECLARACION (POST)
$obRouter->post('/admin/testimonies/{id}/delete',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request, $id){
        return new Response(200, Admin\Testimony::setDeleteTestimony($request, $id));
    }
]);