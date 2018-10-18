<?php
namespace App\Decorator;

class Login
{
    function before($controller)
    {
        session_start();
        if (empty($_SESSION['isLogin']))
        {
            header('Location: /login/index/');
            exit;
        }
    }

    function after($return_value)
    {

    }
}