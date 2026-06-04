<?php

namespace App\Controllers\Admin;

use App\Services\UpdateService;
use Core\App;
use Core\Database;
use Core\Response;
use PDO;
use Throwable;

/**
 * 后台首页：关于本系统 + 系统信息 + 检查更新。
 *
 * 相比旧版，去掉了把访客 IP 外链到第三方查询站的功能（避免泄露 IP）。
 */
class DashboardController extends AdminController
{
    public function index(): Response
    {
        return $this->view('dashboard', [
            'title'          => '关于本系统',
            'version'        => App::VERSION,
            'phpVersion'     => PHP_VERSION,
            'mysqlVersion'   => $this->mysqlVersion(),
            'serverSoftware' => $_SERVER['SERVER_SOFTWARE'] ?? '未知',
            'update'         => (new UpdateService())->latest(), // 可能为 null
        ]);
    }

    /** 取 MySQL 服务器版本，失败返回「未知」。 */
    private function mysqlVersion(): string
    {
        try {
            return (string) Database::connection()->getAttribute(PDO::ATTR_SERVER_VERSION);
        } catch (Throwable $e) {
            return '未知';
        }
    }
}
