<?php

namespace App\Http;

use Closure;
use Exception;
use ReflectionFunction;
use \App\Http\Middleware\Queue as MiddlewareQueue;

class Router{

    //URL completa del proyecto (raiz)
    private $url = '';

    //Prefix de todas las rutas
    private $prefix = '';

    //Indice de rutas
    private $routes = [];

    //Instancia del request
    private $request;

    private $contentType = 'text/html';

    //Constructor
    public function __construct($url){
        $this->request = new Request($this);
        $this->url     = $url;
        $this->setPrefix();
    }

    public function setContentType($contentType){
        $this->contentType = $contentType;
    }

    //Define el prefijo de las rutas
    private function setPrefix(){
        //Informacion de la url actual
        $parseUrl = parse_url($this->url);

        //Define el prefijo
        $this->prefix = $parseUrl['path'] ?? '';
    
    }

    //Adiciona una ruta a la clase
    private function addRoute($method, $route, $params = []){
        foreach ($params as $key => $value) {
            if($value instanceof Closure){
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }

        $params['middlewares'] = $params['middlewares'] ?? [];

        //Variables para la ruta
        $params['variables'] = [];

        $patternVariable = '/{(.*?)}/';
        if(preg_match_all($patternVariable, $route, $matches)){
            $route = preg_replace($patternVariable, '(.*?)', $route);
            $params['variables'] = $matches[1];
        }

        //Patron de validacion de url
        $patternRoute = '/^'.str_replace('/','\/',$route).'$/';

        //Adiciona una ruta dentro de la clase
        $this->routes[$patternRoute][$method] = $params;

    }


    public function get($route, $params = []){
        return $this->addRoute('GET', $route, $params);
    }
    public function post($route, $params = []){
        return $this->addRoute('POST', $route, $params);
    }
    public function put($route, $params = []){
        return $this->addRoute('PUT', $route, $params);
    }
    public function delete($route, $params = []){
        return $this->addRoute('DELETE', $route, $params);
    }


    private function getUri(){
        $uri = $this->request->getUri();
       
        
        //Cortar el uri con el prefijo
        $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];

        //Retorna la uri sin prefijo
        return rtrim(end($xUri), '/');
    } 

    //Retorna los datos de la ruta acctual
    private function getRoute(){
        //URI
        $uri = $this->getUri();
        
        $httpMethod = $this->request->getHttpMethod();

        foreach ($this->routes as $patternRoute => $methods) {
            if(preg_match($patternRoute, $uri, $matches)){
                if(isset($methods[$httpMethod])){
                    //elimina la primera posicion del array
                    unset($matches[0]);
                    //Variables procesadas
                    $keys = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
                    $methods[$httpMethod]['variables']['request'] = $this->request;

                    return $methods[$httpMethod];
                }
                    throw new Exception("Metodo no permitido", 405);
                
            }
        }
        throw new Exception("URL no encontrado", 404);
    }

    //Ejecuta la ruta actual
    //@return Response
    public function run(){
        try {
            //Obtiene la ruta actual
            $route = $this->getRoute();

            if(!isset($route['controller'])){
                throw new Exception("La URL no pudo ser procesada", 500);
            }

            $args = [];

            //ReflectionFunction
            $reflection = new ReflectionFunction($route['controller']);
            foreach ($reflection->getParameters() as $parameter) {
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
            }

           return (new MiddlewareQueue($route['middlewares'],$route['controller'],$args))->next($this->request);
        } catch (Exception $e) {
            return new Response($e->getCode(), $this->getErrorMessage($e->getMessage()), $this->contentType);
        }
    }

    //Retorna mensaje de error con ContentType (JSON)
    private function getErrorMessage($message){
        switch ($this->contentType) {
            case 'application/json':
                return [
                    'error' => $message
                ];
                break;
            
            default:
                return $message;
                break;
        }
    }

    //Retorna la URL actual
    public function getCurrentUrl(){
        return $this->url.$this->getUri(); 
    }

    public function redirect($route){
        $url = $this->url.$route;

        header('location: '.$url);
        exit;

    }
}
