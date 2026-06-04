<?php

namespace App\Controllers\Admin;

use App\Models\Result;
use Core\Response;

/**
 * 后台学生管理：列表、添加、编辑、删除。
 *
 * 全部写操作经预处理执行，并要求 CSRF 令牌；删除改用 POST（旧版是 GET，存在 CSRF 风险）。
 */
class StudentController extends AdminController
{
    /** 学生列表。 */
    public function index(): Response
    {
        $model = new Result();
        return $this->view('students/index', [
            'title'    => '学生信息',
            'subjects' => config('subjects', []),
            'students' => $model->all(),
            'total'    => $model->count(),
        ]);
    }

    /** 添加表单。 */
    public function create(): Response
    {
        return $this->view('students/create', [
            'title'    => '添加学生',
            'subjects' => config('subjects', []),
            'old'      => [],
        ]);
    }

    /** 处理添加。 */
    public function store(): Response
    {
        $this->verifyCsrf();
        $data  = $this->collect();
        $model = new Result();

        if ($data['number'] === '' || $data['name'] === '') {
            return $this->view('students/create', [
                'title'    => '添加学生',
                'subjects' => config('subjects', []),
                'error'    => '考生号与姓名为必填项。',
                'old'      => $data,
            ]);
        }
        if ($model->findByNumber($data['number']) !== null) {
            return $this->view('students/create', [
                'title'    => '添加学生',
                'subjects' => config('subjects', []),
                'error'    => '该考生号已存在。',
                'old'      => $data,
            ]);
        }

        $model->create($data);
        return $this->redirect('/admin/students');
    }

    /** 编辑表单。 */
    public function edit(string $number): Response
    {
        $student = (new Result())->findByNumber($number);
        if ($student === null) {
            return $this->redirect('/admin/students');
        }
        return $this->view('students/edit', [
            'title'    => '编辑学生',
            'subjects' => config('subjects', []),
            'student'  => $student,
        ]);
    }

    /** 处理编辑。 */
    public function update(string $number): Response
    {
        $this->verifyCsrf();
        (new Result())->updateByNumber($number, $this->collect());
        return $this->redirect('/admin/students');
    }

    /** 处理删除。 */
    public function destroy(string $number): Response
    {
        $this->verifyCsrf();
        (new Result())->deleteByNumber($number);
        return $this->redirect('/admin/students');
    }

    /** 从请求收集学生字段。 */
    private function collect(): array
    {
        $r = $this->request;
        $data = [
            'number'  => trim((string) $r->post('number', '')),
            'id'      => trim((string) $r->post('id', '')),
            'name'    => trim((string) $r->post('name', '')),
            'remarks' => trim((string) $r->post('remarks', '')),
        ];
        for ($i = 1; $i <= 9; $i++) {
            $data['custom_text' . $i] = trim((string) $r->post('custom_text' . $i, ''));
        }
        return $data;
    }
}
