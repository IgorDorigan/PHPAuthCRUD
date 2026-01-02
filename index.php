<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/helpers/Auth.php';
define('URL', '/sistema');



use App\core\Env;
use App\http\Router;


Env::load(__DIR__);

// session_start();

// Cria objeto do Router
$obRouter = new Router(URL);


// Inclui rotas
include __DIR__ . '/routes/Pages.php';

// Executa rota e envia resposta
$obRouter->run();
