<?php

require __DIR__.'/vendor/autoload.php';

use \App\Http\Router;
use App\Utils\View;

define('URL', 'http://localhost:8070/mvc');

// Define o valor padrão da variaveis
View::init([
    'URL' => URL
]);

// inicia o router
$obRouter = new Router(URL);

// Inclui as rotas de pagina
include __DIR__.'/routes/pages.php';

// Imprime o response da pagina
$obRouter->run()->sendResponse();
