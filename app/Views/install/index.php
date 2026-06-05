<?php
/**
 * 安装向导 - 表单页（独立页面，内联样式，不依赖配置 / 外网 / 主题）
 *
 * @var string      $title
 * @var string|null $error
 * @var array|null  $old
 */
$old     = $old ?? [];
$db      = $old['db'] ?? [];
$appName = $old['appName'] ?? '学生电子成绩查询系统';
$theme   = $old['theme'] ?? 'default';
$baseUrl = $old['baseUrl'] ?? '';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<title><?= e($title ?? '安装向导') ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
  * { box-sizing: border-box; }
  body { margin:0; font-family:-apple-system,"Microsoft YaHei",sans-serif; background:linear-gradient(135deg,#6a82fb,#fc5c7d); min-height:100vh; padding:40px 16px; }
  .card { max-width:560px; margin:0 auto; background:#fff; border-radius:12px; box-shadow:0 10px 40px rgba(0,0,0,.2); overflow:hidden; }
  .card > h1 { margin:0; padding:22px; font-size:19px; background:#3b4a6b; color:#fff; text-align:center; }
  .body { padding:24px; }
  .group-title { margin:18px 0 6px; font-size:14px; color:#3b4a6b; font-weight:bold; border-left:3px solid #6a82fb; padding-left:8px; }
  label { display:block; font-size:13px; color:#555; margin:10px 0 4px; }
  input[type=text], input[type=password], select { width:100%; padding:10px; border:1px solid #ddd; border-radius:6px; font-size:14px; }
  .row { display:flex; gap:12px; }
  .row > div { flex:1; }
  .hint { font-size:12px; color:#999; margin-top:4px; }
  .checkbox { margin-top:12px; font-size:14px; color:#555; }
  .error { background:#fdecea; color:#b71c1c; padding:10px 12px; border-radius:6px; font-size:13px; margin-bottom:8px; }
  button { width:100%; margin-top:20px; padding:12px; border:0; border-radius:6px; background:#3b4a6b; color:#fff; font-size:16px; cursor:pointer; }
  button:hover { background:#2c3950; }
</style>
</head>
<body>
<div class="card">
  <h1>学生电子成绩查询系统 · 安装向导</h1>
  <div class="body">
    <?php if (!empty($error)): ?><div class="error"><?= e($error) ?></div><?php endif; ?>

    <!-- 省略 action，提交到当前 URL，避免子目录部署时路径出错 -->
    <form method="post">
      <?= csrf_field() ?>

      <div class="group-title">数据库（仅支持 MySQL）</div>
      <div class="row">
        <div>
          <label>主机</label>
          <input type="text" name="db_host" value="<?= e($db['host'] ?? 'localhost') ?>">
        </div>
        <div style="max-width:130px;">
          <label>端口</label>
          <input type="text" name="db_port" value="<?= e($db['port'] ?? 3306) ?>">
        </div>
      </div>
      <label>数据库名</label>
      <input type="text" name="db_name" value="<?= e($db['name'] ?? 'beautiful_report_card') ?>">
      <div class="hint">不存在时将自动创建（utf8mb4）；仅限字母、数字、下划线。</div>
      <div class="row">
        <div>
          <label>用户名</label>
          <input type="text" name="db_user" value="<?= e($db['user'] ?? 'root') ?>">
        </div>
        <div>
          <label>密码</label>
          <input type="password" name="db_pass" value="<?= e($db['pass'] ?? '') ?>">
        </div>
      </div>
      <label>数据表名</label>
      <input type="text" name="db_table" value="<?= e($db['table'] ?? 'result') ?>">

      <div class="group-title">站点</div>
      <label>站点名称</label>
      <input type="text" name="app_name" value="<?= e($appName) ?>">
      <div class="row">
        <div>
          <label>前台主题</label>
          <select name="app_theme">
            <option value="default"<?= $theme === 'default' ? ' selected' : '' ?>>默认透明主题</option>
            <option value="wechat"<?= $theme === 'wechat' ? ' selected' : '' ?>>微信简洁主题</option>
          </select>
        </div>
        <div>
          <label>子目录路径（可空）</label>
          <input type="text" name="base_url" value="<?= e($baseUrl) ?>" placeholder="如 /Beautiful-Report-Card">
        </div>
      </div>

      <div class="group-title">后台管理</div>
      <label>后台登录密码（至少 6 位）</label>
      <input type="password" name="admin_password" placeholder="安装后用它登录 /admin" autocomplete="new-password">

      <button type="submit">开始安装</button>
    </form>
  </div>
</div>
</body>
</html>
