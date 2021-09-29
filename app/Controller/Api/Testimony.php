<?php

namespace App\Controller\Api;

use \App\Model\Entity\Testimony as EntityTestimony;
use \WilliamCosta\DatabaseManager\Pagination;

class Testimony extends Api{

    private static function getTestimonyItems($request, &$obPagination){
        //DECLARACIONES
        $items = [];

        //CANTIDAD TOTAL DE REGISTROS
        $cantidadTotal = EntityTestimony::getTestimonies(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

        //PAGINA ACTUAL
        $queryParams = $request->getQueryParams();
        $paginaActual = $queryParams['page'] ?? 1;

        //INSTANCIA DE PAGINA
        $obPagination = new Pagination($cantidadTotal, $paginaActual, 3);

        //RESULTADO DE PAGINA
        $results = EntityTestimony::getTestimonies(null, 'id DESC', $obPagination->getLimit());

        while($obTestimony = $results->fetchObject(EntityTestimony::class)){
            $items[] = [
                'id' => (int)$obTestimony->id,
                'nombre' => $obTestimony->nombre,
                'mensaje' => $obTestimony->mensaje,
                'data' => $obTestimony->data
            ];
        }

        return $items;
    }


    public static function getTestimonies($request){
        return [
            'declaraciones' => self::getTestimonyItems($request, $obPagination),
            'paginacion' => parent::getPagination($request, $obPagination)
        ];
    }

    public static function getTestimony($request, $id){
        if(!is_numeric($id)){
            throw new \Exception("El id '".$id."' no es valido", 400);
        }

        $obTestimony = EntityTestimony::getTestimonyById($id);
        
        if(!$obTestimony instanceof EntityTestimony){
            throw new \Exception("La Declaración ".$id." no fue encontrada", 404);
        }

        //RETORNA LOS DETALLES DE LA DECLARACION
        return [
            'id'      => (int)$obTestimony->id,
            'nombre'  => $obTestimony->nombre,
            'mensaje' => $obTestimony->mensaje,
            'data'    => $obTestimony->data
        ];
    }

    public static function setNewTestimony($request){
        $postVars = $request->getPostVars();
        if(!isset($postVars['nombre']) or !isset($postVars['mensaje'])){
            throw new \Exception("Los campos 'nombre' y 'mensaje' son obligatorios", 400);   
        }

        $obTestimony = new EntityTestimony;
        $obTestimony->nombre = $postVars['nombre'];
        $obTestimony->mensaje = $postVars['mensaje'];
        $obTestimony->registrar();

        return [
            'id'      => (int)$obTestimony->id,
            'nombre'  => $obTestimony->nombre,
            'mensaje' => $obTestimony->mensaje,
            'data'    => $obTestimony->data
        ];
    }

    public static function setEditTestimony($request, $id){
        $postVars = $request->getPostVars();
        if(!isset($postVars['nombre']) or !isset($postVars['mensaje'])){
            throw new \Exception("Los campos 'nombre' y 'mensaje' son obligatorios", 400);   
        }

        $obTestimony = EntityTestimony::getTestimonyById($id);
        if(!$obTestimony instanceof EntityTestimony){
            throw new \Exception("La Declaración ".$id." no fue encontrada", 404);
        }

        $obTestimony->nombre = $postVars['nombre'];
        $obTestimony->mensaje = $postVars['mensaje'];
        $obTestimony->actualizar();

        return [
            'id'      => (int)$obTestimony->id,
            'nombre'  => $obTestimony->nombre,
            'mensaje' => $obTestimony->mensaje,
            'data'    => $obTestimony->data
        ];
    }

    public static function setDeleteTestimony($request, $id){
        $obTestimony = EntityTestimony::getTestimonyById($id);
        if(!$obTestimony instanceof EntityTestimony){
            throw new \Exception("La Declaración ".$id." no fue encontrada", 404);
        }

        $obTestimony->eliminar();

        return [
            'Suceso' => true
        ];
    }
}