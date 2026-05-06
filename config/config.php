<?php

define('DB_SERVIDOR', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_USUARIO', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');
define('DB_DATABASE', $_ENV['DB_DATABASE'] ?? 'tienda');
define('DB_CHARSET', 'utf8mb4');

const BASE_URL = "http://localhost/NextLevelHub/";

define('GOOGLE_CLIENT_ID', $_ENV['GOOGLE_CLIENT_ID'] ?? '');
define('GOOGLE_CLIENT_SECRET', $_ENV['GOOGLE_CLIENT_SECRET'] ?? '');
define('GOOGLE_REDIRECT_URI', $_ENV['GOOGLE_REDIRECT_URI'] ?? BASE_URL . 'auth/google/callback');