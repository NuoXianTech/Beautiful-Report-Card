<?php
/**
 * 后台首页：关于 + 系统信息 + 检查更新
 *
 * @var string     $title
 * @var string     $version
 * @var string     $phpVersion
 * @var string     $mysqlVersion
 * @var string     $serverSoftware
 * @var array|null $update
 */
$this->layout('layouts/admin');
?>
<div class="mdui-typo" style="text-align:center;">
    <h2>关于本系统</h2>
    <p>本系统已使用自研的轻量 MVC 框架重构：单一入口、PDO 预处理、输出自动转义。</p>
</div>

<ul class="mdui-list mdui-shadow-2 mdui-m-x-2">
    <li class="mdui-list-item"><i class="mdui-icon material-icons">&#xe322;</i>&nbsp;当前版本
        <div class="mdui-list-item-content mdui-text-right">Ver <?= e($version) ?></div>
    </li>
    <li class="mdui-list-item"><i class="mdui-icon material-icons">&#xe2bd;</i>&nbsp;最新版本
        <div class="mdui-list-item-content mdui-text-right"><?= e($update['latest_version'] ?? '获取失败') ?></div>
    </li>
    <li class="mdui-list-item"><i class="mdui-icon material-icons">&#xe80b;</i>&nbsp;PHP 版本
        <div class="mdui-list-item-content mdui-text-right"><?= e($phpVersion) ?></div>
    </li>
    <li class="mdui-list-item"><i class="mdui-icon material-icons">&#xe6c4;</i>&nbsp;MySQL 版本
        <div class="mdui-list-item-content mdui-text-right"><?= e($mysqlVersion) ?></div>
    </li>
    <li class="mdui-list-item"><i class="mdui-icon material-icons">&#xe875;</i>&nbsp;服务器
        <div class="mdui-list-item-content mdui-text-right"><?= e($serverSoftware) ?></div>
    </li>
</ul>

<?php if (!empty($update['update_content'])): ?>
<div class="mdui-card mdui-m-a-2">
    <div class="mdui-card-primary">
        <div class="mdui-card-primary-title">更新日志（<?= e($update['update_time'] ?? '') ?>）</div>
    </div>
    <div class="mdui-card-content"><?= nl2br(e($update['update_content'])) ?></div>
    <?php if (!empty($update['publish_page'])): ?>
    <div class="mdui-card-actions">
        <a href="<?= e($update['publish_page']) ?>" target="_blank" rel="noopener" class="mdui-btn mdui-ripple">前往查看</a>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>
