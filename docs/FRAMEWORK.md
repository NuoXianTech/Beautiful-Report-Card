# 轻量 MVC 框架说明

本项目已从原先「过程式、多入口、字符串拼接 SQL」的写法，**正式重构为一个零第三方依赖、手写的轻量 MVC 框架**。旧代码（`index.php`、`init.php`、`config.php`、`admin/`、`themes/`、`inc/`）已全部移除，全部功能运行在新框架上。

设计目标：**每一行都看得懂、零依赖、安全默认**。

---

## 环境要求

- PHP **7.4+**（推荐 8.x）
- PHP 扩展：`pdo_mysql`（必需）、`curl`（可选，用于检查更新）
- MySQL **5.7+**（UTF-8 / utf8mb4）

---

## 目录结构

```
.
├─ public/                       # Web 根目录（DocumentRoot 指向这里）
│  ├─ index.php                  #   唯一入口（Front Controller）
│  ├─ .htaccess                  #   Apache 重写规则
│  ├─ favicon.ico
│  └─ assets/css/                #   default.css / wechat.css
│
├─ core/                         # 框架核心（手写，零依赖）
│  ├─ App.php                    #   应用引导；含版本号常量 App::VERSION
│  ├─ Router.php                 #   路由：method + path → 控制器@方法，支持 {param}
│  ├─ Request.php                #   请求封装（method / path / 输入 / cookie）
│  ├─ Response.php               #   响应封装（html / json / redirect）
│  ├─ Controller.php             #   前台控制器基类（view / redirect / json）
│  ├─ Model.php                  #   模型基类（基于 PDO 预处理）
│  ├─ Database.php               #   PDO 单例 + 预处理（防 SQL 注入）
│  ├─ View.php                   #   视图渲染（主题 + 布局 + 组件）
│  ├─ Config.php                 #   配置容器（点号读取）
│  ├─ Session.php                #   会话封装（HttpOnly/SameSite + CSRF 令牌）
│  ├─ Auth.php                   #   后台登录态（password_hash / session）
│  ├─ Installer.php              #   安装器（建库/建表/生成配置，仅 MySQL）
│  ├─ Http/
│  │  └─ HttpResponseException.php  # 携带响应的异常（鉴权跳转 / 403）
│  └─ helpers.php                #   e / config / view / asset / redirect / csrf_field
│
├─ app/
│  ├─ Controllers/
│  │  ├─ HomeController.php       #   前台：成绩查询
│  │  ├─ InstallController.php    #   安装向导（仅未安装时可用）
│  │  └─ Admin/                   #   后台
│  │     ├─ AdminController.php   #     鉴权基类（构造即校验登录 + CSRF）
│  │     ├─ AuthController.php    #     登录 / 登出
│  │     ├─ DashboardController.php  #  关于 + 系统信息 + 检查更新
│  │     └─ StudentController.php #     学生增删改查
│  ├─ Models/
│  │  └─ Result.php               #   成绩表模型
│  ├─ Services/
│  │  └─ UpdateService.php        #   检查更新（带本地缓存）
│  └─ Views/
│     ├─ layouts/app.php          #   前台 default 共享布局
│     ├─ partials/copyright.php   #   共享组件
│     ├─ default/                 #   前台默认主题（query / result）
│     ├─ wechat/                  #   前台微信主题（含自己的 layouts）
│     ├─ admin/                   #   后台视图（layouts / partials / students）
│     └─ install/                 #   安装向导页面（index / success）
│
├─ config/
│  ├─ config.sample.php           # 配置模板（提交到仓库）
│  └─ config.php                  # 实际配置（被 .gitignore 忽略）
│
├─ routes/web.php                 # 路由定义
├─ storage/{cache,logs}/          # 运行时缓存与日志
├─ tools/make-password.php        # 生成后台密码哈希的小工具
├─ sql/result.sql                 # 建表与演示数据
├─ docs/FRAMEWORK.md              # 本文档
├─ CHANGELOG.md                   # 更新日志
└─ bootstrap.php                  # 框架引导（常量 / 自动加载 / 配置 / 错误处理）
```

