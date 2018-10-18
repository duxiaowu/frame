<?php
namespace App\Decorator;

class Template
{
    /**
     * @var \IMooc\Controller
     */
    protected $controller;

    function before($controller)
    {
        $this->controller = $controller;
    }

    function after($return_value)
    {
        if ($_GET['app'] == 'html')
        {
            foreach($return_value as $k => $v)
            {
                $this->controller->assign($k, $v);
            }
            $this->controller->display();
        }
    }
}