<?php
/**
 * 生成后台登录密码的哈希
 *
 * 用法：php tools/make-password.php 你的密码
 * 把输出的字符串填入 config/config.php 的 admin.password_hash。
 */

if (PHP_SAPI !== 'cli') {
    exit('请在命令行运行本脚本。');
}

$pwd = $argv[1] ?? '';
if ($pwd === '') {
    fwrite(STDERR, "用法：php tools/make-password.php <密码>\n");
    exit(1);
}

echo password_hash($pwd, PASSWORD_DEFAULT), PHP_EOL;
