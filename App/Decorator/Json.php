<?php
/*
 *装饰者模式
 */
namespace App\Decorator;

class Json
{
    function before($controller)
    {

    }

    function after($return_value)
    {
        if(empty($return_value)) $return_value = [];
        if (isset($_GET['return']) && $_GET['return'] == 'json')
        {
            echo json_encode($return_value);
            exit;
        }
        if (isset($_GET['return']) && $_GET['return'] == 'jsonp')
        {
            $callback = $_REQUEST['callback'];
            echo $callback . '(' . json_encode($return_value) . ')';
            exit;
        }
        echo json_encode($return_value);
        exit;
    }
}