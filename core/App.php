<?php

namespace Core;

use Core\Http\HttpResponseException;
use Throwable;

/**
 * 应用核心
 *
 * 串起整个请求生命周期：加载路由 -> 构造请求 -> 分发 -> 把结果规整为 Response -> 发送。
 * 入口 public/index.php 只需 new App()->run()。
 */
class App
{
    /** 程序版本号，由框架维护。 */
    const VERSION = '0.1.0';

    /** @var Router */
    private $router;

    public function __construct()
    {
        $this->router = new Router();
    }

    public function run(): void
    {
        // 载入路由定义（web.php 中通过 $router 注册路由）
        $router = $this->router;
        require ROUTES_PATH . '/web.php';

        $request = new Request();

        try {
            $result = $this->router->dispatch($request);
        } catch (HttpResponseException $e) {
            // 鉴权跳转、CSRF 失败等：直接发送异常携带的响应
            $result = $e->getResponse();
        } catch (Throwable $e) {
            $result = $this->handleException($e);
        }

        $this->toResponse($result)->send();
    }

    /** 把控制器返回值统一为 Response 对象。 @param mixed $result */
    private function toResponse($result): Response
    {
        if ($result instanceof Response) {
            return $result;
        }
        return (new Response())->html((string) $result);
    }

    /** 统一异常处理：记录日志，按调试模式决定是否展示细节。 */
    private function handleException(Throwable $e): Response
    {
        error_log($e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine());

        if (Config::get('app.debug', false)) {
            $body = '<h1>500 Server Error</h1><pre>'
                . e($e->getMessage()) . "\n"
                . e($e->getFile() . ':' . $e->getLine()) . '</pre>';
        } else {
            $body = '<h1>500 Server Error</h1><p>服务器内部错误，请稍后再试。</p>';
        }
        return (new Response())->status(500)->html($body);
    }
}
