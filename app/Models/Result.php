<?php

namespace App\Models;

use Core\Config;
use Core\Database;
use Core\Model;

/**
 * 成绩表模型
 *
 * 数据表 result 字段：
 *   number           考生号 / 准考证号（主键，作为学生唯一标识）
 *   id               身份证号后六位
 *   name             姓名
 *   remarks          老师批语 / 备注
 *   custom_text1..9  各科成绩（与 config 的 subjects 一一对应）
 *
 * 所有增删改查均经 Database 预处理执行；列名取自下方白名单常量（可信），值一律参数化。
 */
class Result extends Model
{
    /** 可写入的列（白名单）。 */
    private const COLUMNS = [
        'number', 'id', 'name', 'remarks',
        'custom_text1', 'custom_text2', 'custom_text3', 'custom_text4', 'custom_text5',
        'custom_text6', 'custom_text7', 'custom_text8', 'custom_text9',
    ];

    public function __construct()
    {
        $this->table = (string) Config::get('database.table', 'result');
    }

    /**
     * 按「考生号 + 身份证后六位」联合查询成绩（前台用）。
     * 两个条件都进 SQL 并走预处理，既防注入又避免松散比较的类型陷阱。
     */
    public function findByNumberAndId(string $number, string $id): ?array
    {
        return $this->firstWhere(['number' => $number, 'id' => $id]);
    }

    /** 按考生号查询单个学生。 */
    public function findByNumber(string $number): ?array
    {
        return $this->firstWhere(['number' => $number]);
    }

    /** 新增一条学生记录。 */
    public function create(array $data): void
    {
        $cols = $marks = $params = [];
        foreach (self::COLUMNS as $col) {
            $cols[]   = "`{$col}`";
            $marks[]  = '?';
            $params[] = $data[$col] ?? '';
        }
        $sql = "INSERT INTO `{$this->table}` (" . implode(', ', $cols) . ') VALUES (' . implode(', ', $marks) . ')';
        Database::execute($sql, $params);
    }

    /** 按考生号更新（number 作为标识，不修改）。返回受影响行数。 */
    public function updateByNumber(string $number, array $data): int
    {
        $sets = $params = [];
        foreach (self::COLUMNS as $col) {
            if ($col === 'number') {
                continue;
            }
            $sets[]   = "`{$col}` = ?";
            $params[] = $data[$col] ?? '';
        }
        $params[] = $number;
        $sql = "UPDATE `{$this->table}` SET " . implode(', ', $sets) . ' WHERE `number` = ?';
        return Database::execute($sql, $params);
    }

    /** 按考生号删除。返回受影响行数。 */
    public function deleteByNumber(string $number): int
    {
        return Database::execute("DELETE FROM `{$this->table}` WHERE `number` = ?", [$number]);
    }
}
