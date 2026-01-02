<?php

namespace App\middleware;

use App\service\JwtService;
use App\utils\ApiResponse;


class AuthApiMiddleware
{
    public static function handle()
    {
        $headers = getallheaders();

        $token = $headers['Authorization'] ?? '';

        if (! $token) {
            ApiResponse::send(null, false, 401, 'Token ausente');
            exit;
        }

        $token = str_replace('Bearer ', '', $token);
        $token = trim($token);

        $user = JwtService::validate($token);

        if (! $user) {
            ApiResponse::send(null, false, 401, 'Token inválido ou expirado');
            exit;
        }

        return $user;

    }
}