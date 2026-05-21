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

function regularExpressionMatchArrayRoutes($uri, $routes)
{
    // Filtra o array de rotas, mantendo apenas as rotas cuja chave combina com a URI atual
    return array_filter(
        // Como foi usado ARRAY_FILTER_USE_KEY, essa função recebe a CHAVE da rota
        $routes,
        function ($value) use ($uri) {
            // Remove a barra inicial da rota e escapa as barras restantes
            $regex = str_replace('/', '\/', ltrim($value, '/'));
            // Compara a URI atual com a regex da rota
            // ^ significa começo da string e $ significa fim da string
            return preg_match("/^$regex$/", ltrim($uri, '/'));
        },
        // Faz o array_filter trabalhar com as CHAVES do array, não com os valores
        ARRAY_FILTER_USE_KEY
    );
}

function params($uri, $matchedUri)
{
    // Se encontrou uma URI correspondente
    if (!empty($matchedUri)) {
        // Obtém a cahve da Uri encontrada
        $matchedToGetParams = array_keys($matchedUri) [0];
        // Retorna a diferença entre as partes das URIs,
        // removendo a barra inicial das URIs
        return array_diff(
            $uri,
            explode('/', ltrim($matchedToGetParams, '/'))
        );
    }
    // Retorna um array vazio caso não encontre correspondência
    return [];
}

function paramsFormat($uri, $params)
{
    $paramsData = [];
    // Usa o segmento anterior da URI como chave do parâmetro
    foreach ($params as $index => $param) {
        $paramsData[$uri[$index - 1]] = $param;
    }
    // Retorna os parâmetros formatados
    return $paramsData;
}

function router()
{
    // $_SERVER['REQUEST_URI'] contém a URL completa acessada (incluindo query string)
    // parse_url com PHP_URL_PATH extrai apenas o caminho da URL (sem parâmetros ?)
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    $routes = routes();

    $matchedUri = exactMatchUriInArrayRoutes($uri, $routes);

    if (empty($matchedUri)) {
        $matchedUri = regularExpressionMatchArrayRoutes($uri, $routes);
        // Exclui a primeira barra e separa a URI
        $uri = explode('/', ltrim($uri, '/'));
        $params = params($uri, $matchedUri);
        $params = paramsFormat($uri, $params);
    }

    var_dump($matchedUri);
    die();
}