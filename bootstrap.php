<?php
/**
 * 框架引导文件
 *
 * 职责：定义路径常量、注册类自动加载、载入辅助函数与配置、设置时区与错误处理。
 * 由唯一入口 public/index.php 引入，是整个框架的启动起点。
 */

// ---- 路径常量（全部基于本文件所在的项目根目录） ----
define('BASE_PATH', __DIR__);
define('CORE_PATH', BASE_PATH . '/core');
define('APP_PATH', BASE_PATH . '/app');
define('CONFIG_PATH', BASE_PATH . '/config');
define('ROUTES_PATH', BASE_PATH . '/routes');
define('STORAGE_PATH', BASE_PATH . '/storage');
define('VIEW_PATH', APP_PATH . '/Views');

// ---- 自动加载：把命名空间映射到目录，按需 require 类文件 ----
// 例：Core\Router  -> core/Router.php
//     App\Controllers\HomeController -> app/Controllers/HomeController.php
spl_autoload_register(function (string $class): void {
    $prefixes = [
        'Core\\' => CORE_PATH . '/',
        'App\\'  => APP_PATH . '/',
    ];
    foreach ($prefixes as $prefix => $baseDir) {
        $len = strlen($prefix);
        if (strncmp($class, $prefix, $len) !== 0) {
            continue;
        }
        $relative = substr($class, $len);
        $file = $baseDir . str_replace('\\', '/', $relative) . '.php';
        if (is_file($file)) {
            require $file;
            return;
        }
    }
});

// ---- 全局辅助函数 ----
require CORE_PATH . '/helpers.php';

// ---- 加载配置 ----
$configFile = CONFIG_PATH . '/config.php';
if (!is_file($configFile)) {
    http_response_code(500);
    exit('缺少配置文件 config/config.php，请复制 config/config.sample.php 为 config.php 并填写。');
}
\Core\Config::load(require $configFile);

// ---- 时区 ----
date_default_timezone_set(\Core\Config::get('app.timezone', 'PRC'));

// ---- 错误处理：调试模式直接显示，生产模式写入日志 ----
if (\Core\Config::get('app.debug', false)) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', STORAGE_PATH . '/logs/php-error.log');
}
