<?php

namespace App\Controllers\Admin;

use Core\Auth;
use Core\Controller;
use Core\Response;
use Core\Session;
use Core\View;

/**
 * 后台认证：登录页、登录处理、登出。
 * 本控制器不继承 AdminController（登录页本身无需登录态）。
 */
class AuthController extends Controller
{
    /** 显示登录页；已登录则直接进后台。 */
    public function showLogin(): Response
    {
        if (Auth::check()) {
            return $this->redirect('/admin');
        }
        return $this->loginView();
    }

    /** 处理登录提交。 */
    public function login(): Response
    {
        $token = $this->request->post('_csrf');
        if (!Session::verifyCsrf(is_string($token) ? $token : null)) {
            return $this->loginView('会话已过期，请重试。');
        }

        $pwd = (string) $this->request->post('password', '');
        if ($pwd === '') {
            return $this->loginView('请输入密码。');
        }
        if (!Auth::attempt($pwd)) {
            return $this->loginView('密码错误。');
        }
        return $this->redirect('/admin');
    }

    /** 登出。 */
    public function logout(): Response
    {
        Auth::logout();
        return $this->redirect('/admin/login');
    }

    /** 渲染登录页（admin 视图目录）。 */
    private function loginView(?string $error = null): Response
    {
        View::setTheme('admin');
        return (new Response())->html(View::make('login', [
            'title' => '后台登录',
            'error' => $error,
        ]));
    }
}
