<?php

namespace App\Controllers;

use App\Models\Result;
use Core\Controller;
use Core\Response;

/**
 * 前台首页控制器：成绩查询表单与查询结果。
 */
class HomeController extends Controller
{
    /** 查询表单页。 */
    public function index(): Response
    {
        return $this->formView();
    }

    /** 处理查询提交。 */
    public function query(): Response
    {
        $number = trim((string) $this->request->post('number', ''));
        $id     = trim((string) $this->request->post('id', ''));

        // 基础校验。注意：防 SQL 注入靠预处理（见 Result 模型）
        if ($number === '' || $id === '') {
            return $this->formView(['error' => '请完整填写 考生号/准考证号 与 身份证号后六位。']);
        }

        $student = (new Result())->findByNumberAndId($number, $id);

        if ($student === null) {
            return $this->formView(['error' => '查询的学生信息不存在，请核对后重试。']);
        }

        return $this->view('result', $this->shared(['student' => $student]));
    }

    /** 渲染查询表单（可带错误提示）。 */
    private function formView(array $extra = []): Response
    {
        return $this->view('query', $this->shared($extra));
    }

    /** 各视图共用的基础数据。 */
    private function shared(array $extra = []): array
    {
        return array_merge([
            'title'    => config('app.name', '成绩查询'),
            'subjects' => config('subjects', []),
        ], $extra);
    }
}
