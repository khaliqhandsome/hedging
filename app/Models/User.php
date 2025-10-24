<?php

namespace App\Models;

use PDO;

class User
{
    public function __construct(private PDO $db)
    {
    }

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = :username LIMIT 1');
        $stmt->execute(['username' => $username]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
}
