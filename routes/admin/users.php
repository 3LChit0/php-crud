<?php

use \App\Http\Response;
use \App\Controller\Admin;

 //RUTA DE LISTAR USUARIOS
 $obRouter->get('/admin/users',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\User::getUsers($request));
    }
]);

 //RUTA PARA REGISTRAR UN NUEVO USUARIO
 $obRouter->get('/admin/users/new',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\User::getNewUser($request));
    }
]);

 //RUTA PARA REGISTRAR UN NUEVO USUARIO (POST)
 $obRouter->post('/admin/users/new',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\User::setNewUser($request));
    }
]);

 //RUTA PARA EDITAR UN USUARIO
 $obRouter->get('/admin/users/{id}/edit',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request, $id){
        return new Response(200, Admin\User::getEditUser($request, $id));
    }
]);

 //RUTA PARA EDITAR UN USUARIO (POST)
 $obRouter->post('/admin/users/{id}/edit',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request, $id){
        return new Response(200, Admin\User::setEditUser($request, $id));
    }
]);

 //RUTA PARA ELIMINAR USUARIO
 $obRouter->get('/admin/users/{id}/delete',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request, $id){
        return new Response(200, Admin\User::getDeleteUser($request, $id));
    }
]);

//RUTA PARA ELIMINAR USUARIO (POST)
$obRouter->post('/admin/users/{id}/delete',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request, $id){
        return new Response(200, Admin\User::setDeleteUser($request, $id));
    }
]);