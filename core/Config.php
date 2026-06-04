<?php

namespace Core;

/**
 * 配置容器
 *
 * 启动时由 bootstrap 载入一份配置数组，之后全局静态读取。
 * 支持用点号访问嵌套项，例如 Config::get('database.host')。
 */
class Config
{
    /** @var array 已加载的配置项 */
    private static $items = [];

    /** 载入配置数组（覆盖式）。 */
    public static function load(array $items): void
    {
        self::$items = $items;
    }

    /**
     * 读取配置项，支持 'a.b.c' 形式的点号路径。
     *
     * @param mixed $default 取不到时的默认值
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        $value = self::$items;
        foreach (explode('.', $key) as $segment) {
            if (is_array($value) && array_key_exists($segment, $value)) {
                $value = $value[$segment];
            } else {
                return $default;
            }
        }
        return $value;
    }

    /** 返回全部配置。 */
    public static function all(): array
    {
        return self::$items;
    }
}
