<?php

namespace App\Controller\Api;

use \App\Model\Entity\User as EntityUser;
use \WilliamCosta\DatabaseManager\Pagination;

class User extends Api{

    private static function getUserItems($request, &$obPagination){
        //DECLARACIONES
        $items = [];

        //CANTIDAD TOTAL DE REGISTROS
        $cantidadTotal = EntityUser::getUsers(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

        //PAGINA ACTUAL
        $queryParams = $request->getQueryParams();
        $paginaActual = $queryParams['page'] ?? 1;

        //INSTANCIA DE PAGINA
        $obPagination = new Pagination($cantidadTotal, $paginaActual, 3);

        //RESULTADO DE PAGINA
        $results = EntityUser::getUsers(null, 'id ASC', $obPagination->getLimit());

        while($obUser = $results->fetchObject(EntityUser::class)){
            $items[] = [
                'id'     => (int)$obUser->id,
                'nombre' => $obUser->nombre,
                'email'  => $obUser->email
            ];
        }

        return $items;
    }


    public static function getUsers($request){
        return [
            'usuarios'   => self::getUserItems($request, $obPagination),
            'paginacion' => parent::getPagination($request, $obPagination)
        ];
    }

    public static function getUser($request, $id){
        if(!is_numeric($id)){
            throw new \Exception("El id '".$id."' no es valido", 400);
        }

        $obUser = EntityUser::getUserById($id);
        
        if(!$obUser instanceof EntityUser){
            throw new \Exception("El usuario ".$id." no fue encontrada", 404);
        }

        //RETORNA LOS DETALLES DE LA DECLARACION
        return [
            'id'     => (int)$obUser->id,
            'nombre' => $obUser->nombre,
            'email'  => $obUser->email
        ];
    }

    //RETORNA EL USUARIO ACTUALMENTE CONECTADO
    public static function getCurrentUser($request){
        $obUser = $request->user;

        return [
            'id'     => (int)$obUser->id,
            'nombre' => $obUser->nombre,
            'email'  => $obUser->email
        ];
    }

    public static function setNewUser($request){
        $postVars = $request->getPostVars();
        if(!isset($postVars['nombre']) or !isset($postVars['email']) or !isset($postVars['password'])){
            throw new \Exception("Los campos 'nombre', 'email' y 'contraseña' son obligatorios", 400);   
        }
        
        $obUserEmail = EntityUser::getUserByEmail($postVars['email']);
        if($obUserEmail instanceof EntityUser){
            throw new \Exception("El email '".$postVars['email']."' ya esta registrado.", 400);
        }

        $obUser = new EntityUser;
        $obUser->nombre   = $postVars['nombre'];
        $obUser->email    = $postVars['email'];
        $obUser->password = password_hash($postVars['password'], PASSWORD_DEFAULT);
        $obUser->registrar();

        return [
            'id'     => (int)$obUser->id,
            'nombre' => $obUser->nombre,
            'email'  => $obUser->email
        ];
    }

    public static function setEditUser($request, $id){
        $postVars = $request->getPostVars();
        if(!isset($postVars['nombre']) or !isset($postVars['email']) or !isset($postVars['password'])){
            throw new \Exception("Los campos 'nombre', 'email' y 'contraseña' son obligatorios", 400);   
        }
        
        $obUser = EntityUser::getUserById($id);
        
        if(!$obUser instanceof EntityUser){
            throw new \Exception("El usuario ".$id." no fue encontrada", 404);
        }

        $obUserEmail = EntityUser::getUserByEmail($postVars['email']);
        if($obUserEmail instanceof EntityUser && $obUserEmail->id != $obUser->id){
            throw new \Exception("El email '".$postVars['email']."' ya esta registrado.", 400);
        }
        
        $obUser->nombre   = $postVars['nombre'];
        $obUser->email    = $postVars['email'];
        $obUser->password = password_hash($postVars['password'], PASSWORD_DEFAULT);
        $obUser->actualizar();

        return [
            'id'     => (int)$obUser->id,
            'nombre' => $obUser->nombre,
            'email'  => $obUser->email
        ];
    }

    public static function setDeleteUser($request, $id){
        $obUser = EntityUser::getUserById($id);
        
        if(!$obUser instanceof EntityUser){
            throw new \Exception("El usuario ".$id." no fue encontrada", 404);
        }

        if($obUser->id == $request->user->id){
            throw new \Exception("No es posible eliminar el usuario actualmente conectado", 400);
        }

        $obUser->eliminar();

        return [
            'Suceso' => true
        ];
    }
}