<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\Testimony as EntityTestimony;
use \WilliamCosta\DatabaseManager\Pagination;

class Testimony extends Page{

    private static function getTestimonyItems($request, &$obPagination){
        //DECLARACIONES
        $items = '';

        //CANTIDAD TOTAL DE REGISTROS
        $cantidadTotal = EntityTestimony::getTestimonies(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

        //PAGINA ACTUAL
        $queryParams = $request->getQueryParams();
        $paginaActual = $queryParams['page'] ?? 1;

        //INSTANCIA DE PAGINA
        $obPagination = new Pagination($cantidadTotal, $paginaActual, 2);

        //RESULTADO DE PAGINA
        $results = EntityTestimony::getTestimonies(null, 'id DESC', $obPagination->getLimit());

        while($obTestimony = $results->fetchObject(EntityTestimony::class)){
            $items .= View::render('admin/modules/testimonies/item', [
                'id' => $obTestimony->id,
                'nombre' => $obTestimony->nombre,
                'mensaje' => $obTestimony->mensaje,
                'data' => date('d/m/Y H:i:s', strtotime($obTestimony->data))
            ]);
        }

        return $items;
    }


    public static function getTestimonies($request){

        $content = View::render('admin/modules/testimonies/index', [
            'items'      => self::getTestimonyItems($request, $obPagination),
            'pagination' => parent::getPagination($request, $obPagination),
            'status'     => self::getStatus($request)
        ]);
        return parent::getPanel('Declaraciones > MINSAL', $content,'testimonies');
    }

    public static function getNewTestimony($request){
        $content = View::render('admin/modules/testimonies/form', [
            'title'   => 'Registrar Declaracion',
            'nombre'  => '',
            'mensaje' => '',
            'status'  => ''
        ]);
        return parent::getPanel('Registrar Declaracion > MINSAL', $content,'testimonies');
    }

    public static function setNewTestimony($request){
        $postVars = $request->getPostVars();
        
        $obTestimony = new EntityTestimony;
        $obTestimony->nombre = $postVars['nombre'] ?? '';
        $obTestimony->mensaje = $postVars['mensaje'] ?? '';
        $obTestimony->registrar();

        $request->getRouter()->redirect('/admin/testimonies/'.$obTestimony->id.'/edit?status=created');
    }

    private static function getStatus($request){
        $queryParams = $request->getQueryParams();

        if(!isset($queryParams['status'])) return '';

        switch($queryParams['status']){
            case 'created':
                return Alert::getSuccess('Declaración creado correctamente');
                break;
            case 'updated':
                return Alert::getSuccess('Declaración actualizado correctamente');
                break;
            case 'deleted':
                return Alert::getSuccess('Declaración eliminado correctamente');
                break;    
        }
    }

    public static function getEditTestimony($request, $id){
        $obTestimony = EntityTestimony::getTestimonyById($id);

        if(!$obTestimony instanceof EntityTestimony){
            $request->getRouter()->redirect('/admin/testimonies');
        }

        $content = View::render('admin/modules/testimonies/form', [
            'title'   => 'Editar Declaracion',
            'nombre'  => $obTestimony->nombre,
            'mensaje' => $obTestimony->mensaje,
            'status'  => self::getStatus($request)
        ]);
        return parent::getPanel('Editar Declaracion > MINSAL', $content,'testimonies');
    }
    
    public static function setEditTestimony($request, $id){
        $obTestimony = EntityTestimony::getTestimonyById($id);

        if(!$obTestimony instanceof EntityTestimony){
            $request->getRouter()->redirect('/admin/testimonies');
        }

        $postVars = $request->getPostVars();

        $obTestimony->nombre = $postVars['nombre'] ?? $obTestimony->nombre;
        $obTestimony->mensaje = $postVars['mensaje'] ?? $obTestimony->mensaje;
        $obTestimony->actualizar();

        $request->getRouter()->redirect('/admin/testimonies/'.$obTestimony->id.'/edit?status=updated');
    }

    public static function getDeleteTestimony($request, $id){
        $obTestimony = EntityTestimony::getTestimonyById($id);

        if(!$obTestimony instanceof EntityTestimony){
            $request->getRouter()->redirect('/admin/testimonies');
        }

        $content = View::render('admin/modules/testimonies/delete', [
            'nombre'  => $obTestimony->nombre,
            'mensaje' => $obTestimony->mensaje
        ]);
        return parent::getPanel('Eliminar Declaracion > MINSAL', $content,'testimonies');
    }

    public static function setDeleteTestimony($request, $id){
        $obTestimony = EntityTestimony::getTestimonyById($id);

        if(!$obTestimony instanceof EntityTestimony){
            $request->getRouter()->redirect('/admin/testimonies');
        }

        $obTestimony->eliminar();

        $request->getRouter()->redirect('/admin/testimonies?status=deleted');
    }
}