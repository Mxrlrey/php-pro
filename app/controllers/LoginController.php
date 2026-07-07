<?php

namespace app\controllers;

use app\http\Request;
use app\models\User;
use Exception;

class LoginController
{
    public function index()
    {
        return [
            'view'  => 'login.php',
            'data'  =>  ['title' => 'Login']
        ];
    }

    public function store(Request $request)
    {
        $email = trim($request->post('email', ''));
        $password = $request->post('password', '');

        $erros = [];

        if ($email === '') {
            $erros['email'] = 'Email obrigatório';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erros['email'] = 'Email inválido';
        }

        if ($password === '') {
            $erros['password'] = 'A senha é obrigatória';
        }

        if (!empty($erros)) {
            return [
                'view' => 'login.php',
                'data' => [
                    'title' => 'Login',
                    'erros' => $erros,
                    'email' => $email,
                ],
            ];
        }

        $user = User::findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            return [
                'view' => 'login.php',
                'data' => [
                    'title' => 'Login',
                    'erros' => [
                        'login' => 'Email ou senha inválidos',
                    ],
                    'email' => $email,
                ],
            ];
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];

        header('Location: /');
        exit;
    }
}
