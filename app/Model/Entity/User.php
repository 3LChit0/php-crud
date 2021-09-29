<?php

namespace App\Model\Entity;

use \WilliamCosta\DatabaseManager\Database;

class User{

    public $id;
    public $nombre;
    public $email;
    public $password;

    public function registrar(){
        $this->id = (new Database('usuarios'))->insert([
            'nombre'   => $this->nombre,
            'email'    => $this->email,
            'password' => $this->password
        ]);

        return true;
    }

    //ACTUALIZA USUARIO EN BD
    public function actualizar(){
        return (new Database('usuarios'))->update('id = '.$this->id,[
            'nombre'   => $this->nombre,
            'email'    => $this->email,
            'password' => $this->password
        ]);
    }

    //ELIMINA USUARIO EN BD
    public function eliminar(){
        return (new Database('usuarios'))->delete('id = '.$this->id);
    }

    //BUSCA EL USUARIO EN BASE A SU ID
    public static function getUserById($id){
        return self::getUsers('id = '.$id)->fetchObject(self::class);
    }

    //BUSCA EL USUARIO EN BASE A SU EMAIL
    public static function getUserByEmail($email){
        return self::getUsers('email = "'.$email.'"')->fetchObject(self::class);
    }

    public static function getUsers($where = null, $order = null, $limit = null, $fields = '*'){
        return (new Database('usuarios'))->select($where, $order, $limit, $fields);
    }
}