<?php

namespace app\service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class JwtService
{
    private static $secret =  'minha-chave-secreta-super-segura-1234567890';

    public static function generate(array $payload)
    {
        return JWT::encode($payload, self::$secret, 'HS256');
    }

    public static function validate(string $token)
    {
        try {
            $decoded = JWT::decode($token, new Key(self::$secret, 'HS256'));
            return (array)$decoded;
            
        } catch (\Exception $e) {
            return null;
        }
    }
}