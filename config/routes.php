<?php

use NextLevelHub\Controllers\ContactoController;
use NextLevelHub\Controllers\DashboardController;
use NextLevelHub\Controllers\AuthController;
use NextLevelHub\Controllers\CategoriaController;
use NextLevelHub\Controllers\ProductoController;
use NextLevelHub\Controllers\UsuarioController;
use NextLevelHub\Controllers\CarritoController;
use NextLevelHub\Controllers\PedidoController;
use NextLevelHub\Core\AdminMiddleware;
use NextLevelHub\Core\Router;

// Ruta principal
Router::add('GET', '/', static function() {
    (new DashboardController())->index();
});

// Rutas de Autenticación
Router::add('GET', '/auth/register', static function() {
    (new AuthController())->register();
});

Router::add('GET', '/auth/login', static function() {
    (new AuthController())->login();
});

Router::add('GET', '/auth/google', static function() {
    (new AuthController())->googleLogin();
});

Router::add('GET', '/auth/google/callback', static function() {
    (new AuthController())->googleCallback();
});

Router::add('POST', '/auth/login', static function() {
    (new AuthController())->authenticate();
});

Router::add('GET', '/auth/logout', static function() {
    (new AuthController())->logout();
});

Router::add('GET', '/auth/profile', static function() {
    (new AuthController())->profile();
});

Router::add('POST', '/auth/save', static function() {
    (new AuthController())->save();
});

// Rutas de Categorías
Router::add('GET', '/categoria/listar', static function() {
    AdminMiddleware::handle();
    (new CategoriaController())->listar();
});

Router::add('GET', '/categoria/form', static function() {
    AdminMiddleware::handle();
    (new CategoriaController())->form();
});

Router::add('GET', '/categoria/form/:id', static function($id) {
    AdminMiddleware::handle();
    (new CategoriaController())->form((int)$id);
});

Router::add('POST', '/categoria/form', static function() {
    AdminMiddleware::handle();
    (new CategoriaController())->form();
});

Router::add('POST', '/categoria/form/:id', static function($id) {
    AdminMiddleware::handle();
    (new CategoriaController())->form((int)$id);
});

Router::add('GET', '/categoria/eliminar/:id', static function($id) {
    AdminMiddleware::handle();
    (new CategoriaController())->eliminar((int)$id);
});

// Rutas de Productos
Router::add('GET', '/producto/gestion', static function() {
    AdminMiddleware::handle();
    (new ProductoController())->gestion();
});

Router::add('GET', '/producto/listar', static function() {
    AdminMiddleware::handle();
    (new ProductoController())->gestion();
});

Router::add('GET', '/producto/form', static function() {
    AdminMiddleware::handle();
    (new ProductoController())->form();
});

Router::add('GET', '/producto/form/:id', static function($id) {
    AdminMiddleware::handle();
    (new ProductoController())->form((int)$id);
});

Router::add('POST', '/producto/form', static function() {
    AdminMiddleware::handle();
    (new ProductoController())->form();
});

Router::add('POST', '/producto/form/:id', static function($id) {
    AdminMiddleware::handle();
    (new ProductoController())->form((int)$id);
});

Router::add('GET', '/producto/eliminar/:id', static function($id) {
    AdminMiddleware::handle();
    (new ProductoController())->eliminar((int)$id);
});

Router::add('GET', '/categoria/productos/:id', static function($id) {
    (new ProductoController())->productosPorCategoria((int)$id);
});

Router::add('GET', '/carrito', static function() {
    (new CarritoController())->index();
});

Router::add('POST', '/carrito/add', static function() {
    (new CarritoController())->add();
});

Router::add('GET', '/carrito/increment/:id', static function($id) {
    (new CarritoController())->increment((int)$id);
});

Router::add('GET', '/carrito/decrement/:id', static function($id) {
    (new CarritoController())->decrement((int)$id);
});

Router::add('GET', '/carrito/remove/:id', static function($id) {
    (new CarritoController())->remove((int)$id);
});

Router::add('GET', '/carrito/vaciar', static function() {
    (new CarritoController())->vaciar();
});

Router::add('GET', '/carrito/confirm', static function() {
    (new CarritoController())->confirm();
});

Router::add('GET', '/producto/nuevo', static function() {
    AdminMiddleware::handle();
    (new ProductoController())->form();
});

Router::add('POST', '/producto/nuevo', static function() {
    AdminMiddleware::handle();
    (new ProductoController())->form();
});

Router::add('GET', '/usuario/lista', static function() {
    AdminMiddleware::handle();
    (new UsuarioController())->listar();
});

Router::add('GET', '/usuario/nuevo', static function() {
    AdminMiddleware::handle();
    (new UsuarioController())->formUsuario();
});

Router::add('GET', '/usuario/form/:id', static function($id) {
    AdminMiddleware::handle();
    (new UsuarioController())->formUsuario((int)$id);
});

Router::add('POST', '/usuario/nuevo', static function() {
    AdminMiddleware::handle();
    (new UsuarioController())->formUsuario();
});

Router::add('POST', '/usuario/form/:id', static function($id) {
    AdminMiddleware::handle();
    (new UsuarioController())->formUsuario((int)$id);
});

Router::add('GET', '/usuario/eliminar/:id', static function($id) {
    AdminMiddleware::handle();
    (new UsuarioController())->eliminar((int)$id);
});

Router::add('GET', '/pedido/nuevo', static function() {
    (new PedidoController())->nuevoPedido();
});

Router::add('POST', '/pedido/nuevo', static function() {
    (new PedidoController())->nuevoPedido();
});

Router::add('GET', '/pedido/mis-pedidos', static function() {
    (new PedidoController())->misPedidos();
});

Router::add('GET', '/pedido/ver/:id', static function($id) {
    (new PedidoController())->verPedido((int)$id);
});

// Ruta para nuevo contacto
// Router::add('GET', '/Contacto/nuevoContacto', static function() {
//     (new ContactoController())->nuevoContacto();
// });
Router::dispatch();
