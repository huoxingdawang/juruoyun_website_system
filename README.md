# 蒟蒻云网页系统
![](http://www.juruoyun.top/jry_wb/jry_wb_netdisk/jry_nd_do_file.php?action=open&share_id=4&file_id=15)

-------------

[TOC]
## 介绍
[先来个连接](http://www.juruoyun.top)

[再来个连接(开发版)](http://dev.juruoyun.top)

蒟蒻云网站系统是由蒟蒻云开发组yy的一套网站系统

以其非常丑的界面闻名于世

大概长这样？？？
![](http://www.juruoyun.top/jry_wb/jry_wb_netdisk/jry_nd_do_file.php?action=open&share_id=5&file_id=18)

虽然开发组一直在~~努力~~美化界面，但似乎没什么进展。。。

这个项目以[码云](https://gitee.com/huoxingdawang/juruoyun_website_system)为主要开发阵地，同步托管于[github](https://github.com/huoxingdawang/juruoyun_website_system)

## 一些帮助文档
[错误代码大全](http://www.juruoyun.top/jry_wb/jry_wb_blog/jry_wb_blog_show.php?reload=1&blog_id=5)

[操作代码大全](http://www.juruoyun.top/jry_wb/jry_wb_blog/jry_wb_blog_show.php?reload=1&blog_id=6)

[蒟蒻云美化计划](http://www.juruoyun.top/jry_wb/jry_wb_blog/jry_wb_blog_show.php?reload=1&blog_id=2)

## 魔改版及第三方使用案例
[青岛二中2019HIMUN模联报名](http://himun.info:2019)
![himun](http://www.juruoyun.top/jry_wb/jry_wb_netdisk/jry_nd_do_file.php?action=open&share_id=5&file_id=17)

## 部署指北
### 环境依赖
1. PHP7.2
2. MYSQL5.7.26
3. UBUNTU 18LTS
4. 硬盘缓存啥的随意。。。

### 操作过程
#### 先导
##### ubuntu18LTS安装
略。。。
##### 开启openssh
这个东西可以帮助你远程连接
使用如下代码
```bash
sudo apt-get install openssh-server
```
然后确认ssh是否启动
```bash
ps -e | grep ssh
```
正常情况下
应该会有一个标成红色的sshd出现
这样子ssh服务端就启动好了
##### 设置固定IP
先运行
```bash
cd /etc/netplan/
ls
```
应该会有一个后缀为**.yaml**的文件，打开
```bash
sudo nano 01-netcfg.yaml
```
大概这个样子
```bash
# This file describes the network interfaces available on your system
# For more information, see netplan(5).
network:
  version: 2
  renderer: networkd
  ethernets:
    eno1:
      dhcp4: yes

```
修改成这个样子
```
network:
  version: 2
  renderer: networkd
  ethernets:
    eno1:
      dhcp4: no
      addresses: [192.168.0.200/24]
      gateway4: 192.168.0.1
      nameservers:
          addresses: [192.168.0.1]

```
使用
```bash
netplan apply
```
更新设置
如果你是用ssh连接，请重新连接
#### 安装APACHE2
```bash
sudo apt-get install apache2
```
现在访问你服务器的地址应该可以看见apache2的宣传页了
顺便开启权限
```bash
sudo chmod 777 /var/www/html
```
#### 安装PHP7.2
```bash
sudo apt-get install php7.2
```
重启APACHE2
```bash
sudo /etc/init.d/apache2 restart
```
创建测试文件
```bash
sudo nano index.php
```
并写入
```php
<?php phpinfo(); ?>
```
现在用浏览器访问你服务器的index.php，应该就可以看见php的信息了
扩展安装
```bash
sudo apt-get install php7.2-dev php7.2-fpm php7.2-mysql php7.2-curl php7.2-gd php7.2-mbstring php7.2-xml php7.2-xmlrpc php7.2-zip
```
重启APACHE2
```bash
sudo /etc/init.d/apache2 restart
```
现在用浏览器访问你服务器的index.php，应该就可以看见php扩展的信息了
==#00ff00完成==
#### mysql安装
```bash
sudo apt-get install mysql-server
```
修改配置文件
```bash
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf
```
把
```
bind-address            = 127.0.0.1
```
改成
```
bind-address            = 0.0.0.0
```
自动配置
```bash
sudo mysql_secure_installation
```
```sql
GRANT UPDATE, TRIGGER, REFERENCES, INSERT, INDEX, EVENT, DROP, DELETE, CREATE VIEW, CREATE TEMPORARY TABLES, CREATE TABLESPACE, CREATE ROUTINE, CREATE, ALTER ROUTINE, ALTER, SHOW VIEW, SHOW DATABASES, SELECT, PROCESS, EXECUTE, CREATE USER,RELOAD, FILE, LOCK TABLES, REPLICATION CLIENT, REPLICATION SLAVE, SHUTDOWN, SUPER  ON *.* TO ''@'%';
GRANT USAGE ON *.* TO 'lijunyan'@'%' WITH GRANT OPTION;
```
重启
```bash
sudo service mysql restart
```
==#00ff00完成==
#### 蒟蒻云配置
上传，导入数据库
```bash
sudo nano /etc/apache2/sites-available/000-default.conf
ErrorDocument 404 /404.php
sudo service mysql restart
```
#REDIS安装
```bash
sudo apt-get install redis
sudo apt-get install php7.2-redis
sudo /etc/init.d/apache2 restart
```


## 功能简介
### 用户信息收集及控制
1. 昵称
2. 性别
3. 惯用语(只做了中文)
4. 主题(然鹅都很丑)
5. 电话(暴力验证+阿里云短信API(控制文件可调))
6. 邮箱(暴力验证+phpemailer(控制文件可调))
7. 签名(markdown支持)
8. 登录控制(长期登录,远程登出)
9. 密码控制(md5加密)
10. 第三方接入(QQ,码云,github,小米)
11. 头像设置(默认,gravatar,四个第三方接入,外部URL,网盘)
12. 隐私控制
12.1. 电话(全码,码中间4位,不码)
12.2. 邮箱(全码,码中间的部分,不码)
12.3. 第三方接入(显示,不显示)
12.4. 登录信息(显示,不显示)
13. 加特技(除了导致浏览器变慢没什么用)
13.1. 鼠标跟随
13.2. 弹幕
13.3. 头像旋转

14. 背景音乐(也没啥用，可以关掉)
14.1. QQ音乐爬虫(歌词，作者，外联等)
14.2. 网易云音乐爬虫(歌词，作者，外联等)
15 邀请码
16. 操作记录
17. 更多的信息(由后台控制文件自动引导生成各页面JS,支持身份证,电话,邮箱等格式检测及去重)


### 管理员中心
1. 基本权限,用户控制

### 网盘
1. 文件上传,下载
1.1. 阿里云OSS
1.1.1. 签名上传
1.1.2. 签名下载
1.2. 服务器直传
1.1.1. 分片上传
1.1.2. 暴力输出下载
2. 分享权限控制
3. “人工”智能鉴黄(没有后台...)

### 小程序(大部分是外联)

### 博客
1. 基本的markdown和代码高亮,暂时不支持latex
2. 网盘文件内联

### 聊天室
1. ajax&websocket连接,面刷新消息展示
2. 消息markdown
3. “人工”智能鉴黄(没有后台...)

### 在线测评
1. 支持的题目类型
1.1. 单选
1.2. 填空
1.3. 单词
1.4. 编译题(暂时不能编译....)
2. 后台题目管理
3. log查询

### 总结
艹我莫名其妙写了这么写代码。。。

## 规范
### 文件规范
1. 所有传到服务器的代码在 /push_dev/
2. 所有的通用JS库在 /dev/js
3. /dev_tools/是开发工具,用于压缩JS
4. /push_dev/jry_wb/jry_wb_tp_sdk 存放第三方SDK
5. /push_dev/jry_wb/jry_wb_tp_callback 存放第三方回调

### 代码规范
1. 遵循全小写下划线分词方法
2. Tab分割是\t而不是4个空格
3. 全局变量和函数使用jry_(项目缩写)_开头(例如网页系统是jry_wb_)
4. 如果if,while,for后只有一个语句,应省略大括号并令开一行书写除非
4.1. 有很多一样的语句时应将if写在一行,并对齐书写,(除非特殊说明,对齐指在tab占4位的notepad++)下对齐
```php
<?php
	if($jry_wb_aaa='aaa')			a();
    else if($jry_wb_aaa='aaaa')		b();
    else if($jry_wb_aaa='ccccccc')	c();
```
5.大括号换行,除非
5.1. 有很多一样的语句,且不能用逗号压缩时,应将if写在一行,并对齐书写,(除非特殊说明,对齐指在tab占4位的notepad++)下对齐
```php
<?php
	if($jry_wb_aaa='aaa')			{a();return;}
    else if($jry_wb_aaa='aaaa')		{b();return;}
    else if($jry_wb_aaa='ccccccc')	{c();return;}
```
6.严禁使用//进行注释,尤其是在/dev/js/目录下(因为代码压缩工具有BUG会直接忽略之后的所有代码...)


## 接下来的目标
受开发组成员全部进入高三影响
咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕咕

1. 分离前后端，前端全静态
2. 前端分层，分成DOM操作和数据处理及信息交互，方便UI重构
3. 后端换C，大工程，咕咕咕

新系统的架构大概这样？？？
相当宏大，由灵魂画师火星大王绘制
![](http://www.juruoyun.top/jry_wb/jry_wb_netdisk/jry_nd_do_file.php?action=open&share_id=5&file_id=20)

## 外部依赖
1. [fonticon](https://www.iconfont.cn)由阿里妈妈出品
2. [ip2region](https://gitee.com/lionsoul/ip2region)一个跑得飞快的IP查询系统
3. [phpemailer]()这个链接找不到了233333
4. [阿里云OSS SDK]()
5. [github oAuth2.0SDK]()
6. [码云 oAuth2.0SDK]()
7. [小米 oAuth2.0SDK]()
8. [QQ oAuth2.0SDK]()

## 完啦
没有啦 嘤嘤嘤 欢迎访问[蒟蒻云](http://www.juruoyun.top)
欢迎交pr