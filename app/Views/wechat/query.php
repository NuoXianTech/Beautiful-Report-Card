<?php
/**
 * wechat 主题 - 成绩查询表单
 *
 * @var string      $title
 * @var array       $subjects
 * @var string|null $error
 */
$this->layout('layouts/app');
?>
<div style="width:90%;margin:130px auto;text-align:center;">
    <h1 style="margin:10px auto;">电子成绩单</h1>
    <div>
        <?php if (!empty($error)): ?>
            <p style="color:#e64340;"><?= e($error) ?></p>
        <?php endif; ?>
        <form method="post" action="<?= e(base_url('/')) ?>">
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">身份证</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" maxlength="6" name="id" placeholder="请输入身份证后六位">
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">学号</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" name="number" placeholder="请输入学号">
                </div>
            </div>
            <div style="margin:30px auto;">
                <input class="weui-btn weui-btn_primary" type="submit" value="查询">
                <button class="weui-btn weui-btn_default" type="reset">重置</button>
            </div>
        </form>
    </div>
</div>
