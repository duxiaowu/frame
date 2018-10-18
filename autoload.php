<?php
class ClassLoader {
    public static function load($className) {
        // 类全名的反斜杠替换为斜杠 并拼接后缀
        $path = str_replace('\\', '/', $className) . '.php';
        $fileName = _ROOT . '/' . lcfirst($path);
        if (file_exists($fileName)) {
            require_once $fileName;
            return true;
        }
        $fileName = _ROOT . '/' . ucfirst($path);
        if (file_exists($fileName)) {
            require_once $fileName;
            return true;
        }
        return false;
    }
}

// 将自动加载方法注册到__autoload队列
spl_autoload_register('ClassLoader::load');
