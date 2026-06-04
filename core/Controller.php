<?php

namespace Core;

/**
 * 控制器基类
 *
 * 提供请求对象与几个便捷方法（视图渲染、重定向、JSON）。
 * 业务控制器继承它，方法应「返回」一个 Response，交由 App 统一发送。
 */
abstract class Controller
{
    /** @var Request */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /** 按当前主题渲染视图，返回 HTML 响应。 */
    protected function view(string $template, array $data = []): Response
    {
        View::setTheme((string) Config::get('app.theme', 'default'));
        return (new Response())->html(View::make($template, $data));
    }

    /** 重定向到站内路径。 */
    protected function redirect(string $path): Response
    {
        return (new Response())->redirect(base_url($path));
    }

    /** 返回 JSON 响应。 @param mixed $data */
    protected function json($data): Response
    {
        return (new Response())->json($data);
    }
}
