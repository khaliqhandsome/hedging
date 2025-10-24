<?php

use Core\Database;

return function () {
    $db = Database::getConnection();

    $count = (int)$db->query('SELECT COUNT(*) FROM users')->fetchColumn();
    if ($count === 0) {
        $stmt = $db->prepare('INSERT INTO users (username, password_hash, role) VALUES (:username, :password_hash, :role)');
        $users = [
            ['username' => 'admin', 'password_hash' => password_hash('password', PASSWORD_DEFAULT), 'role' => 'admin'],
            ['username' => 'auditor', 'password_hash' => password_hash('password', PASSWORD_DEFAULT), 'role' => 'auditor'],
            ['username' => 'user', 'password_hash' => password_hash('password', PASSWORD_DEFAULT), 'role' => 'user'],
        ];

        foreach ($users as $user) {
            $stmt->execute($user);
        }
    }
};
