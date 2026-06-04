<?php

namespace Core\Http;

use Core\Response;
use RuntimeException;

/**
 * 携带响应的异常
 *
 * 在任意位置抛出本异常即可中断当前流程，并由 App 直接发送其携带的 Response。
 * 典型用途：鉴权失败跳转登录页、CSRF 校验失败返回 403。
 */
class HttpResponseException extends RuntimeException
{
    /** @var Response */
    private $response;

    public function __construct(Response $response)
    {
        parent::__construct('HTTP response exception');
        $this->response = $response;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }
}
