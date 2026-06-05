
<p align="center">
  <a href="https://github.com/nuoxi4n"><img alt="作者" src="https://img.shields.io/badge/Author-nuoxi4n-blueviolet"></a>
  <a href="https://github.com/NuoXianTech/Beautiful-Report-Card"><img alt="文件大小" src="https://img.shields.io/github/repo-size/NuoXianTech/Beautiful-Report-Card"></a>
  <a href="https://github.com/NuoXianTech/Beautiful-Report-Card/blob/master/LICENSE"><img alt="GitHub license" src="https://img.shields.io/github/license/NuoXianTech/Beautiful-Report-Card"></a>
  <a href="https://github.com/NuoXianTech/Beautiful-Report-Card/stargazers"><img alt="GitHub stars" src="https://img.shields.io/github/stars/NuoXianTech/Beautiful-Report-Card?style=social"></a>
</p>

## 学生电子成绩查询系统

【学习项目】使用 PHP + MySQL 开发的学生电子成绩查询系统，输入考生号与身份证号后六位即可查询成绩。

## 功能特性
- 🎨 精美自适应前台，`default` / `wechat` 双主题，改配置即可切换
- 🔍 考生号 + 身份证号后六位查询成绩
- 🛠 后台管理：登录、学生信息增删改查、系统信息、检查更新（带缓存）
- 🔒 安全默认：PDO 预处理防注入、输出转义防 XSS、CSRF 防护、`password_hash` + 会话登录
- 🧩 轻量 MVC 框架：单一入口 + 路由 + 控制器 / 模型 / 视图；科目用配置数组统一管理

## 项目展示

<table width="100%">
  <tr>
    <td width="50%"><img src="https://mpimg.cn/view.php/e6c7e09d7f463caa421384a871c2b002.png" alt="image" border="0"></td>
    <td width="50%"><img src="https://mpimg.cn/view.php/2fb50a27410530ce40cf606ab79cd7c3.png" alt="image edit" border="0"></td>
  </tr>
  <tr>
    <td width="50%"><img src="https://mpimg.cn/view.php/cbe2b8fdc0c7254cb2c76e6d59548f1b.png" alt="chery studio" border="0"></td>
    <td width="50%"><img src="https://mpimg.cn/view.php/e897469a090a17214f66807ab616d2d5.png" alt="account pool" border="0"></td>
  </tr>
  <tr>
    <td width="50%"><img src="https://mpimg.cn/view.php/b92d74347a3c571abc290f1b8f0efcbd.png" alt="new api" border="0"></td>
  </tr>
</table>

## 现有主题
* 默认透明主题（`default`）
* 微信简洁主题（`wechat`）

## 环境要求
* PHP **7.4+**（需 `pdo_mysql`、`curl` 扩展）
* MySQL **5.7+**（UTF-8 / utf8mb4）

## 安装方法
本项目内置 **Web 安装向导**，无需手动改配置或导数据库。

1. 下载源码，将站点根目录（DocumentRoot）指向 **`public/`** 并启用 URL 重写；本地可快速启动：
   ```bash
   php -S localhost:8000 -t public public/index.php
   ```
2. 浏览器访问站点，会**自动跳转到安装向导** `/install`。
3. 在向导中填写 MySQL 连接、站点信息、后台密码，点击「开始安装」——
   向导会自动建库、建表并生成 `config/config.php`。
4. 安装完成后向导自动关闭：前台即可查询，后台 `/admin` 用刚设置的密码登录。

> 仅支持 MySQL；需保证 `config/` 目录可写（用于生成配置）。进阶用户也可手动复制 `config/config.sample.php` 为 `config/config.php` 跳过向导。

> Apache / Nginx / 子目录部署的详细说明见 [docs/FRAMEWORK.md](docs/FRAMEWORK.md)。

## 目录结构
```
WEB
├─ public/        Web 根目录（唯一入口 index.php、.htaccess、assets、favicon）
├─ core/          框架核心（路由 / 请求 / 响应 / 控制器 / 模型 / 数据库 / 视图 / 会话 / 鉴权）
├─ app/           业务代码（Controllers / Models / Services / Views）
├─ config/        配置（config.sample.php 模板，config.php 实际配置不入库）
├─ routes/        路由定义 web.php
├─ storage/       运行时缓存与日志
├─ tools/         实用脚本（make-password.php）
├─ sql/           建表与演示数据
├─ docs/          文档（FRAMEWORK.md）
└─ bootstrap.php  框架引导
```
> 完整结构与各组件职责见 [docs/FRAMEWORK.md](docs/FRAMEWORK.md)。

## 文档
* 框架说明：[docs/FRAMEWORK.md](docs/FRAMEWORK.md)
* 更新日志：[CHANGELOG.md](CHANGELOG.md)

## 联系作者
* Blog：https://nxvav.cn
* E-Mail：nuo_xian@qq.com
* QQ：1428309052
* QQ群：445202136

## Ref
* 网站图标：https://www.iconfont.cn/collections/detail?cid=18409
* 前台 svg：https://www.iconfont.cn/collections/detail?cid=1245
* 后台 svg：https://www.iconfont.cn/collections/detail?cid=19892
* mdui 前端框架：https://www.mdui.org/
