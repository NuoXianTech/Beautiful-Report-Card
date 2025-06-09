
<p align="center">
  <a href="https://github.com/nuoxi4n"><img alt="作者" src="https://img.shields.io/badge/Author-nuoxi4n-blueviolet"></a>
  <a href="https://github.com/NuoXianTech/Beautiful-Report-Card"><img alt="文件大小" src="https://img.shields.io/github/repo-size/NuoXianTech/Beautiful-Report-Card"></a>
  <a href="https://github.com/NuoXianTech/Beautiful-Report-Card/blob/master/LICENSE"><img alt="GitHub license" src="https://img.shields.io/github/license/NuoXianTech/Beautiful-Report-Card"></a>
  <a href="https://github.com/NuoXianTech/Beautiful-Report-Card/stargazers"><img alt="GitHub stars" src="https://img.shields.io/github/stars/NuoXianTech/Beautiful-Report-Card?style=social"></a>
</p>

## 学生电子成绩查询系统
【学习项目】使用PHP+MYSQL开发的学生电子成绩查询系统，输入学号和身份证号即可查询信息，欢迎体验。
### 游客访问
![](https://profile-counter.glitch.me/Beautiful-Report-Card/count.svg)
### 默认主题
![](https://mpimg.cn/view.php/e6c7e09d7f463caa421384a871c2b002.png)
![](https://mpimg.cn/view.php/2fb50a27410530ce40cf606ab79cd7c3.png)
### 微信主题
![](https://mpimg.cn/view.php/cbe2b8fdc0c7254cb2c76e6d59548f1b.png)
![](https://mpimg.cn/view.php/e897469a090a17214f66807ab616d2d5.png)
### 后台展示
![](https://mpimg.cn/view.php/b92d74347a3c571abc290f1b8f0efcbd.png)
## 全部功能
##### 没打勾的说明正在施工(实现)中~
- [x] 精美的页面
- [x] 自适应页面
    - [x] 1250宽度以下
    - [ ] 1120宽度以下
    - [ ] 768宽度以下
- [x] 简单的请求参数过滤
- [x] 可更换壁纸
- [x] 自定义科目
- [x] 自定义主题
- [ ] 管理成绩后台
- [x] 后台检测更新
## 现有主题
* 默认透明主题
* 微信简洁主题
## 程序好处
* 无需奔波，手机即可查看
* 电子成绩单，查看更方便
* 多种成绩单样式，提高美观程度
## 安装方法
1. 需安装PHP环境，并确保支持MYSQL5.6+ UTF-8编码
2. 下载源码解压到站点根目录
3. 导入`sql/result.sql`至MYSQL
4. 修改`config.php`的数据库配置信息
5. 删除位于`sql/result.sql`的数据库文件
6. 访问站点`http://IP/index.php`
## 目录结构
```
WEB
├─admin
|  └─function                 功能目录
|     └─action                请求目录
|       └─Add.php             添加成绩文件
|       └─Get_latest.php      检测更新文件
|     └─about.php             后台关于页面
|     └─index.php             后台跳转页面
|     └─query.php             学生信息页面
|  └─index.php                后台跳转页面
|  └─login.php                后台登录页面
|  └─logout.php               后台退出页面
|
├─themes                      主题目录
│  └─default                  默认主题
│     └─assets                样式目录
│       └─css                 css目录
│         └─style.css         主题样式
│     └─index.php             主页文件
|
│  └─concise                  微信主题
│     └─index.php             主页文件
│     └─style.css             样式文件
│
├─sql                         数据库目录
│  └─result.sql               数据库文件
│
├──README.md                  描述文件
├──favicon.ico                图标文件
└──config.php                 配置文件
└──index.php                  首页文件
```
## 联系作者
* Blog：https://nxvav.cn/
* E-Mail：nuo_xian@qq.com
* QQ：1428309052
* QQ群：445202136
## Ref
* 功能实现、参考部分：OneIndex
* 网站图标：https://www.iconfont.cn/collections/detail?cid=18409
* 前台svg：https://www.iconfont.cn/collections/detail?cid=1245
* 后台svg：https://www.iconfont.cn/collections/detail?cid=19892
* mdui前端框架：https://www.mdui.org/
