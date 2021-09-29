<?php

namespace App\Http;

class Response{
    /**
     * Codigo de Status HTTP
     * @var integer
     */
    private $httpCode = 200;

    /**
     * Encabezado de respuesta
     * @var array
     */
    private $headers = [];

    /**
     * Tipo de contenido retornado
     * @var string
     */
    private $contentType = 'text/html';

    /**
     * Contenido de la respuesta
     * @var mixed
     */
    private $content;

    /**
     * Constructor
     * @param integer $httpCode
     * @param mixed $content
     * @param string $contentType
     */
    public function __construct($httpCode, $content, $contentType = 'text/html'){
        $this->httpCode = $httpCode;
        $this->content = $content;
        $this->setContentType($contentType);
    }
    
    /**
     * Metodo responsable de alterar un contentType del Response
     * @param string
     */
    public function setContentType($contentType){
        $this->contentType = $contentType;
        $this->addHeader('Content-Type', $contentType);
    }

    //Agrega un registro al header de respuesta
    public function addHeader($key, $value){
        $this->headers[$key] = $value;
    }

    //Envnia los headers al navegador
    private function sendHeaders(){
        //STATUS
        http_response_code($this->httpCode);

        //ENVIAR HEADERS
        foreach ($this->headers as $key => $value) {
            header($key.': '.$value);
        }
    }

    //Envia la respuesta al usuario
    public function sendResponse(){
        //ENVIA LOS HEADERS
        $this->sendHeaders();

        //IMPRIME EL CONTENIDO
        switch ($this->contentType) {
            case 'text/html':
                echo $this->content; 
                exit;
            case 'application/json':
                echo json_encode($this->content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); 
                exit;
        }
    }
}