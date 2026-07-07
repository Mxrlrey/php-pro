<?php

namespace app\controllers;

class HomeController
{
    public function index()
    {
        return [
            'view'  => 'home.php',
            'data'  =>  ['title' => 'Home']
        ];
    }
}
