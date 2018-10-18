<?php
define('_ENVIRONMENT', 'test'); // 运行环境 test-开发环境，dev-预发布环境，release-正式环境
define('_ROOT', __DIR__); // 项目根目录
require_once _ROOT . '/config/' . _ENVIRONMENT . '.php'; // 按照环境引入配置文件

