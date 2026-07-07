<?php

namespace app\controllers;

class UserController
{
    public function show(int $id)
    {
        var_dump($id);
        die();
    }

    public function create()
    {
        var_dump('create');
        die();
    }
}
