<?php

namespace App\Http;

class Request{
    private $router;

    private $httpMethod;

    private $uri;

    private $queryParams = [];

    private $postVars = [];

    private $headers = [];

    public function __construct($router){
        $this->router      = $router;
        $this->queryParams = $_GET ?? [];
        $this->headers     = getallheaders();
        $this->httpMethod  = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->setUri();
        $this->setPostVars();
    }

    private function setPostVars(){
        if($this->httpMethod == 'GET') return false;
        $this->postVars = $_POST ?? [];
        $inputRaw = file_get_contents('php://input');
        $this->postVars =( strlen($inputRaw) && empty($_POST)) ? json_decode($inputRaw, true) : $this->postVars;
    }
    
    //Define la URI
    private function setUri(){
        $this->uri = $_SERVER['REQUEST_URI'] ?? '';

        $xURI = explode('?', $this->uri);
        $this->uri = $xURI[0];
    }

    public function getRouter(){
        return $this->router;
    }

    /**
     * Metodo responsable de retornar un metodo HTTP solicitado
     * @return string
     */
    public function getHttpMethod(){
        return $this->httpMethod;
    }

    /**
     * Metodo responsable de retornar URI solicitado
     * @return string
     */
    public function getUri(){
        return $this->uri;
    }

     /**
     * Metodo responsable de retornar header solicitado
     * @return array
     */
    public function getHeaders(){
        return $this->headers;
    }

     /**
     * Metodo responsable de retornar los parametros de la URL solicitado
     * @return array
     */
    public function getQueryParams(){
        return $this->queryParams;
    }

     /**
     * Metodo responsable de retornar las variables solicitadas
     * @return array
     */
    public function getPostVars(){
        return $this->postVars;
    }
}
