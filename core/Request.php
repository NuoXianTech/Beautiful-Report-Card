<?php

namespace Core;

/**
 * 请求封装
 *
 * 统一从超全局变量中读取请求方法、路径与输入参数，避免业务代码直接接触 $_POST/$_SERVER。
 */
class Request
{
    /** HTTP 方法，如 GET / POST。 */
    public function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    /**
     * 当前请求路径，始终以 / 开头、不含查询串。
     * 会自动去掉 app.base_url 前缀，便于子目录部署。
     */
    public function path(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';

        // 去掉查询串
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }

        // 去掉子目录前缀
        $base = rtrim((string) Config::get('app.base_url', ''), '/');
        if ($base !== '' && strpos($uri, $base) === 0) {
            $uri = substr($uri, strlen($base));
        }

        $uri = '/' . ltrim(rawurldecode($uri), '/');

        // 去掉结尾多余斜杠（根路径除外）
        if ($uri !== '/') {
            $uri = rtrim($uri, '/');
        }

        return $uri === '' ? '/' : $uri;
    }

    /** 取 POST 输入。 @param mixed $default @return mixed */
    public function post(string $key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }

    /** 取 GET 输入。 @param mixed $default @return mixed */
    public function query(string $key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }

    /** 取任意输入（POST 优先）。 @param mixed $default @return mixed */
    public function input(string $key, $default = null)
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    /** 是否为 POST 请求。 */
    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    /** 读取 Cookie。 @param mixed $default @return mixed */
    public function cookie(string $key, $default = null)
    {
        return $_COOKIE[$key] ?? $default;
    }

    /** 客户端 IP。 */
    public function ip(): string
    {
        return $_SERVER['REMOTE_ADDR'] ?? '';
    }
}
