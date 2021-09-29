<?php

namespace App\Http\Middleware;

class Queue{

    //MAPEO DE MIDDLEWARES
    private static $map = [];

    //MAPEO DE MIDDLEWARES EN TODAS LAS RUTAS 
    private static $default = [];

    private $middlewares = [];

    
    // Funcion de ejecucion del controlador
    private $controller;

    //Argumentos del controlador
    private $controllerArgs = [];

    public function __construct($middlewares, $controller, $controllerArgs){
        $this->middlewares = array_merge(self::$default, $middlewares);
        $this->controller = $controller;
        $this->controllerArgs = $controllerArgs;
    }

    public static function setMap($map){
        self::$map = $map;
    }
    
    public static function setDefault($default){
        self::$default = $default;
    }

    public function next($request){
        //Verifica si la fila esta vacia
        if (empty($this->middlewares)) return call_user_func_array($this->controller, $this->controllerArgs);

        $middleware = array_shift($this->middlewares);

        if(!isset(self::$map[$middleware])){
            throw new \Exception("Problemas al procesar un middleware", 500);
        }
 
        $queue = $this;
        $next = function($request) use($queue){
            return $queue->next($request);
        };

        //EJECUTA UN MIDDLEWARE
        return (new self::$map[$middleware])->handle($request, $next);
    }

}