#AOS: Automatic Operation System 自动化运维系统

## 功能说明
 ![image](https://raw.githubusercontent.com/mafengwo/operation/master/Operation.jpg)
- 服务器信息管理
  + 服务节点管理
  + 服务标签管理
  + 远程管理（服务器批量管理）
  + 硬件（资产）管理
- 后台管理框架
  + 菜单（节点）配置
  + 用户管理/授权

## 运行环境
### 典型的LAMP或LNMP 环境，但需要注意以下两点
- php > 7.0
- memcache

### web服务器规则配置 以nginx为例

```
server {
    listen    80;
    server_name operation.yourdomain.com;
    root /operation/www/;
    include fastcgi_params;
    set $path_info "";
    set $real_script_name $fastcgi_script_name;
    if ($fastcgi_script_name ~ "^(.+?\.php)(/.+)$") {
      set $real_script_name $1;
      set $path_info $2;
    }
    fastcgi_param PATH_INFO    $path_info;
    fastcgi_param SCRIPT_NAME   $real_script_name;

    fastcgi_param SCRIPT_FILENAME /operation/www/entry.php;

    gzip  on;
    gzip_min_length  1000;
    gzip_buffers     4 8k;
    gzip_types       application/x-javascript text/css;

    location / {
      if ($request_filename ~* \.php$) {
        fastcgi_pass 127.0.0.1:9000;
        break;
      }
      if (!-f $request_filename) {
        fastcgi_pass 127.0.0.1:9000;
      }
    }

    location ~* \.(ico|gif|bmp|jpg|jpeg|png|swf|js|css|mp3) {
    	expires 7d;
    }
}
```
### 安装脚本

operation.yourdomain.com/system/setup/install
