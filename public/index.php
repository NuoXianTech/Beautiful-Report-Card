<?php
/**
 * 唯一入口（Front Controller）
 *
 * 所有请求都经由本文件进入框架。Web 服务器的 DocumentRoot 应指向 public 目录，
 * 这样 core/、config/、app/ 等源码不在 Web 可访问范围内，更安全。
 */

// PHP 内置服务器：若请求的是真实存在的静态文件，交还给服务器直接输出
if (PHP_SAPI === 'cli-server') {
    $file = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (is_file($file)) {
        return false;
    }
}

require dirname(__DIR__) . '/bootstrap.php';

(new Core\App())->run();
