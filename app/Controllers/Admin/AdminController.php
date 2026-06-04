<?php

namespace App\Controllers\Admin;

use Core\Auth;
use Core\Http\HttpResponseException;
use Core\Request;
use Core\Response;
use Core\Session;
use Core\View;

/**
 * 后台控制器基类
 *
 * - 构造即鉴权：未登录直接抛出「重定向到登录页」的响应；
 * - view() 固定使用 admin 视图目录，与前台主题无关；
 * - verifyCsrf() 校验 POST 表单令牌，失败返回 403。
 */
abstract class AdminController
{
    /** @var Request */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;

        if (!Auth::check()) {
            throw new HttpResponseException(
                (new Response())->redirect(base_url('/admin/login'))
            );
        }
    }

    /** 用 admin 视图目录渲染。 */
    protected function view(string $template, array $data = []): Response
    {
        View::setTheme('admin');
        return (new Response())->html(View::make($template, $data));
    }

    /** 重定向到站内路径。 */
    protected function redirect(string $path): Response
    {
        return (new Response())->redirect(base_url($path));
    }

    /** 校验 POST 的 CSRF 令牌，失败抛出 403。 */
    protected function verifyCsrf(): void
    {
        $token = $this->request->post('_csrf');
        if (!Session::verifyCsrf(is_string($token) ? $token : null)) {
            throw new HttpResponseException(
                (new Response())->status(403)->html('<h1>403 Forbidden</h1><p>CSRF 校验失败，请返回重试。</p>')
            );
        }
    }
}
