<?php 

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Paciente;

class About extends Page{
    
    /**
     * Metodo responsable a retornar al contenido(vista) de nuestro pagina Sobre
     * @return string
     */
    public static function getAbout(){
        $obPaciente = new Paciente();

        //VISTA DE HOME
        $content = View::render('pages/about', [
            'name' => $obPaciente->nombre,
            'adress' => $obPaciente->direccion
        ]);

        //RETORMA LA VISTA DE LA PAGINA
        return parent::getPage('CHITO - DEV', $content);
    }

}


?>