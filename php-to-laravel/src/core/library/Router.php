<?php

namespace core\library;

use DI\Container;
use core\exceptions\ControllerNotFoundException;

class Router
{

    protected array $routes = [];
    protected ?string $controller = null;
    protected string $action;
    protected array $parameters = [];




    public function __construct(
        private Container $container
    ) {

    } // __construct

    public function add(
        string $method,
        string $uri,
        array $route
    ) {
        $this->routes[$method][$uri] = $route;
    } // add

    public function execute()
    {
        foreach ($this->routes as $request => $routes) {
            if ($request === REQUEST_METHOD) {
                return $this->handleUri($routes);
            }
        }
    } // execute()


    protected function handleUri(array $routes)
    {
        foreach ($routes as $uri => $route) {
            // ? Rota sem parametros

            if ($uri === REQUEST_URI) {
                [$this->controller, $this->action] = $route;
                break;

            }

            $pattern = str_replace('/', '\/', trim($uri, '/'));

            // ? Rota com parametros
            if ($uri !== '/' && preg_match("/^$pattern$/", trim(REQUEST_URI, '/'), $this->parameters)) {
                [$this->controller, $this->action] = $route;
                unset($this->parameters[0]);
                break;
            }
        }// foreach



        if ($this->controller) {

            return $this->handleController(
                $this->controller,
                $this->action,
                $this->parameters
            );
        }

        return $this->handleNotFound();

    }// handleUri()


    protected function handleController(
        string $controller,
        string $action,
        array $parameters
    ) {

        //! caso classe não exista (controller)
        if (!class_exists($controller) || !method_exists($controller, $action)) {
            throw new ControllerNotFoundException(
                "[$controller::$action] does not exist"
            );
        }

        $controller = $this->container->get($controller);
        $this->container->call([$controller, $action], [...$parameters]);
    }

    protected function handleNotFound()
    {
        dump('Not found Controller');
    }

} // Router
