<?php
/**
 * 安装向导 - 完成页
 *
 * @var string $title
 */
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<title><?= e($title ?? '安装完成') ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
  body { margin:0; font-family:-apple-system,"Microsoft YaHei",sans-serif; background:linear-gradient(135deg,#56ab2f,#a8e063); min-height:100vh; display:flex; align-items:center; justify-content:center; padding:20px; }
  .card { max-width:480px; background:#fff; border-radius:12px; box-shadow:0 10px 40px rgba(0,0,0,.2); padding:32px; text-align:center; }
  .icon { font-size:56px; }
  h1 { color:#2e7d32; margin:8px 0; }
  p { color:#555; line-height:1.7; }
  .btns { margin-top:22px; }
  a.btn { display:inline-block; margin:6px; padding:10px 22px; border-radius:6px; text-decoration:none; color:#fff; background:#3b4a6b; }
  a.btn.green { background:#2e7d32; }
  .tip { margin-top:18px; font-size:12px; color:#999; }
</style>
</head>
<body>
<div class="card">
  <div class="icon">✅</div>
  <h1>安装完成</h1>
  <p>数据库已初始化，配置文件已生成。<br>出于安全，安装向导已自动关闭（再次访问将跳转首页）。</p>
  <div class="btns">
    <a class="btn green" href="<?= e(base_url('/')) ?>">前往首页</a>
    <a class="btn" href="<?= e(base_url('/admin')) ?>">进入后台</a>
  </div>
  <p class="tip">建议核对 config/config.php；生产环境请保持 app.debug = false。</p>
</div>
</body>
</html>
