<?php
/**
 * 后台布局
 *
 * @var string $title
 * @var string $content 子视图已渲染的 HTML
 */
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title><?= e($title ?? '后台') ?> - 学生电子成绩管理系统</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <link rel="icon" href="<?= e(base_url('favicon.ico')) ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/mdui@1.0.2/dist/css/mdui.min.css">
</head>
<body class="mdui-theme-primary-indigo mdui-theme-accent-pink mdui-drawer-body-left mdui-appbar-with-toolbar">
<?php $this->partial('partials/navbar'); ?>
<?= $content ?? '' ?>
<script src="https://cdn.jsdelivr.net/npm/mdui@1.0.2/dist/js/mdui.min.js"></script>
</body>
</html>