---

## 请求生命周期

```
浏览器
  → public/index.php              （唯一入口）
  → bootstrap.php                 （定义常量、注册自动加载、载入配置、错误处理）
  → Core\App::run()
      → 载入 routes/web.php        （注册路由）
      → 安装守卫：未安装 → 跳转 /install；已安装访问 /install → 跳转首页
      → Router::dispatch(Request)  （匹配路径 → 实例化控制器 → 调用方法）
      → 控制器返回 Response（或抛出 HttpResponseException 由 App 捕获）
  → Response::send()              （输出状态码、响应头、响应体）
```

自动加载采用 PSR-4 风格的简易实现（`bootstrap.php`）：`Core\` → `core/`，`App\` → `app/`。

---

## 核心约定

| 关注点 | 位置 | 说明 |
|---|---|---|
| 路由 | `routes/web.php` | `$router->get('/path', 'Controller@method')`，支持 `{param}` |
| 前台控制器 | `app/Controllers/` | 继承 `Core\Controller`，方法**返回** `Response` |
| 后台控制器 | `app/Controllers/Admin/` | 继承 `AdminController`（自动鉴权），方法返回 `Response` |
| 模型 | `app/Models/` | 继承 `Core\Model`，设 `$table`，查询走预处理 |
| 视图 | `app/Views/` | 用 `<?= e($x) ?>` 转义；`$this->layout()` / `$this->partial()` |
| 配置 | `config/config.php` | `config('database.host')` 点号读取 |
| 版本号 | `Core\App::VERSION` | 由框架维护，**不在** config 中 |

---

## 路由一览

| 方法 | 路径 | 处理器 | 说明 |
|---|---|---|---|
| GET / POST | `/install` | `InstallController@show / install` | 安装向导（仅未安装时可用） |
| GET | `/` | `HomeController@index` | 查询表单 |
| POST | `/` | `HomeController@query` | 提交查询 |
| GET | `/admin/login` | `Admin/AuthController@showLogin` | 登录页 |
| POST | `/admin/login` | `Admin/AuthController@login` | 登录处理 |
| GET | `/admin/logout` | `Admin/AuthController@logout` | 登出 |
| GET | `/admin` | `Admin/DashboardController@index` | 关于 + 系统信息 |
| GET | `/admin/students` | `Admin/StudentController@index` | 学生列表 |
| GET | `/admin/students/create` | `Admin/StudentController@create` | 添加表单 |
| POST | `/admin/students` | `Admin/StudentController@store` | 保存新增 |
| GET | `/admin/students/{number}/edit` | `Admin/StudentController@edit` | 编辑表单 |
| POST | `/admin/students/{number}` | `Admin/StudentController@update` | 保存修改 |
| POST | `/admin/students/{number}/delete` | `Admin/StudentController@destroy` | 删除 |

---

## 主题机制

配置 `app.theme`（`default` 或 `wechat`）决定前台主题。视图解析时**先找主题目录、再回退共享目录**：

```
View::make('query')  在主题 default 下 → 先 Views/default/query.php，再 Views/query.php
layout('layouts/app') → 先 Views/<theme>/layouts/app.php，再 Views/layouts/app.php
```

因此切换主题只需改一个配置项；共享的布局、组件放在 `Views/` 根下复用。后台视图固定使用 `admin/` 目录，与前台主题无关。

---

## 安全机制

| 风险 | 框架对策 | 代码位置 |
|---|---|---|
| SQL 注入 | 全程 PDO 预处理 + 绑定参数；列名白名单校验 | `core/Database.php`、`core/Model.php`、`app/Models/Result.php` |
| XSS | 视图输出统一 `e()`（`htmlspecialchars`） | 所有 `app/Views/**` |
| CSRF | 表单 `csrf_field()` + 后台 `verifyCsrf()` 校验；删除改用 POST | `core/Session.php`、`AdminController` |
| 认证 | `password_verify` / `hash_equals` + 服务端 session + 登录后 `session_regenerate_id` | `core/Auth.php` |
| 会话窃取 | 会话 Cookie 设 `HttpOnly` + `SameSite=Lax` | `core/Session.php` |
| 凭据泄露 | `config.php` 被 `.gitignore`，仅提交模板 | `.gitignore`、`config/` |
| 报错外泄 | 调试/生产分离；生产写日志不外显 | `bootstrap.php`、`core/App.php` |
| IP 外泄 | 移除旧版「把访客 IP 外链第三方查询站」的功能 | `Admin/DashboardController` |
| 重复安装 | 已安装（config.php 存在）后安装向导自动失效 | `core/Installer.php`、`core/App.php` |

---

## 如何扩展

### 新增一个前台页面
1. `routes/web.php`：`$router->get('/about', 'AboutController@index');`
2. `app/Controllers/AboutController.php`：
   ```php
   namespace App\Controllers;
   use Core\Controller;
   use Core\Response;

   class AboutController extends Controller
   {
       public function index(): Response
       {
           return $this->view('about', ['title' => '关于']);
       }
   }
   ```
3. `app/Views/default/about.php`：
   ```php
   <?php $this->layout('layouts/app'); ?>
   <h1><?= e($title) ?></h1>
   ```

### 新增一个后台页面
控制器继承 `AdminController` 即自动要求登录；POST 动作记得在视图表单里加 `<?= csrf_field() ?>` 并在方法首行调用 `$this->verifyCsrf()`。

---

## 后台使用

- 入口：`/admin`，未登录会跳转到 `/admin/login`。
- 默认密码：`admin123`（见 `config/config.php` 的 `admin.password`）。**请尽快修改**。
- 更安全的方式：生成哈希并清空明文：
  ```bash
  php tools/make-password.php 你的新密码
  # 将输出填入 config/config.php 的 admin.password_hash，并把 admin.password 留空
  ```
- 功能：关于 / 系统信息（含版本、PHP / MySQL 版本、检查更新）、学生信息列表、添加、编辑、删除。

---

## 安装与运行

推荐用**内置安装向导**完成初始化（仅支持 MySQL）：

1. 将 Web 根目录指向 `public/` 并启用重写，或本地启动：
   ```bash
   php -S localhost:8000 -t public public/index.php
   ```
2. 访问站点 → 自动跳转 `/install` → 填写 MySQL 连接、站点信息、后台密码 → 点「开始安装」。
   向导会自动建库、建表，并生成 `config/config.php`（需 `config/` 目录可写）。
3. 安装完成后向导自动失效，前台与后台即可使用。

**部署方式**

- **Apache**：`DocumentRoot` 指向 `public/`，启用 `mod_rewrite`（`.htaccess` 已就绪）。
- **Nginx**：`root` 指向 `public/`，非真实文件请求 `try_files $uri /index.php`。
- **子目录**：安装向导的「子目录路径」填 `/Beautiful-Report-Card`，或安装后在 `config.php` 设 `'base_url'`。
  注意：子目录部署建议先用根路径完成安装（安装阶段尚无 base_url 配置）。

> 手动安装（跳过向导）：复制 `config/config.sample.php` 为 `config/config.php` 并填写，再导入 `sql/result.sql`。

---

## 验证清单

0. 首次访问任意页面（尚无 `config.php`）→ 自动跳转 `/install`；填表提交后自动建库建表并生成配置。
1. 安装完成后访问 `/admin` → 用安装时设置的密码登录 → 添加一名学生。
2. 访问 `/` → 用刚添加学生的考生号 + 身份证后六位查询 → 显示成绩表格。
3. 留空或乱填 → 友好错误提示（不会发生 SQL 注入）。
4. 后台编辑 / 删除学生 → 列表实时变化；删除带二次确认与 CSRF 校验。
5. 把 `config.php` 的 `app.theme` 改为 `wechat` → 前台切换为微信主题。

> 如需演示数据，可手动导入 `sql/result.sql`（考生 202101 / 202102）。
> 注：本框架未随附自动化测试；以上为手动验证步骤。
