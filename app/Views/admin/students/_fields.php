<?php
/**
 * 学生表单字段（add / edit 共用的局部组件）
 *
 * @var array $subjects
 * @var array $student    回填数据，可为空
 * @var bool  $lockNumber 是否锁定考生号（编辑时为 true）
 */
$student    = $student ?? [];
$lockNumber = $lockNumber ?? false;
?>
<div class="mdui-textfield">
    <label class="mdui-textfield-label">考生号 / 学号</label>
    <input class="mdui-textfield-input" name="number" maxlength="24" value="<?= e($student['number'] ?? '') ?>"<?= $lockNumber ? ' readonly' : '' ?>>
</div>
<div class="mdui-textfield">
    <label class="mdui-textfield-label">身份证后六位</label>
    <input class="mdui-textfield-input" name="id" maxlength="6" value="<?= e($student['id'] ?? '') ?>">
</div>
<div class="mdui-textfield">
    <label class="mdui-textfield-label">姓名</label>
    <input class="mdui-textfield-input" name="name" maxlength="14" value="<?= e($student['name'] ?? '') ?>">
</div>
<div class="mdui-textfield">
    <label class="mdui-textfield-label">老师批语 / 备注</label>
    <textarea class="mdui-textfield-input" name="remarks"><?= e($student['remarks'] ?? '') ?></textarea>
</div>
<?php foreach ($subjects as $i => $s): $col = 'custom_text' . ($i + 1); ?>
<div class="mdui-textfield">
    <label class="mdui-textfield-label"><?= e($s) ?></label>
    <input class="mdui-textfield-input" name="<?= e($col) ?>" value="<?= e($student[$col] ?? '') ?>">
</div>
<?php endforeach; ?>
