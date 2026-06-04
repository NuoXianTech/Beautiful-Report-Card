<?php

namespace Core;

/**
 * 后台登录态管理（基于 Session）
 *
 * 校验密码时优先使用 config 的 password_hash（password_verify），
 * 未配置哈希时回退到明文 password（hash_equals 防时序攻击）。
 * 登录成功后重置 session id，防会话固定攻击。
 *
 * 这取代了旧版「Cookie 值 = md5(明文密码)」这种可预测、不可失效的脆弱方案。
 */
class Auth
{
    private const KEY = 'admin_authenticated';

    /** 尝试用密码登录，成功返回 true。 */
    public static function attempt(string $password): bool
    {
        $hash = (string) Config::get('admin.password_hash', '');
        if ($hash !== '') {
            $ok = password_verify($password, $hash);
        } else {
            $plain = (string) Config::get('admin.password', '');
            $ok = ($plain !== '' && hash_equals($plain, $password));
        }

        if ($ok) {
            Session::start();
            session_regenerate_id(true); // 防会话固定
            Session::set(self::KEY, true);
        }
        return $ok;
    }

    /** 当前是否已登录。 */
    public static function check(): bool
    {
        return (bool) Session::get(self::KEY, false);
    }

    /** 退出登录。 */
    public static function logout(): void
    {
        Session::destroy();
    }
}
