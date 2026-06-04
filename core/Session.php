<?php

namespace Core;

/**
 * 会话封装
 *
 * 统一管理 PHP session 的启动与读写，并提供 CSRF 令牌。
 * 启动时给会话 Cookie 加上 HttpOnly / SameSite，降低被窃取与 CSRF 的风险。
 */
class Session
{
    /** 确保会话已启动（带安全 Cookie 参数）。 */
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params([
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
            session_start();
        }
    }

    /** @param mixed $default @return mixed */
    public static function get(string $key, $default = null)
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    /** @param mixed $value */
    public static function set(string $key, $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function forget(string $key): void
    {
        self::start();
        unset($_SESSION[$key]);
    }

    /** 销毁整个会话（登出用）。 */
    public static function destroy(): void
    {
        self::start();
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
    }

    /** 获取（必要时生成）CSRF 令牌。 */
    public static function csrfToken(): string
    {
        self::start();
        if (empty($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf'];
    }

    /** 校验提交上来的 CSRF 令牌是否与会话中一致。 */
    public static function verifyCsrf(?string $token): bool
    {
        self::start();
        return !empty($_SESSION['_csrf']) && is_string($token) && hash_equals($_SESSION['_csrf'], $token);
    }
}
