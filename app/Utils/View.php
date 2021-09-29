<?php 

namespace App\Utils;

class View{

    private static $vars = [];

    public static function init($vars = []){
        self::$vars = $vars;
    }

    /**
     * Metodo responsable de retornar una vista
     * @param string $view
     * @return string
     */
    private static function getContentView($view){
        $file = __DIR__.'/../../resources/view/'.$view.'.html';
        return file_exists($file) ? file_get_contents($file) : '';
    }

    /**
     * Metodo responsable de retornar renderizado de una vista
     * @param string $view
     * @param array $vars (string/numeric)
     * @return string
     */
    public static function render($view, $vars = []){
        //Continua a la vista
        $contentView = self::getContentView($view);

        $vars = array_merge(self::$vars,$vars);

        //LLAVES DE MATRIZ DE VARIABLES
        $keys = array_keys($vars);
        $keys = array_map(function($item){
            return '{{'.$item.'}}';
        },$keys);

        //Retorna o continua rederizado
        return str_replace($keys, array_values($vars),$contentView);
    }

}


?>