<?php

// Cargar el autoloader de Composer
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

// Cargar variables del archivo .env
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// Incluir configuración
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';

// Incluir las clases principales
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'BaseDatos.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Pages.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Router.php';

// Aquí puedes agregar más configuración inicial si es necesaria
