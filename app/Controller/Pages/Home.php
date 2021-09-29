<?php 

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Paciente;

class Home extends Page{
    
    /**
     * Metodo responsable a retornar al contenido(vista) de nuestro home 
     * @return string
     */
    public static function getHome(){
        $obPaciente = new Paciente();

        //VISTA DE HOME
        $content = View::render('pages/home', [
            'name' => $obPaciente->nombre,
        ]);

        //RETORMA LA VISTA DE LA PAGINA
        return parent::getPage('Laboratorio Minsal', $content);
    }

}


?>