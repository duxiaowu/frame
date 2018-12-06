<?php
require_once __DIR__ . '/global.php';
require_once _ROOT . '/autoload.php';
if (!isset($argv[1])) {
    echo 'no controller';
    exit;
}
$con = $argv[1];
$act = (!isset($argv[2])) ? "index" : $argv[2];
$lockFile = _ROOT . '/' . $con . '_' . $act . '.lock';
$lockFileHandle = fopen($lockFile, 'w');
if ($lockFileHandle === false)
    die("Can not create lock file $lockFile\n");
if (!flock($lockFileHandle, LOCK_EX + LOCK_NB)) {
    die(date("Y-m-d H:i:s") . "Process already exists.\n");
}
$cli = null;
switch ($con) {
    case 'index':
        $cli = new \App\Cli\IndexCli();
        break;
}

if ($cli instanceof App\Cli\BaseCli) {
    $cli->$act();
}



