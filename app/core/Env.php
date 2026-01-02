<?php

namespace App\core;

class Env
{
    public static function load($dir)
    {
        $path = $dir . '/.env';

        if (!file_exists($path)) {
            throw new \Exception('.env file not found');
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line){
            if (str_starts_with(trim($line), '#')) {
                continue;
            }

            list($key, $value) = explode('=', $line, 2);

            $key = trim($key);
            $value = trim($value);

            if (!array_key_exists($key, $_ENV)) {
               $_ENV[$key] = $value;
            }

            putenv("$key=$value");
            $_SERVER[$key] = $value;
        }
    }
}