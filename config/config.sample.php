<?php
/**
 * 配置模板
 *
 * 复制本文件为 config/config.php，再按实际环境填写。
 *
 */

return [
    'app' => [
        'name'     => '学生电子成绩查询系统',
        'timezone' => 'PRC',
        'debug'    => true,      // 生产环境务必改为 false（隐藏报错细节）
        'theme'    => 'default', // 主题：default / wechat
        'base_url' => '',        // 子目录部署时填写，如 /Beautiful-Report-Card；DocumentRoot 指向 public 时留空
    ],

    'database' => [
        'host'    => 'localhost',
        'port'    => 3306,
        'name'    => 'cf',        // 改成你导入 result 表所在的数据库名
        'user'    => 'root',
        'pass'    => '',          // 数据库密码，切勿留默认
        'charset' => 'utf8mb4',
        'table'   => 'result',    // 成绩数据表名
    ],

    'admin' => [
        // 后台登录密码。优先使用 password_hash（更安全）：
        //   生成：php tools/make-password.php 你的密码 —— 把结果填入 password_hash，并将 password 留空。
        // password 为明文后备，开箱即用但不安全；生产环境请清空 password 改用 password_hash。
        'password'      => 'admin123',
        'password_hash' => '',
    ],

    // 科目名称：对应数据表的 custom_text1 / custom_text2 / ...
    // 增减科目只需改这里，无需再像旧版那样维护一堆 $Custom_textN 变量。
    'subjects' => ['语文', '数学', '英语', '政治', '历史', '地理', '物理', '化学', '生物'],
];
