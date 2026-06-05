<?php

namespace Core;

use PDO;
use RuntimeException;
use Throwable;

/**
 * 安装器（仅支持 MySQL）
 *
 * 职责：检测安装状态、测试数据库连接、自动建库 / 建表、生成 config/config.php。
 * 「是否已安装」以 config/config.php 是否存在为准——安装成功即生成它，安装向导随即失效。
 */
class Installer
{
    /** 是否已安装。 */
    public static function isInstalled(): bool
    {
        return is_file(CONFIG_PATH . '/config.php');
    }

    /** 测试能否连接 MySQL 服务器（不指定库）。返回 [bool $ok, string $error]。 */
    public static function testConnection(array $db): array
    {
        try {
            self::serverPdo($db);
            return [true, ''];
        } catch (Throwable $e) {
            return [false, $e->getMessage()];
        }
    }

    /**
     * 执行安装：建库 → 建表 → 写配置。
     * 返回 [bool $ok, string $error]。
     */
    public static function install(array $input): array
    {
        try {
            $db = $input['database'];

            // 库名 / 表名只允许标识符字符（它们会拼进 SQL，不能走参数绑定）
            self::assertIdentifier($db['name'], '数据库名');
            self::assertIdentifier($db['table'], '表名');

            $pdo = self::serverPdo($db);

            // 1. 建库（仅 MySQL 语法）
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$db['name']}` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
            $pdo->exec("USE `{$db['name']}`");

            // 2. 建表
            $pdo->exec(self::schemaSql($db['table']));

            // 3. 写配置文件（写成功即视为已安装）
            self::writeConfig($input);

            return [true, ''];
        } catch (Throwable $e) {
            return [false, $e->getMessage()];
        }
    }

    /** 连接 MySQL 服务器（不指定具体数据库，用于建库前）。 */
    private static function serverPdo(array $db): PDO
    {
        $dsn = "mysql:host={$db['host']};port={$db['port']};charset=utf8mb4";
        return new PDO($dsn, $db['user'], $db['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5,
        ]);
    }

    private static function assertIdentifier(string $value, string $label): void
    {
        if (!preg_match('/^[A-Za-z0-9_]+$/', $value)) {
            throw new RuntimeException("{$label}只能包含字母、数字、下划线。");
        }
    }

    /** 成绩表建表语句（与 sql/result.sql 一致，字符集升级为 utf8mb4）。 */
    private static function schemaSql(string $table): string
    {
        return "CREATE TABLE IF NOT EXISTS `{$table}` (
  `number` bigint(20) NOT NULL COMMENT '考生号',
  `id` bigint(20) NOT NULL COMMENT '身份证号后六位',
  `name` varchar(14) NOT NULL COMMENT '姓名',
  `remarks` text NOT NULL COMMENT '老师批语或备注',
  `custom_text1` varchar(14) NOT NULL,
  `custom_text2` varchar(14) NOT NULL,
  `custom_text3` varchar(14) NOT NULL,
  `custom_text4` varchar(14) NOT NULL,
  `custom_text5` varchar(14) NOT NULL,
  `custom_text6` varchar(14) NOT NULL,
  `custom_text7` varchar(14) NOT NULL,
  `custom_text8` varchar(14) NOT NULL,
  `custom_text9` varchar(14) NOT NULL,
  PRIMARY KEY (`number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
    }

    /** 生成 config/config.php（用 var_export 保证值被正确转义）。 */
    private static function writeConfig(array $input): void
    {
        $config = [
            'app' => [
                'name'     => $input['app']['name'],
                'timezone' => 'PRC',
                'debug'    => false,
                'theme'    => $input['app']['theme'],
                'base_url' => $input['app']['base_url'],
            ],
            'database' => [
                'host'    => $input['database']['host'],
                'port'    => (int) $input['database']['port'],
                'name'    => $input['database']['name'],
                'user'    => $input['database']['user'],
                'pass'    => $input['database']['pass'],
                'charset' => 'utf8mb4',
                'table'   => $input['database']['table'],
            ],
            'admin' => [
                'password'      => '',
                'password_hash' => password_hash($input['admin_password'], PASSWORD_DEFAULT),
            ],
            'subjects' => $input['subjects'],
        ];

        $content = "<?php\n\n// 本文件由安装向导自动生成，可手动修改。\n\nreturn " . var_export($config, true) . ";\n";
        if (@file_put_contents(CONFIG_PATH . '/config.php', $content) === false) {
            throw new RuntimeException('无法写入 config/config.php，请检查 config 目录是否可写。');
        }
    }
}
