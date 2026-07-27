<?php

class Router{

    private array $routes = [];

    private string $action;

    public function __construct(){

        $action = $_GET['action'] ?? '';

        $this->action = is_string($action) ? $action : '';
    }

    public function post(string $action, callable $handler): void{

        $this->routes['POST'][$action] = $handler;
    }

    public function getAction(): string{

        return $this->action;
    }

    public function dispatch(string $redirectTo): void{

        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        $handler = $this->routes[$method][$this->action] ?? null;

        if(!$handler){

            self::redirect($redirectTo);
        }

        $handler();
    }

    public static function redirect(string $url): void{

        header("Location: {$url}");

        exit;
    }
}

?>
