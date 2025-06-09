<?php
require __DIR__."/config.php"; // 引入当前目录下的指定文件

// 检查是否能够连接数据库

$link = @mysqli_connect($host, $username, $password, $dbname, $port);
if ($link) {
    @mysqli_close ($link); // 关闭数据库
    header("Location: themes/$themes/"); // 跳转至相关页面
} else {
    @mysqli_close ($link);
    die ("Error establishing a database connection."); // 无法连接数据库抛出
}
?>