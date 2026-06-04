<?php

namespace Core;

use InvalidArgumentException;

/**
 * 模型基类
 *
 * 封装对单张数据表的常用操作，全部通过 Database 的预处理执行。
 * 子类只需指定 $table，即可获得基础查询能力。
 *
 * 注意：表名/列名来自代码（可信），值一律走绑定参数；列名额外做白名单校验，杜绝注入。
 */
abstract class Model
{
    /** 对应的数据表名，由子类指定。 */
    protected $table = '';

    /** 查询全部记录。 */
    public function all(): array
    {
        return Database::select("SELECT * FROM `{$this->table}`");
    }

    /** 统计记录总数。 */
    public function count(): int
    {
        $row = Database::selectOne("SELECT COUNT(*) AS c FROM `{$this->table}`");
        return (int) ($row['c'] ?? 0);
    }

    /**
     * 按「列 => 值」条件查询第一行，多个条件用 AND 连接。
     * @return array|null
     */
    public function firstWhere(array $conditions): ?array
    {
        [$where, $params] = $this->buildWhere($conditions);
        return Database::selectOne(
            "SELECT * FROM `{$this->table}` WHERE {$where} LIMIT 1",
            $params
        );
    }

    /**
     * 由「列 => 值」构造 WHERE 子句和参数数组。
     * 列名做白名单校验，值用 ? 占位并参数化。
     *
     * @return array{0:string,1:array}
     */
    protected function buildWhere(array $conditions): array
    {
        $clauses = [];
        $params  = [];
        foreach ($conditions as $column => $value) {
            if (!preg_match('/^[a-zA-Z0-9_]+$/', (string) $column)) {
                throw new InvalidArgumentException("非法列名：{$column}");
            }
            $clauses[] = "`{$column}` = ?";
            $params[]  = $value;
        }
        return [implode(' AND ', $clauses), $params];
    }
}
