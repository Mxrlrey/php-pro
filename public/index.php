<?php

require 'bootstrap.php';

try {
    $data = router();

    extract($data['data']);

    if (!isset($data['view'])) {
        throw new Exception('O índice view está faltando.');
    }

    if (!file_exists(VIEWS.$data['view'])) {
        throw new Exception("Essa view {$data['view']} não existe.");
    }

    $view = $data['view'];

    require VIEWS.'master.php';
}catch (Exception $e) {
    var_dump($e ->getMessage());
}