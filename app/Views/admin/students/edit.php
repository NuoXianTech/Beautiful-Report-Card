<?php
/**
 * 编辑学生表单
 *
 * @var string $title
 * @var array  $subjects
 * @var array  $student
 */
$this->layout('layouts/admin');
?>
<div class="mdui-typo"><h2 style="margin:20px 30px;">编辑学生</h2></div>

<div style="margin:0 30px 40px;">
    <form method="post" action="<?= e(base_url('/admin/students/' . rawurlencode($student['number']))) ?>">
        <?= csrf_field() ?>
        <?php $this->partial('students/_fields', ['subjects' => $subjects, 'student' => $student, 'lockNumber' => true]); ?>

        <div class="mdui-m-t-2">
            <button type="submit" class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-black">保存</button>
            <a href="<?= e(base_url('/admin/students')) ?>" class="mdui-btn mdui-ripple">取消</a>
        </div>
    </form>
</div>
