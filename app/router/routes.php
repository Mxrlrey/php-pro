<?php

// Rotas do sistema
return [
    '/' => 'Home@index',
    '/user/create' => "User@create",
    '/user/[0-9]+' => 'User@index',
];