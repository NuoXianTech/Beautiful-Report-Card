<?php
/**
 * 默认主题 - 成绩查询结果
 *
 * 注意各科成绩用 subjects 配置数组循环输出，对应数据表 custom_text1..N，
 *
 * @var string $title
 * @var array  $subjects 科目名称数组
 * @var array  $student  单条成绩记录
 */
$this->layout('layouts/app');
?>
<div class="indexBox">
    <p class="indexTitle">成绩查询结果</p>
    <table border="0" align="center">
        <tbody>
            <tr>
                <td>考生号</td>
                <td>姓名</td>
                <?php foreach ($subjects as $subject): ?>
                    <td><?= e($subject) ?></td>
                <?php endforeach; ?>
            </tr>
            <tr>
                <td><?= e($student['number']) ?></td>
                <td><?= e($student['name']) ?></td>
                <?php foreach ($subjects as $i => $subject): ?>
                    <td><?= e($student['custom_text' . ($i + 1)] ?? '') ?></td>
                <?php endforeach; ?>
            </tr>
        </tbody>
    </table>
</div>
