<?php

namespace App\Controller\Admin;

use \App\Utils\View;

class Alert{

    public static function getError($message){
        return View::render('admin/alert/status',[
            'tipo' => 'danger',
            'message' => $message
        ]);
    }

    public static function getSuccess($message){
        return View::render('admin/alert/status',[
            'tipo' => 'success',
            'message' => $message
        ]);
    }

}