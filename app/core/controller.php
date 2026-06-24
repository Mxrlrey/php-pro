<?php

function controller($matchedURI, $params)
{
    [$controller, $method] = explode('@', array_values($matchedURI)[0]);
    $controllerWithNamespace = CONTROLLER_PATH.$controller;

    if (!class_exists($controllerWithNamespace)) {
        throw new Exception("Controller {$controller} não existe");
    }

    $controllerInstance = new $controllerWithNamespace;

    if (!method_exists($controllerInstance, $method)) {
        throw new Exception("O método {$method} não existe no controller {$controller}");
    }

    return $controllerInstance->$method($params);
}