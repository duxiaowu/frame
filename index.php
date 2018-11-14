<?php
header('Access-Control-Allow-Origin:*');
require_once __DIR__ . '/global.php';
require_once _ROOT . '/autoload.php';
$con = isset($_REQUEST['con']) ? $_REQUEST['con'] : '';
$act = isset($_REQUEST['act']) ? $_REQUEST['act'] : 'index';
$controller = null;
switch ($con) {
    case 'index':
        $controller = new \App\Controller\IndexController();
        break;
}

if (isset($GLOBALS['Decorator']))
{
    foreach($GLOBALS['Decorator'] as $class)
    {
        $decorators[] = new $class;
    }
}
foreach($decorators as $decorator)
{
    $decorator->before($controller);
}
if ($controller instanceof App\Controller\BaseController) {
    $return = $controller->$act();
}
foreach($decorators as $decorator)
{
    $decorator->after($return);
}



