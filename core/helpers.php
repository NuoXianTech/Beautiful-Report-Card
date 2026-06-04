<?php
/**
 * 全局辅助函数
 *
 * 这些函数在模板与控制器中频繁使用，尤其是 e()——所有动态输出都应经过它转义。
 */

use Core\Config;
use Core\View;
use Core\Response;

if (!function_exists('e')) {
    /**
     * HTML 转义输出，防止 XSS。
     * 视图中输出任何来自数据库或用户的内容都必须用它包裹，例如：<?= e($name) ?>
     *
     * @param mixed $value
     */
    function e($value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('config')) {
    /**
     * 读取配置，支持点号路径，如 config('database.host')。
     *
     * @param mixed $default
     * @return mixed
     */
    function config(string $key, $default = null)
    {
        return Config::get($key, $default);
    }
}

if (!function_exists('base_url')) {
    /** 生成站点内 URL（兼容子目录部署的 app.base_url 配置）。 */
    function base_url(string $path = ''): string
    {
        $base = rtrim((string) Config::get('app.base_url', ''), '/');
        return $base . '/' . ltrim($path, '/');
    }
}

if (!function_exists('asset')) {
    /** 生成 public/assets 下的静态资源 URL。 */
    function asset(string $path): string
    {
        return base_url('assets/' . ltrim($path, '/'));
    }
}

if (!function_exists('view')) {
    /** 渲染视图为字符串（按当前主题解析）。 */
    function view(string $template, array $data = []): string
    {
        return View::make($template, $data);
    }
}

if (!function_exists('redirect')) {
    /** 生成一个指向站内路径的重定向响应。 */
    function redirect(string $path): Response
    {
        return (new Response())->redirect(base_url($path));
    }
}

if (!function_exists('csrf_token')) {
    /** 当前会话的 CSRF 令牌。 */
    function csrf_token(): string
    {
        return \Core\Session::csrfToken();
    }
}

if (!function_exists('csrf_field')) {
    /** 生成带 CSRF 令牌的隐藏表单字段，放进每个后台 POST 表单里。 */
    function csrf_field(): string
    {
        return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
    }
}
