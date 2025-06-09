<?php
    require "./../config.php";

    // 判断是否存在cookie且符合密码
    if ($_COOKIE['cookie'] === md5($admin_password)) {

    } else {
        header("Location: login.php");
        exit();
    }
?>
<?php require __DIR__."/action/Get_latest.php"; // 加载检查更新文件 ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>学生电子成绩管理系统</title>
    <meta name="author" content="nuoxian">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no"/>
    <link rel="stylesheet" href="https://cdnjs.loli.net/ajax/libs/mdui/2.1.3/mdui.css">
    <script src="https://cdnjs.loli.net/ajax/libs/mdui/2.1.3/mdui.global.js"></script>
    <script>$ = mdui.$;</script>
</head>
<body class="mdui-theme-primary-indigo mdui-theme-accent-pink mdui-drawer-body-left mdui-appbar-with-toolbar">
<?php require_once ("component/navbar.php"); ?>
    <div class="mdui-typo" style="text-align: center;">
        <h2>关于我们</h2>
        <p><b>作者：</b><abbr title="作者网站：https://nuoxiana.cn">nuoxian</abbr>（学习，还是学习。做一个知识分子！）</p>
        <p>这是我学习PHP的第一个练习项目，可能有部分不完善或有风险。使用PHP+MYSQL开发的学生电子成绩查询系统，输入学号和身份证号即可查询信息，欢迎体验。</p>
        <div class="mdui-typo">
            <hr/>
        </div>
        <h4 class="mdui-center">系统信息</h4>
        <div class="mdui-dialog" id="Update_content">
            <div class="mdui-dialog-content">
                <div class="mdui-dialog-title">更新日期 (<?php echo $data['update_time']; ?>)</div>
                <p class="mdui-text-left"><?php echo str_replace("\r\n", "<br>", $data['update_content']); ?></p>
            </div>
            <div class="mdui-dialog-actions">
                <button class="mdui-btn mdui-ripple mdui-text-color-black" onclick="window.location.href='<?php echo $data['download']; ?>'">前往更新</button>
                <button class="mdui-btn mdui-ripple mdui-text-color-black" mdui-dialog-close>确定</button>
            </div>
        </div>
        <div class="mdui-p-b-3">
            <button class="mdui-btn mdui-btn-raised mdui-ripple" onclick="window.location.href='https:\/\/nuoxiana.cn\/'"><i class="mdui-icon material-icons">&#xe7fd;</i>作者网站</button>
            <button class="mdui-btn mdui-btn-raised mdui-ripple" onclick="window.location.href='https:\/\/github.com/nuoxianCN/Beautiful-Report-Card'"><i class="mdui-icon material-icons">&#xe838;</i>项目主页</button>
            <button class="mdui-btn mdui-btn-raised mdui-ripple" mdui-dialog="{target: '#Update_content'}"><i class="mdui-icon material-icons">&#xe41a;</i>更新日志</button>
        </div>
    </div>
        <ul class="mdui-list mdui-shadow-3 mdui-m-x-5">
            <li class="mdui-list-item mdui-ripple">
                <i class="mdui-icon material-icons">&#xe322;</i>&nbsp;
                学生电子成绩管理系统当前版本
                <div class="mdui-list-item-content mdui-text-right">Ver <?php echo $Program_version; ?></div>
            </li>
            <li class="mdui-list-item mdui-ripple">
                <i class="mdui-icon material-icons">&#xe2bd;</i>&nbsp;
                学生电子成绩管理系统最新版本
                <div class="mdui-list-item-content mdui-text-right">Ver <?php echo $data['latest_version'] ?></div>
            </li>
            <li class="mdui-list-item mdui-ripple">
            <i class="mdui-icon material-icons">&#xe875;</i>&nbsp;
                服务器信息
                <div class="mdui-list-item-content mdui-text-right"><?php echo $_SERVER['SERVER_SOFTWARE'] ?></div>
            </li>
            <li class="mdui-list-item mdui-ripple">
                <i class="mdui-icon material-icons">&#xe80b;</i>&nbsp;
                服务器 PHP 版本
                <div class="mdui-list-item-content mdui-text-right"><?php echo substr(PHP_VERSION,0,6); ?></div>
            </li>
            <li class="mdui-list-item mdui-ripple">
                <i class="mdui-icon material-icons">&#xe6c4;</i>&nbsp;
                服务器 MYSQL 版本
                <div class="mdui-list-item-content mdui-text-right"><?php $link = @mysqli_connect($host,$username,$password,$dbname,$port); if($link){printf(substr($link -> server_info,0,6));}else{echo "无法连接至数据库";}; mysqli_close($link); ?></div>
            </li>
            <a class="mdui-ripple" href="https://ip.sanv.org/index.php?ip=<?php echo $_SERVER['REMOTE_ADDR']; ?>" target="_blank">
                <li class="mdui-list-item mdui-ripple">
                    <i class="mdui-icon material-icons">&#xe250;</i>&nbsp;
                    正在连接的 IP
                    <div class="mdui-list-item-content mdui-text-right"><?php echo $_SERVER['REMOTE_ADDR']; ?></div>
                </li>
            </a>
        </ul>
</body>
</html>