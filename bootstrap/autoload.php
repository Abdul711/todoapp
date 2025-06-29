<?php
require_once __DIR__ . '/../app/Support/Str.php';
spl_autoload_register(function ($class) {
    $baseDir = __DIR__ . '/../app/';
    $classPath = str_replace('App\\', '', $class);
    $file = $baseDir . str_replace('\\', '/', $classPath) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});