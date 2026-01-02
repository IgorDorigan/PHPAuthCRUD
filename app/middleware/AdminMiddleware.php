<?php

namespace app\middleware;


class AdminMiddleware
{
    public static function handle()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $user = $_SESSION['user_id'] ?? null;

        if (!$user || $_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            echo "Acesso negado: você não tem permissão para acessar esta página.";
            exit;
        }
    }
}