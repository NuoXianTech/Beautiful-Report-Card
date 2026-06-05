<?php

namespace App\Controllers;

use Core\Controller;
use Core\Installer;
use Core\Response;
use Core\Session;
use Core\View;

/**
 * 安装向导（仅未安装时可访问，由 Core\App 的安装守卫控制）。
 *
 * 流程：填写 MySQL 连接 + 站点信息 + 后台密码 → 测试连接 → 自动建库建表导入数据 → 生成配置。
 */
class InstallController extends Controller
{
    /** 默认科目（与旧版一致）。 */
    private const SUBJECTS = ['语文', '数学', '英语', '政治', '历史', '地理', '物理', '化学', '生物'];

    /** 显示安装表单。 */
    public function show(): Response
    {
        return $this->render();
    }

    /** 处理安装提交。 */
    public function install(): Response
    {
        $token = $this->request->post('_csrf');
        if (!Session::verifyCsrf(is_string($token) ? $token : null)) {
            return $this->render(['error' => '会话已过期，请重新提交。']);
        }

        $db = [
            'host'  => trim((string) $this->request->post('db_host', 'localhost')) ?: 'localhost',
            'port'  => (int) ($this->request->post('db_port', 3306) ?: 3306),
            'name'  => trim((string) $this->request->post('db_name', '')),
            'user'  => trim((string) $this->request->post('db_user', '')),
            'pass'  => (string) $this->request->post('db_pass', ''),
            'table' => trim((string) $this->request->post('db_table', 'result')) ?: 'result',
        ];
        $adminPwd = (string) $this->request->post('admin_password', '');
        $appName  = trim((string) $this->request->post('app_name', '')) ?: '学生电子成绩查询系统';
        $theme    = $this->request->post('app_theme') === 'wechat' ? 'wechat' : 'default';
        $baseUrl  = trim((string) $this->request->post('base_url', ''));

        $old = compact('db', 'appName', 'theme', 'baseUrl');

        // 基础校验
        if ($db['name'] === '' || $db['user'] === '') {
            return $this->render(['error' => '数据库名与用户名为必填项。', 'old' => $old]);
        }
        if (strlen($adminPwd) < 6) {
            return $this->render(['error' => '后台密码至少需要 6 位。', 'old' => $old]);
        }

        // 测试连接
        [$ok, $err] = Installer::testConnection($db);
        if (!$ok) {
            return $this->render(['error' => '数据库连接失败：' . $err, 'old' => $old]);
        }

        // 执行安装
        [$ok, $err] = Installer::install([
            'app'            => ['name' => $appName, 'theme' => $theme, 'base_url' => $baseUrl],
            'database'       => $db,
            'admin_password' => $adminPwd,
            'subjects'       => self::SUBJECTS,
        ]);
        if (!$ok) {
            return $this->render(['error' => '安装失败：' . $err, 'old' => $old]);
        }

        View::setTheme('install');
        return (new Response())->html(View::make('success', ['title' => '安装完成']));
    }

    /** 渲染安装表单（可带错误与回填）。 */
    private function render(array $extra = []): Response
    {
        View::setTheme('install');
        return (new Response())->html(View::make('index', array_merge(['title' => '安装向导'], $extra)));
    }
}
