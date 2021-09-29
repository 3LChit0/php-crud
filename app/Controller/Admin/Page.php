<?php

namespace App\Controller\Admin;

use App\Utils\View;

class Page{

    private static $modules = [
        'home' =>[
            'label' => 'Home',
            'link' => URL.'/admin'
        ],
        'testimonies' =>[
            'label' => 'Declaraciones',
            'link' => URL.'/admin/testimonies'
        ],
        'users' =>[
            'label' => 'Usuarios',
            'link' => URL.'/admin/users'
        ]
    ];

    public static function getPage($title, $content){
        return view::render('admin/page',[
            'title' => $title,
            'content' => $content
        ]);
    }

    public static function getMenu($currentModule){
        $links = '';
        foreach(self::$modules as $hash=>$module){
            $links .= View::render('admin//menu/link',[
                'label' => $module['label'],
                'link' => $module['link'],
                'current' => $hash == $currentModule ? 'text-danger' : ''
            ]);
        }


        return View::render('admin/menu/box',[
            'links' => $links
        ]);
    }

    public static function getPanel($title, $content, $currentModule){
        $contentPanel = View::render('admin/panel', [
            'menu' => self::getMenu($currentModule),
            'content' => $content
        ]);

        return self::getPage($title, $contentPanel);
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
            
            $links .= View::render('admin/pagination/link', [
                'page' => $page['page'],
                'link' => $link,
                'active' => $page['current'] ? 'active' : ''
            ]);
        }

        //RENDERIZA LA PAGINACION BOX
        return View::render('admin/pagination/box', [
            'links' => $links
        ]);
    }
}