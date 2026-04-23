<?php

use NextLevelHub\Controllers\ContactoController;
use NextLevelHub\Controllers\DashboardController;
use NextLevelHub\Controllers\AuthController;
use NextLevelHub\Core\Router;

// Ruta principal
Router::add('GET', '/', static function() {
    (new DashboardController())->index();
});

// Rutas de Autenticación
// Mostrar formulario de registro
Router::add('GET', '/auth/register', static function() {
    (new AuthController())->register();
});

// Procesar el registro de nuevo usuario
Router::add('POST', '/auth/save', static function() {
    (new AuthController())->save();
});

// Ruta para listar contactos
Router::add('GET', '/Contacto/listar', static function() {
    (new ProductoController())->listar();
});

// Ruta para nuevo contacto
// Router::add('GET', '/Contacto/nuevoContacto', static function() {
//     (new ContactoController())->nuevoContacto();
// });
Router::dispatch();
