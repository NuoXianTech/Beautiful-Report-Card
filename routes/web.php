<?php
/**
 * 路由定义
 *
 * 本文件在 App::run() 中被引入，$router 为已就绪的路由器实例。
 * 处理器写法 'Controller@method' 会映射到 App\Controllers\Controller->method()。
 *
 * @var Core\Router $router
 */

// ---- 安装向导（仅未安装时可访问，由 Core\App 安装守卫控制）----
$router->get('/install',  'InstallController@show');
$router->post('/install', 'InstallController@install');

// ---- 前台：成绩查询 ----
$router->get('/', 'HomeController@index');   // 查询表单
$router->post('/', 'HomeController@query');  // 提交查询
$router->get('/query', 'HomeController@index');

// ---- 后台 ----
$router->get('/admin/login',  'Admin/AuthController@showLogin');
$router->post('/admin/login', 'Admin/AuthController@login');
$router->get('/admin/logout', 'Admin/AuthController@logout');

$router->get('/admin', 'Admin/DashboardController@index');

$router->get('/admin/students',                  'Admin/StudentController@index');
$router->get('/admin/students/create',           'Admin/StudentController@create');
$router->post('/admin/students',                 'Admin/StudentController@store');
$router->get('/admin/students/{number}/edit',    'Admin/StudentController@edit');
$router->post('/admin/students/{number}',        'Admin/StudentController@update');
$router->post('/admin/students/{number}/delete', 'Admin/StudentController@destroy');
