<?php
namespace Harmony\Resources;


class Router {
    private Array $routes;
    private $notImplemented;
    private $methodNotAllowed;
    private $notFound;

    public function __construct() {
        $this->routes = [
            'get' => [],
            'post' => [],
            'delete' => [],
            'put' => [],
            'patch' => []
        ];

        $this->notImplemented = '';
        $this->methodNotAllowed = '';
        $this->notFound = '';
    }

    public function setPageNotImplementedHandler($func) {
        $this->notImplemented = $func;
    }

    public function setPageMethodNotAllowedHandler($func) {
        $this->methodNotAllowed = $func;
    }

    public function setPageNotFoundHandler($func) {
        $this->notFound = $func;
    }

    public function get($expr, $call)
    {
        $this->routes['get'][] = [
            'expression' => $expr,
            'call' => $call
        ];
    }

    public function post($expr, $call)
    {
        $this->routes['post'][] = [
            'expression' => $expr,
            'call' => $call
        ];
    }

    public function delete($expr, $call)
    {
        $this->routes['delete'][] = [
            'expression' => $expr,
            'call' => $call
        ];
    }

    public function put($expr, $call)
    {
        $this->routes['put'][] = [
            'expression' => $expr,
            'call' => $call
        ];
    }

    public function patch($expr, $call)
    {
        $this->routes['patch'][] = [
            'expression' => $expr,
            'call' => $call
        ];
    }

    private function prepare($urlPath)
    {
        $urlPath = str_replace("{i}", "([0-9]*)", $urlPath);
        $urlPath = '#^'.$urlPath.'$#';
        return $urlPath;
    }

    private function execute(&$call, $vars=null)
    {
        if($call instanceof \Closure) {
            print_r($call());
            return;
        }

        $controller_name = $controller_method = null;
        $controller_call = array_filter(explode('|', $call));
        if($controller_call) {
            $controller_name = "\\App\\Controllers\\" . $controller_call[0];
            $controller_method = $controller_call[1];
        }

        if (class_exists($controller_name) && method_exists($controller_name, $controller_method)) {
            $controller = new $controller_name();
            call_user_func([$controller, $controller_method], $vars);
            return;
        } else {
            header($_SERVER["SERVER_PROTOCOL"]." 501 Not Implemented", true, 501);
            if($this->notImplemented instanceof \Closure) {
                print_r(call_user_func($this->notImplemented));
                return;
            } else {
                if(empty($this->notImplemented) || $this->execute($this->notImplemented)) {
                    print_r("501 NOT IMPLEMENTED");
                    return;
                }
            }
        }

        return false;
    }

    public function dispatch()
    {
        $env = new Environment();
        $context = $env->get("context");
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        $parsed_url = parse_url($_SERVER['REQUEST_URI']);
        $path = str_replace($context, '', rtrim($parsed_url['path'], '/'));
        if(!isset($path) || empty($path))
            $path = '/';

        foreach($this->routes[$method] as $route)
        {
            $preparedRoute = $this->prepare($route['expression']);
            if(preg_match($preparedRoute, $path, $matches)){
                $this->execute($route['call'], $matches);
                return;
            }
        }

        unset($this->routes[$method]);
        foreach($this->routes as $method)
        {
            foreach($method as $route)
            {
                $preparedRoute = $this->prepare($route['expression']);
                if(preg_match($preparedRoute, $path, $matches)){
                    $this->execute($this->methodNotAllowed);
                    return;
                }
            }
        }

        $this->execute($this->notFound);
        return;

    }
}