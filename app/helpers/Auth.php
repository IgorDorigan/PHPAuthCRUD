<?php

if (!function_exists('authUser')) {
    function authUser(): ?array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user_id'])) {
            return [
                'id'    => $_SESSION['user_id'],
                'nome'  => $_SESSION['user_name'] ?? '',
                'role'  => $_SESSION['user_role'] ?? 'user',
                'email' => $_SESSION['user_email'] ?? '',
            ];
        }

        return null; // Nenhum usuário logado
    }
}

if (!function_exists('isAdmin')) {
    function isAdmin(): bool
    {
        $user = authUser();
        return $user && $user['role'] === 'admin';
    }
}
