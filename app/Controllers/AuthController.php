<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\User;
use Core\Validator;

class AuthController extends Controller
{
    public function showLoginForm(): string
    {
        if (!empty($_SESSION['user'])) {
            $this->redirect('/');
        }

        return $this->view('auth/login', ['title' => 'Masuk']);
    }

    public function login(): string
    {
        $validator = new Validator();
        $validator->required($_POST, 'username', 'Username wajib diisi');
        $validator->required($_POST, 'password', 'Password wajib diisi');

        if (!$validator->passes()) {
            return $this->view('auth/login', [
                'errors' => $validator->errors(),
                'title' => 'Masuk',
            ]);
        }

        $userModel = new User($this->db);
        $user = $userModel->findByUsername($_POST['username']);

        if (!$user || !password_verify($_POST['password'], $user['password_hash'])) {
            return $this->view('auth/login', [
                'errors' => ['auth' => ['Username atau password salah']],
                'title' => 'Masuk',
            ]);
        }

        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'role' => $user['role'],
        ];

        $this->redirect('/');
        return '';
    }

    public function logout(): string
    {
        session_destroy();
        $this->redirect('/login');
        return '';
    }
}
