<?php
/**
 * 学生列表
 *
 * @var string $title
 * @var array  $subjects
 * @var array  $students
 * @var int    $total
 */
$this->layout('layouts/admin');
?>
<div class="mdui-typo"><h2 style="margin:20px 30px;">学生信息（共 <?= e($total) ?> 条）</h2></div>

<div class="mdui-table-fluid mdui-m-x-2">
    <table class="mdui-table mdui-table-hoverable">
        <thead>
            <tr>
                <th>考生号</th>
                <th>姓名</th>
                <th>身份证后六位</th>
                <th>备注</th>
                <?php foreach ($subjects as $s): ?><th><?= e($s) ?></th><?php endforeach; ?>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($students)): ?>
                <tr><td colspan="<?= e(5 + count($subjects)) ?>" class="mdui-text-center">暂无数据</td></tr>
            <?php endif; ?>
            <?php foreach ($students as $stu): ?>
            <tr>
                <td><?= e($stu['number']) ?></td>
                <td><?= e($stu['name']) ?></td>
                <td><?= e($stu['id']) ?></td>
                <td><?= e($stu['remarks']) ?></td>
                <?php foreach ($subjects as $i => $s): ?>
                    <td><?= e($stu['custom_text' . ($i + 1)] ?? '') ?></td>
                <?php endforeach; ?>
                <td style="white-space:nowrap;">
                    <a href="<?= e(base_url('/admin/students/' . rawurlencode($stu['number']) . '/edit')) ?>" class="mdui-btn mdui-btn-dense mdui-color-blue-700">编辑</a>
                    <form method="post" action="<?= e(base_url('/admin/students/' . rawurlencode($stu['number']) . '/delete')) ?>" style="display:inline" onsubmit="return confirm('确定删除该学生吗？');">
                        <?= csrf_field() ?>
                        <button type="submit" class="mdui-btn mdui-btn-dense mdui-color-red">删除</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
