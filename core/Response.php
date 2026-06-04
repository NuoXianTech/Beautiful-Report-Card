<?php

namespace Core;

/**
 * 响应封装
 *
 * 收集状态码、响应头与响应体，最后由 App 统一调用 send() 输出。
 * 控制器方法应「返回」一个 Response，而不是直接 echo / header，便于测试与统一处理。
 */
class Response
{
    /** @var int */
    private $status = 200;

    /** @var array<string,string> */
    private $headers = [];

    /** @var string */
    private $body = '';

    public function status(int $code): self
    {
        $this->status = $code;
        return $this;
    }

    public function header(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function body(string $content): self
    {
        $this->body = $content;
        return $this;
    }

    /** 设置 HTML 响应体。 */
    public function html(string $content): self
    {
        return $this->header('Content-Type', 'text/html; charset=UTF-8')->body($content);
    }

    /** 设置 JSON 响应体。 @param mixed $data */
    public function json($data): self
    {
        $this->header('Content-Type', 'application/json; charset=UTF-8');
        $this->body = (string) json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return $this;
    }

    /** 302 重定向到指定 URL。 */
    public function redirect(string $url): self
    {
        return $this->status(302)->header('Location', $url);
    }

    /** 发送响应到客户端。 */
    public function send(): void
    {
        if (!headers_sent()) {
            http_response_code($this->status);
            foreach ($this->headers as $name => $value) {
                header($name . ': ' . $value);
            }
        }
        echo $this->body;
    }
}
