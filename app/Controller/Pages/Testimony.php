<?php 

namespace App\Controller\Pages;

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
        $obPagination = new Pagination($cantidadTotal, $paginaActual, 3);

        //RESULTADO DE PAGINA
        $results = EntityTestimony::getTestimonies(null, 'id DESC', $obPagination->getLimit());

        while($obTestimony = $results->fetchObject(EntityTestimony::class)){
            $items .= View::render('pages/testimony/item', [
                'nombre' => $obTestimony->nombre,
                'mensaje' => $obTestimony->mensaje,
                'data' => date('d/m/Y H:i:s', strtotime($obTestimony->data))
            ]);
        }

        return $items;
    }
    
    /**
     * Metodo responsable a retornar al contenido(vista) de nuestro de declaraciones 
     * @param Request $request
     * @return string
     */
    public static function getTestimonies($request){

        //VISTA DE DECLARACIONES
        $content = View::render('pages/testimonies', [
             'items' => self::getTestimonyItems($request, $obPagination),
             'pagination' => parent::getPagination($request, $obPagination)
        ]);

        //RETORMA LA VISTA DE LA PAGINA
        return parent::getPage('Declaraciones Minsal', $content);
    }

    //REGISTRA UNA DECLARACION
    public static function insertTestimony($request){
        $postVars = $request->getPostVars();

        $obTestimony = new EntityTestimony;
        $obTestimony->nombre = $postVars['nombre'];
        $obTestimony->mensaje = $postVars['mensaje'];
        $obTestimony->registrar();

        return self::getTestimonies($request);
    }

}


?>