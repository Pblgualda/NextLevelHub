<?php

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'bootstrap.php';

use Agenda\Core\Router;

// Cargar el archivo de rutas
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'routes.php';

// Ejecutar el router
Router::dispatch();