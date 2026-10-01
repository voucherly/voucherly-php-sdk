<?php

spl_autoload_register(static function (string $class): void {
    $prefix = 'VoucherlyApi\\';
    if (0 !== strncmp($class, $prefix, \strlen($prefix))) {
        return;
    }

    $file = __DIR__ . '/lib/' . str_replace('\\', '/', substr($class, \strlen($prefix))) . '.php';
    if (is_file($file)) {
        require $file;
    }
});
