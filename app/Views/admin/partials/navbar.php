<?php
/**
 * 后台导航栏 + 抽屉菜单
 */
?>
<header class="mdui-appbar mdui-appbar-fixed">
    <div class="mdui-toolbar mdui-color-black">
        <span class="mdui-btn mdui-btn-icon mdui-ripple mdui-ripple-white" mdui-drawer="{target: '#menu', swipe: true}"><i class="mdui-icon material-icons">menu</i></span>
        <a href="<?= e(base_url('/admin')) ?>" class="mdui-typo-headline mdui-hidden-xs">学生电子成绩管理系统</a>
        <div class="mdui-toolbar-spacer"></div>
        <a href="<?= e(base_url('/admin/logout')) ?>" class="mdui-btn mdui-btn-icon" mdui-tooltip="{content: '退出'}"><i class="mdui-icon material-icons">exit_to_app</i></a>
    </div>
</header>
<div class="mdui-drawer" id="menu">
    <div class="mdui-list"><br><br>
        <a href="<?= e(base_url('/admin')) ?>" class="mdui-list-item mdui-ripple">
            <i class="mdui-menu-item-icon mdui-icon material-icons">help_outline</i>
            <div class="mdui-list-item-content">关于管理系统</div>
        </a>
        <a href="<?= e(base_url('/admin/students')) ?>" class="mdui-list-item mdui-ripple">
            <i class="mdui-menu-item-icon mdui-icon material-icons">face</i>
            <div class="mdui-list-item-content">学生信息</div>
        </a>
        <a href="<?= e(base_url('/admin/students/create')) ?>" class="mdui-list-item mdui-ripple">
            <i class="mdui-menu-item-icon mdui-icon material-icons">library_add</i>
            <div class="mdui-list-item-content">添加学生</div>
        </a>
    </div>
</div>
