<?php
/**
 * 共享布局
 *
 * 由各页面视图通过 $this->layout('layouts/app') 套用；
 * 子视图渲染后的 HTML 通过 $content 注入这里。CSS 按当前主题加载。
 *
 * @var string $title    页面标题
 * @var string $content  子视图已渲染的 HTML（可信，无需转义）
 */
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title><?= e($title ?? '成绩查询') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
    <meta name="description" content="电子成绩查询系统，无需奔波，移动端即可查看自己的成绩。">
    <meta name="keywords" content="学生成绩查询,电子成绩单,成绩,学生,成绩系统">
    <link rel="stylesheet" href="<?= e(asset('css/' . config('app.theme', 'default') . '.css')) ?>">
</head>
<body>
<?= $content ?? '' ?>
</body>
</html>
