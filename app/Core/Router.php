<?php

namespace App\Core;

class Router
{
    private array $routes = [];
    private array $middlewareGroups = [];

    public function get(string $path, array $action): self
    {
        return $this->addRoute('GET', $path, $action);
    }

    public function post(string $path, array $action): self
    {
        return $this->addRoute('POST', $path, $action);
    }

    public function put(string $path, array $action): self
    {
        return $this->addRoute('PUT', $path, $action);
    }

    public function delete(string $path, array $action): self
    {
        return $this->addRoute('DELETE', $path, $action);
    }

    public function middleware(array $middlewares): self
    {
        $this->middlewareGroups = $middlewares;
        return $this;
    }

    private function addRoute(string $method, string $path, array $action): self
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'action' => $action,
            'middleware' => $this->middlewareGroups,
        ];
        $this->middlewareGroups = [];
        return $this;
    }

    public function dispatch(Request $request): Response
    {
        $method = $request->method();
        $uri = $request->uri();

        foreach ($this->routes as $route) {
            $params = $this->matchRoute($route['path'], $uri);

            if ($params !== false && $route['method'] === $method) {
                // Exécuter les middlewares
                foreach ($route['middleware'] as $middlewareClass) {
                    $middleware = new $middlewareClass();
                    $result = $middleware->handle($request);
                    if ($result instanceof Response) {
                        return $result;
                    }
                }

                // Exécuter l'action
                return $this->callAction($route['action'], $params);
            }
        }

        return Response::html(
            View::make('errors.404', [], 404),
            404
        );
    }

    private function matchRoute(string $pattern, string $uri): false|array
    {
        // Convert pattern: /c/{slug}/app => /c/([^/]+)/app
        $regex = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $pattern);
        $regex = '#^' . $regex . '$#';

        if (preg_match($regex, $uri, $matches)) {
            return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
        }

        return false;
    }

    private function callAction(array $action, array $params): Response
    {
        [$controllerClass, $methodName] = $action;
        $controller = new $controllerClass();

        return $controller->$methodName(...array_values($params));
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}
