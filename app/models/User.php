<?php

namespace app\models;

use app\database\Connection;

class User
{
    public static function findByEmail(string $email): ?array
    {
        $pdo = Connection::connect();

        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute([
            'email' => $email
        ]);

        $user = $stmt->fetch();

        return $user ?: null;
    }

}
