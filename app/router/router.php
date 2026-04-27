<?php

// Carrega o as rotas do sistema
function routes()
{
    return require 'routes.php';
}

function exactMatchUriInArrayRoutes($uri, $routes)
{
    // Verifica se a URI atual existe no array de rotas
    if (array_key_exists($uri, $routes)) {
        return [];
    }

    return [];
}

function router()
{
    // $_SERVER['REQUEST_URI'] contém a URL completa acessada (incluindo query string)
    // parse_url com PHP_URL_PATH extrai apenas o caminho da URL (sem parâmetros ?)
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    $routes = routes();

    $matchedUri = exactMatchUriInArrayRoutes($uri, $routes);
}