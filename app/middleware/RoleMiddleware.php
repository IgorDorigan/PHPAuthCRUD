<?php

namespace App\Middleware;

class RoleMiddleware
{
    private $allowedRoles;

    public function __construct(array $allowedRoles)
    {
        $this->allowedRoles = $allowedRoles;
    }

    public function handle()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $user = $_SESSION['user_id'] ?? null;

        if (!$user || !in_array($_SESSION['user_role'], $this->allowedRoles)) {
            http_response_code(403);
            echo "Acesso negado: você não tem permissão para acessar esta página.";
            exit;
        }
    }
}