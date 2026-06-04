<?php

namespace Core;

use PDO;
use PDOException;

/**
 * 数据库访问层
 *
 * 基于 PDO，惰性建立单例连接，所有查询一律使用「预处理语句 + 绑定参数」，
 * 从根本上杜绝 SQL 注入——这正是旧版 inc/Query_info.php 字符串拼接 SQL 的替代方案。
 */
class Database
{
    /** @var PDO|null */
    private static $pdo = null;

    /** 获取（必要时建立）PDO 连接。 */
    public static function connection(): PDO
    {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }

        $host    = Config::get('database.host', 'localhost');
        $port    = (int) Config::get('database.port', 3306);
        $name    = Config::get('database.name', '');
        $charset = Config::get('database.charset', 'utf8mb4');
        $user    = Config::get('database.user', 'root');
        $pass    = (string) Config::get('database.pass', '');

        $dsn = "mysql:host={$host};port={$port};dbname={$name};charset={$charset}";

        try {
            self::$pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // 出错抛异常
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // 默认返回关联数组
                PDO::ATTR_EMULATE_PREPARES   => false,                 // 关闭模拟，使用真正的预处理
            ]);
        } catch (PDOException $e) {
            // 不向用户暴露连接细节，仅记录到日志
            error_log('数据库连接失败：' . $e->getMessage());
            http_response_code(500);
            exit('数据库连接失败，请检查 config/config.php 配置。');
        }

        return self::$pdo;
    }

    /**
     * 执行查询并返回所有行。
     * @param array $params 绑定参数，按占位符顺序或命名传入
     */
    public static function select(string $sql, array $params = []): array
    {
        $stmt = self::connection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /** 执行查询并返回第一行，无结果返回 null。 */
    public static function selectOne(string $sql, array $params = []): ?array
    {
        $stmt = self::connection()->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    /** 执行写操作（INSERT/UPDATE/DELETE），返回受影响行数。 */
    public static function execute(string $sql, array $params = []): int
    {
        $stmt = self::connection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    /** 最近一次 INSERT 的自增 ID。 */
    public static function lastInsertId(): string
    {
        return self::connection()->lastInsertId();
    }
}
