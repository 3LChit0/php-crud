<?php

namespace App\Model\Entity;

use \WilliamCosta\DatabaseManager\Database;

class Testimony{

    public $id;
    public $nombre;
    public $mensaje;
    public $data;

    public function registrar(){
        $this->data = date('Y-m-d H:i:s');

        $this->id = (new Database('declaraciones'))->insert([
            'nombre' => $this->nombre,
            'mensaje' => $this->mensaje,
            'data' => $this->data
        ]);

        return true;
    }

    public function actualizar(){
        return (new Database('declaraciones'))->update('id = '.$this->id,[
            'nombre' => $this->nombre,
            'mensaje' => $this->mensaje,
        ]);
    }

    public function eliminar(){
        return (new Database('declaraciones'))->delete('id = '.$this->id);
    }

    public static function getTestimonyById($id){
        return self::getTestimonies('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Retorna una Declaracion
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return PDOStatement
     */
    public static function getTestimonies($where = null, $order = null, $limit = null, $fields = '*'){
        return (new Database('declaraciones'))->select($where, $order, $limit, $fields);
    }
}