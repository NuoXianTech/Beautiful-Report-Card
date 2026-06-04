<?php
/**
 * wechat 主题 - 成绩查询结果
 *
 * @var array $subjects
 * @var array $student
 */
$this->layout('layouts/app');
?>
<div id="detail-box">
    <h1><?= e($student['name']) ?> 的成绩单</h1>
    <h3>各科分数：</h3>
    <ul>
        <?php foreach ($subjects as $i => $s): ?>
            <li><span><?= e($s) ?></span><?= e($student['custom_text' . ($i + 1)] ?? '') ?></li>
        <?php endforeach; ?>
    </ul>
    <h3>班主任评语：</h3>
    <p><?= e($student['remarks']) ?></p>
</div>
