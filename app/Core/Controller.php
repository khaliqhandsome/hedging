<?php

namespace Core;

use PDO;

abstract class Controller
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    protected function view(string $view, array $data = []): string
    {
        extract($data);
        ob_start();
        require __DIR__ . '/../Views/' . $view . '.php';
        $content = ob_get_clean();

        ob_start();
        require __DIR__ . '/../Views/layouts/main.php';
        return ob_get_clean();
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit;
    }

    protected function ensureAuthenticated(): void
    {
        if (empty($_SESSION['user'])) {
            $this->redirect('/login');
        }
    }

    protected function authorize(array $roles): void
    {
        $this->ensureAuthenticated();
        $user = $_SESSION['user'];
        if (!in_array($user['role'], $roles, true)) {
            http_response_code(403);
            echo '403 Forbidden';
            exit;
        }
    }
}
