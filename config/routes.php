<?php

use NextLevelHub\Controllers\ContactoController;
use NextLevelHub\Controllers\DashboardController;
use NextLevelHub\Core\Router;

// Ruta principal
Router::add('GET', '/', static function() {
    (new DashboardController())->index();
});

// Ruta para listar contactos
Router::add('GET', '/Contacto/listar', static function() {
    (new ContactoController())->listar();
});

Router::add('GET', '/Contacto/nuevoContacto', static function() {
    (new ContactoController())->nuevoContacto();
});

Router::add('POST', '/Contacto/nuevoContacto', static function() {
    (new ContactoController())->nuevoContacto();
});

// Ruta para nuevo contacto
// Router::add('GET', '/Contacto/nuevoContacto', static function() {
//     (new ContactoController())->nuevoContacto();
// });
Router::dispatch();
