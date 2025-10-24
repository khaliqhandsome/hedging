<?php
session_start();

if (!function_exists('str_starts_with')) {
    function str_starts_with(string $haystack, string $needle): bool
    {
        return substr($haystack, 0, strlen($needle)) === $needle;
    }
}

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../app/';
    $core_prefix = 'Core\\';
    $core_dir = __DIR__ . '/../app/Core/';

    if (str_starts_with($class, $prefix)) {
        $relative = substr($class, strlen($prefix));
        $file = $base_dir . str_replace('\\', DIRECTORY_SEPARATOR, $relative) . '.php';
        if (file_exists($file)) {
            require $file;
        }
    } elseif (str_starts_with($class, $core_prefix)) {
        $relative = substr($class, strlen($core_prefix));
        $file = $core_dir . str_replace('\\', DIRECTORY_SEPARATOR, $relative) . '.php';
        if (file_exists($file)) {
            require $file;
        }
    }
});

require __DIR__ . '/../config/app.php';

use Core\Router;

$router = new Router();

require __DIR__ . '/../routes/web.php';

if (php_sapi_name() !== 'cli' || basename($_SERVER['SCRIPT_FILENAME'] ?? '') === 'index.php') {
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $router->dispatch($path, $method);
}
