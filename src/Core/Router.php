<?php
namespace NextLevelHub\Core;



use NextLevelHub\Controllers\ErrorController;

class Router {
    private static array $routes = [];

    /** Registra una ruta en el sistema */
    public static function add(string $method, string $action, callable $controller): void {
        $action = trim($action, '/');
        self::$routes[strtoupper($method)][$action] = $controller;
    }
    /**
     * Se encarga de obtener el sufijo de la URL que permitirá seleccionar
     * la ruta y mostrar el resultado de ejecutar la función pasada al metodo add
     * para esa ruta usando call_user_func()
     */
    public static function dispatch(): void {
        //$_SERVER['REQUEST_URI'] almacena la cadena de texto que hay después del nombre del host en la URL
        $method = $_SERVER['REQUEST_METHOD'];

        // Obtenemos la URI limpia de parámetros GET (?id=1) y del subdirectorio
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $basePath = rtrim(parse_url(BASE_URL, PHP_URL_PATH), '/'); // /AGENDA

        if ($basePath !== '' && stripos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }

        // Obtenemos la carpeta y convertimos las barras de Windows (\) a barras de URL (/)
        /*$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));

        // Eliminamos la carpeta base (Ignora mayúsculas/minúsculas)
        if ($scriptName !== '/' && $scriptName !== '\\') {
            // Usamos preg_replace con 'i' para que no le importen las mayúsculas (Case-Insensitive)
            // El '^' asegura que solo borre el nombre de la carpeta si está al principio de la URL
            $uri = preg_replace('#^' . preg_quote($scriptName, '#') . '#i', '', $uri);
        }*/



        $uri = trim($uri, '/');


        $params = [];
        $callback = null;// se parte de que no hay acción a ejecutar

        // Buscamos coincidencia exacta o con parámetros en el array con las rutas registradas
        foreach (self::$routes[$method] ?? [] as $action => $controller) {
            // Reemplazamos, en la ruta, lo que empiece por : seguido de letras o número(ej: 'user/:id')
            // por una expresión regular, así localiza user/seguido de cualquier cosa que no es barra inclinada
            $pattern = preg_replace('#:([\w]+)#', '([^/]+)', $action);

            if (preg_match('#^' . $pattern . '$#i', $uri, $matches)) {
                array_shift($matches); // Quitamos la coincidencia completa deja solo los parámetros
                $params = $matches;
                $callback = $controller;//tenemos la ruta
                break;
            }
        }

        if (!$callback) {//la ruta no está registrada
            http_response_code(404);
            echo ErrorController::show_error404();
            return;
        }

        // Ejecutamos el controlador pasando los parámetros detectados
        echo call_user_func_array($callback, $params);
    }

}
