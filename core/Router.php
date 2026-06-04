<?php

namespace Core;

use Closure;
use RuntimeException;

/**
 * 路由器
 *
 * 注册「请求方法 + 路径 -> 处理器」，并按当前请求分发到对应控制器方法。
 * 路径支持 {param} 形式的占位符，例如 /student/{number}，匹配到的值会按顺序传给控制器方法。
 *
 * 处理器写法：
 *   - 字符串 'HomeController@index'  -> App\Controllers\HomeController->index()
 *   - 字符串 'Admin/AuthController@login' -> App\Controllers\Admin\AuthController->login()
 *   - 闭包   function (Request $req) { ... }
 */
class Router
{
    /** @var array<int, array{method:string, path:string, handler:mixed}> */
    private $routes = [];

    public function get(string $path, $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    public function add(string $method, string $path, $handler): void
    {
        $this->routes[] = [
            'method'  => strtoupper($method),
            'path'    => $path,
            'handler' => $handler,
        ];
    }

    /**
     * 按当前请求分发，返回处理器执行结果（Response 或可转为字符串的内容）。
     * @return mixed
     */
    public function dispatch(Request $request)
    {
        $method = $request->method();
        $path   = $request->path();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            if (preg_match($this->compile($route['path']), $path, $matches)) {
                // 仅保留命名捕获（路径参数）
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                return $this->callHandler($route['handler'], array_values($params), $request);
            }
        }

        return (new Response())->status(404)->html(
            '<h1>404 Not Found</h1><p>未找到路径：' . e($path) . '</p>'
        );
    }

    /** 把 '/student/{number}' 编译成匹配用的正则。 */
    private function compile(string $path): string
    {
        $regex = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $path);
        return '#^' . $regex . '$#';
    }

    /**
     * 调用处理器。
     * @param mixed $handler
     * @return mixed
     */
    private function callHandler($handler, array $params, Request $request)
    {
        if ($handler instanceof Closure) {
            return $handler($request, ...$params);
        }

        [$controller, $action] = explode('@', $handler);
        $class = 'App\\Controllers\\' . str_replace('/', '\\', $controller);

        if (!class_exists($class)) {
            throw new RuntimeException("控制器不存在：{$class}");
        }
        $instance = new $class($request);

        if (!method_exists($instance, $action)) {
            throw new RuntimeException("控制器方法不存在：{$class}::{$action}");
        }
        return $instance->$action(...$params);
    }
}
