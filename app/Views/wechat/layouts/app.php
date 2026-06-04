<?php
/**
 * wechat 主题布局（引入 weui + 主题样式）
 *
 * @var string $title
 * @var string $content
 */
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title><?= e($title ?? '电子成绩单') ?></title>
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <meta name="description" content="电子成绩查询系统，无需奔波，移动端即可查看自己的成绩。">
    <meta name="keywords" content="学生成绩查询,电子成绩单,成绩,学生,成绩系统">
    <link rel="stylesheet" href="https://unpkg.com/weui@2.5.4/dist/style/weui.min.css">
    <link rel="stylesheet" href="<?= e(asset('css/wechat.css')) ?>">
</head>
<body>
<?= $content ?? '' ?>
</body>
</html>
