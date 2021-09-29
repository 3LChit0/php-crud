<?php

    require __DIR__.'/includes/app.php';

    use \App\Http\Router;

    $obRouter = new Router(URL);

    //Incluye las rutas de paginas
    include __DIR__.'/routes/pages.php';

    include __DIR__.'/routes/admin.php';
    
    include __DIR__.'/routes/api.php';

    //Imprime response de la ruta 
    $obRouter->run()->sendResponse();