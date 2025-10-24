<?php

namespace Core;

class Router
{
    private array $routes = ['GET' => [], 'POST' => []];

    public function get(string $path, callable $handler): void
    {
        $this->routes['GET'][$this->normalise($path)] = $handler;
    }

    public function post(string $path, callable $handler): void
    {
        $this->routes['POST'][$this->normalise($path)] = $handler;
    }

    public function dispatch(string $path, string $method): void
    {
        $path = $this->normalise($path);
        $method = strtoupper($method);
        $handler = $this->routes[$method][$path] ?? null;

        if (!$handler) {
            http_response_code(404);
            echo '404 Not Found';
            return;
        }

        echo call_user_func($handler);
    }

    private function normalise(string $path): string
    {
        return rtrim($path, '/') ?: '/';
    }
}
