<?php

use \App\Http\Response;
use \App\Controller\Pages;

 //RUTA HOME
 $obRouter->get('/',[
    function(){
        return new Response(200, Pages\Home::getHome());
    }
]);

 //RUTA SOBRE
 $obRouter->get('/sobre',[
    function(){
        return new Response(200, Pages\About::getAbout());
    }
]);

//RUTA DINAMICA
// $obRouter->get('/pagina/{idPagina}/{accion}',[
//   function($idPagina,$accion){
//        return new Response(200, 'Página '.$idPagina,' - '.$accion);
//    }
//]);

 //RUTA DE DECLARACIONES
 $obRouter->get('/declaraciones',[
    function($request){
        return new Response(200, Pages\Testimony::getTestimonies($request));
    }
]);

 //RUTA DE DECLARACIONES (INSERT)
 $obRouter->post('/declaraciones',[
    function($request){
        return new Response(200, Pages\Testimony::insertTestimony($request));
    }
]);