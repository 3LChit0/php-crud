<?php 

namespace App\Controller\Pages;

use \App\Utils\View;

class Page{

    /**
     * Metodo responsable por renderizar el header de la pagina
     * @return string
     */
    private static function getHeader(){
        return View::render('pages/header');
    }

    /**
     * Metodo responsable por renderizar el footer de la pagina
     * @return string
     */
    private static function getFooter(){
        return View::render('pages/footer');
    }

    public static function getPagination($request, $obPagination){
        $pages = $obPagination->getPages();
        
        if(count($pages) <= 1) return '';

        $links = '';

        $url = $request->getRouter()->getCurrentUrl();
        
        $queryParams = $request->getQueryParams();

        //RENDERIZA LOS LINKS
        foreach($pages as $page){
            $queryParams['page'] = $page['page'];

            $link = $url.'?'.http_build_query($queryParams);
            
            $links .= View::render('pages/pagination/link', [
                'page' => $page['page'],
                'link' => $link,
                'active' => $page['current'] ? 'active' : ''
            ]);
        }

        //RENDERIZA LA PAGINACION BOX
        return View::render('pages/pagination/box', [
            'links' => $links
        ]);
    }
    
    /**
     * Metodo responsable a retornar al contenido(vista) de nuestra pagina generica
     * @return string
     */
    public static function getPage($title, $content){
        return View::render('pages/page', [
            'title' => $title,
            'header' => self::getHeader(),
            'content' => $content,
            'footer' => self::getFooter()
        ]);
    }

}


?>