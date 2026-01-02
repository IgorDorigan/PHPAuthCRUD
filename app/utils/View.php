<?php

namespace App\utils;

class View
{
    public static function render($view, $vars = [])
    {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }   

        extract($vars);
        include __DIR__ . '/../../resource/pages/' . $view . '.php';
    }
}
