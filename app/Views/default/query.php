<?php
/**
 * 默认主题 - 成绩查询表单
 *
 * @var string      $title
 * @var array       $subjects
 * @var string|null $error
 */
$this->layout('layouts/app');
?>
<div class="indexBox">
    <p class="indexTitle">成绩查询</p>
    <form class="indexBox-main" method="post" action="<?= e(base_url('/')) ?>">

        <?php if (!empty($error)): ?>
            <p class="error"><?= e($error) ?></p>
        <?php endif; ?>

        <span>考生号 / 准考证号</span>
        <input style="border-bottom: 1px solid white" maxlength="14" name="number" placeholder="填写您的考生号 / 准考证号" type="text">

        <div class="height">&nbsp;</div>

        <span>身份证号后六位</span>
        <input style="border-bottom: 1px solid white" maxlength="6" name="id" placeholder="填写您的身份证号码后六位" type="text">

        <div class="Button">
            <input class="Button" type="submit" value="查询">
        </div>

        <?php $this->partial('partials/copyright'); ?>
    </form>
</div>
