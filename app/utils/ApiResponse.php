<?php

namespace App\utils;


class ApiResponse
{
    public static function send($data = null, $success = true, $statusCode = 200, $message = '')
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');

        echo json_encode([
            'success' => $success,
            'data'    => $data,
            'message' => $message
        ]);

    }

    public static function receive()
    {
        $json = file_get_contents('php://input');

        $dada = json_decode($json, true);

        return $dada;
    }
}