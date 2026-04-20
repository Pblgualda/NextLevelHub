<?php
namespace NextLevelHub\Controllers;

class ErrorController
{
    public static function show_error404(): string
    {
        return '<p>404 - Página no encontrada</p>';
    }
}
