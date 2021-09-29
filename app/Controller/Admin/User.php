<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\User as EntityUser;
use \WilliamCosta\DatabaseManager\Pagination;

class User extends Page{

    private static function getUserItems($request, &$obPagination){
        //USUARIOS
        $items = '';

        //CANTIDAD TOTAL DE REGISTROS
        $cantidadTotal = EntityUser::getUsers(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

        //PAGINA ACTUAL
        $queryParams = $request->getQueryParams();
        $paginaActual = $queryParams['page'] ?? 1;

        //INSTANCIA DE PAGINA
        $obPagination = new Pagination($cantidadTotal, $paginaActual, 2);

        //RESULTADO DE PAGINA
        $results = EntityUser::getUsers(null, 'id DESC', $obPagination->getLimit());

        while($obUser = $results->fetchObject(EntityUser::class)){
            $items .= View::render('admin/modules/users/item', [
                'id'     => $obUser->id,
                'nombre' => $obUser->nombre,
                'email'  => $obUser->email
            ]);
        }

        return $items;
    }


    public static function getUsers($request){

        $content = View::render('admin/modules/users/index', [
            'items'      => self::getUserItems($request, $obPagination),
            'pagination' => parent::getPagination($request, $obPagination),
            'status'     => self::getStatus($request)
        ]);
        return parent::getPanel('Usuarios > MINSAL', $content,'users');
    }

    public static function getNewUser($request){
        $content = View::render('admin/modules/users/form', [
            'title'   => 'Registrar Usuario',
            'nombre'  => '',
            'email'   => '',
            'status'  => self::getStatus($request)
        ]);
        return parent::getPanel('Registrar Usuario > MINSAL', $content,'users');
    }

    public static function setNewUser($request){
        $postVars   = $request->getPostVars();
        $nombre     = $postVars['nombre'] ?? '';
        $email      = $postVars['email'] ?? '';
        $password = $postVars['contraseña'] ?? '';

        //VALIDA EMAIL DUPLICADO
        $obUser = EntityUser::getUserByEmail($email);
        if($obUser instanceof EntityUser){
            $request->getRouter()->redirect('/admin/users/new?status=duplicated');
        }

        $obUser = new EntityUser;
        $obUser->nombre     = $nombre;
        $obUser->email      = $email;
        $obUser->password = password_hash($password, PASSWORD_DEFAULT);
        $obUser->registrar();

        $request->getRouter()->redirect('/admin/users/'.$obUser->id.'/edit?status=created');
    }

    private static function getStatus($request){
        $queryParams = $request->getQueryParams();

        if(!isset($queryParams['status'])) return '';

        switch($queryParams['status']){
            case 'created':
                return Alert::getSuccess('Usuario creado correctamente');
                break;
            case 'updated':
                return Alert::getSuccess('Usuario actualizado correctamente');
                break;
            case 'deleted':
                return Alert::getSuccess('Usuario eliminado correctamente');
                break;
            case 'duplicated':
                return Alert::getError('El email ya esta registrado como usuario');
                break;    
        }
    }

    public static function getEditUser($request, $id){
        $obUser = EntityUser::getUserById($id);

        if(!$obUser instanceof EntityUser){
            $request->getRouter()->redirect('/admin/users');
        }


        $content = View::render('admin/modules/users/form', [
            'title'   => 'Editar Usuario',
            'nombre'  => $obUser->nombre,
            'email'   => $obUser->email,
            'status'  => self::getStatus($request)
        ]);
        return parent::getPanel('Editar Usuario > MINSAL', $content,'users');
    }
    
    public static function setEditUser($request, $id){
        $obUser = EntityUser::getUserById($id);

        if(!$obUser instanceof EntityUser){
            $request->getRouter()->redirect('/admin/users');
        }

        $postVars = $request->getPostVars();
        $nombre     = $postVars['nombre'] ?? '';
        $email      = $postVars['email'] ?? '';
        $password = $postVars['contraseña'] ?? '';

        $obUserEmail = EntityUser::getUserByEmail($email);
        if($obUserEmail instanceof EntityUser && $obUserEmail->id != $id){
            $request->getRouter()->redirect('/admin/users/'.$id.'/edit?status=duplicated');
        }

        $obUser->nombre = $nombre;
        $obUser->email = $email;
        $obUser->password = password_hash($password, PASSWORD_DEFAULT);
        $obUser->actualizar();

        $request->getRouter()->redirect('/admin/users/'.$obUser->id.'/edit?status=updated');
    }

    public static function getDeleteUser($request, $id){
        $obUser = EntityUser::getUserById($id);

        if(!$obUser instanceof EntityUser){
            $request->getRouter()->redirect('/admin/users');
        }

        $content = View::render('admin/modules/users/delete', [
            'nombre' => $obUser->nombre,
            'email'  => $obUser->email
        ]);
        return parent::getPanel('Eliminar Usuario > MINSAL', $content,'users');
    }

    public static function setDeleteUser($request, $id){
        $obUser = EntityUser::getUserById($id);

        if(!$obUser instanceof EntityUser){
            $request->getRouter()->redirect('/admin/users');
        }

        $obUser->eliminar();

        $request->getRouter()->redirect('/admin/users?status=deleted');
    }
}