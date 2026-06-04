<?php
/**
 * 后台登录页（独立页面，不套后台布局）
 *
 * @var string      $title
 * @var string|null $error
 */
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title><?= e($title ?? '后台登录') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <link rel="icon" href="<?= e(base_url('favicon.ico')) ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/mdui@1.0.2/dist/css/mdui.min.css">
</head>
<body class="mdui-theme-primary-indigo mdui-theme-accent-pink">
    <header class="mdui-appbar mdui-color-theme-300">
        <div class="mdui-toolbar mdui-color-black">
            <div class="mdui-toolbar mdui-container mdui-typo-headline">登录</div>
            <div class="mdui-toolbar-spacer"></div>
            <a href="<?= e(base_url('/')) ?>" class="mdui-btn mdui-btn-icon" mdui-tooltip="{content: '主页'}"><i class="mdui-icon material-icons">home</i></a>
        </div>
    </header>

    <div class="mdui-container-fluid">
        <div class="mdui-col-md-6 mdui-col-offset-md-3">
            <center><h4 class="mdui-typo-display-2-opacity">系统管理</h4></center>

            <?php if (!empty($error)): ?>
                <div class="mdui-text-center mdui-text-color-red"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="post" action="<?= e(base_url('/admin/login')) ?>">
                <?= csrf_field() ?>
                <div class="mdui-textfield mdui-textfield-floating-label">
                    <i class="mdui-icon material-icons">https</i>
                    <label class="mdui-textfield-label">密码</label>
                    <input name="password" class="mdui-textfield-input" type="password" autofocus>
                </div>
                <br>
                <button type="submit" class="mdui-center mdui-btn mdui-btn-raised mdui-ripple mdui-color-black">
                    <i class="mdui-icon material-icons">fingerprint</i> 登录
                </button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/mdui@1.0.2/dist/js/mdui.min.js"></script>
</body>
</html>
