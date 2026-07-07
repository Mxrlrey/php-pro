<?php

namespace app\routing;

use app\http\Request;
use Exception;
use ReflectionNamedType;
use ReflectionMethod;

class Router
{
    private array $routes = [];
    private Request $request;

    public function __construct()
    {
        $this->request = new Request();
    }

    public function get(string $uri, array $action): void
    {
        $this->add('GET', $uri, $action);
    }

    public function post(string $uri, array $action): void
    {
        $this->add('POST', $uri, $action);
    }

    private function add(string $method, string $uri, array $action): void
    {
        $this->routes[$method][] = [
            'uri' => $uri,
            'action' => $action,
            'regex' => $this->routeRegex($uri),
            'dynamic' => str_contains($uri, '{'),
        ];
    }

    public function dispatch(string $requestMethod, string $requestUri): array
    {
        $requestMethod = strtoupper($requestMethod);
        $uri = parse_url($requestUri, PHP_URL_PATH);

        if ($route = $this->findRoute($requestMethod, $uri)) {
            return $this->runAction($route['action'], $route['params']);
        }

        if ($this->uriExistsForAnotherMethod($requestMethod, $uri)) {
            throw new Exception('Metodo nao permitido para esta rota.');
        }

        throw new Exception('Rota nao encontrada.');
    }

    private function findRoute(string $method, string $uri): ?array
    {
        $routes = $this->routes[$method] ?? [];

        foreach ($routes as $route) {
            if (!$route['dynamic'] && $uri === $route['uri']) {
                return $route + ['params' => []];
            }
        }

        foreach ($routes as $route) {
            if (!$route['dynamic']) {
                continue;
            }

            if (preg_match($route['regex'], ltrim($uri, '/'), $matches) !== 1) {
                continue;
            }

            return $route + [
                'params' => array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY),
            ];
        }

        return null;
    }

    private function uriExistsForAnotherMethod(string $requestMethod, string $uri): bool
    {
        foreach ($this->routes as $method => $routes) {
            if ($method === $requestMethod) {
                continue;
            }

            if ($this->findRoute($method, $uri)) {
                return true;
            }
        }

        return false;
    }

    private function routeRegex(string $routeUri): string
    {
        $segments = explode('/', trim($routeUri, '/'));

        $regexSegments = array_map(function (string $segment) {
            if (preg_match('/^\{([a-zA-Z_][a-zA-Z0-9_]*)\}$/', $segment, $matches)) {
                return "(?P<{$matches[1]}>[^/]+)";
            }

            return preg_quote($segment, '#');
        }, $segments);

        return '#^'.implode('/', $regexSegments).'$#';
    }

    private function runAction(array $action, array $params): array
    {
        [$controller, $method] = $action;

        if (!class_exists($controller)) {
            throw new Exception("Controller {$controller} nao existe.");
        }

        $controllerInstance = new $controller();

        if (!method_exists($controllerInstance, $method)) {
            throw new Exception("Metodo {$method} nao existe no controller {$controller}.");
        }

        return $controllerInstance->$method(...$this->methodArguments($controllerInstance, $method, $params));
    }

    private function methodArguments(object $controllerInstance, string $method, array $params): array
    {
        $reflection = new ReflectionMethod($controllerInstance, $method);
        $arguments = [];

        foreach ($reflection->getParameters() as $parameter) {
            $type = $parameter->getType();
            $typeName = $type instanceof ReflectionNamedType ? $type->getName() : null;

            if ($typeName === Request::class && !$type->isBuiltin()) {
                $arguments[] = $this->request;
                continue;
            }

            if ($parameter->getName() === 'params') {
                $arguments[] = $params;
                continue;
            }

            if (array_key_exists($parameter->getName(), $params)) {
                $arguments[] = $this->castParam($params[$parameter->getName()], $typeName);
                continue;
            }

            if ($parameter->isDefaultValueAvailable()) {
                $arguments[] = $parameter->getDefaultValue();
                continue;
            }

            throw new Exception("Parametro {$parameter->getName()} nao encontrado para o metodo {$method}.");
        }

        return $arguments;
    }

    private function castParam(mixed $value, ?string $type): mixed
    {
        return match ($type) {
            'int' => (int) $value,
            'float' => (float) $value,
            'bool' => filter_var($value, FILTER_VALIDATE_BOOL),
            'string', null => (string) $value,
            default => $value,
        };
    }
}
