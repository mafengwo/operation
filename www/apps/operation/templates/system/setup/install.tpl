<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>AOS | Setup - 自动化运维系统 | 安装</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="http://{$IMG_DOMAIN}/css/bootstrap/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="http://cdn.bootcss.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="http://cdn.bootcss.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="http://{$IMG_DOMAIN}/css/adminLTE/AdminLTE.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
    <style>
        .setup-box {
            margin: auto;
            0;
            #width: 75%;
            width: 600px;
        }

        .progress {
            height: 30px;
        }

        .progress-bar {
            font-size: 14px;
            line-height: 26px;
            font-weight: bold;
        }
    </style>
</head>

<body class="hold-transition login-page">
    <div class="setup-box">
        <div class="login-logo">
            <p style="line-height: 100px;">AOS自动运维系统<b>安装程序</b></p>
        </div>
        <div class="login-box-body form-horizontal">
            <div class="progress">
                <div class="progress-bar progress-bar-yellow progress-bar-striped" style="width: 33%">
                    <p>Step <span id="step_show">1</span> of 3: 服务器关键配置</p>
                </div>
            </div>
            <p class="login-box-msg">请输入以下信息以初始化数据和配置文件</p>
            <div id="step1" style="displayx:none;">
                <div class="form-group">
                    <label class="col-sm-3 control-label">访问域名</label>
                    <div class="col-sm-8 input-group">
                        <span class="input-group-addon">http://</span>
                        <input type="text" class="form-control valid require" placeholder="Domain" data-field="domain" value="{$WWW_DOMAIN}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">数据库主机地址</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control valid require" placeholder="MySQL Host" data-field="mysql_host">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">数据库端口</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control valid" placeholder="MySQL Port" data-field="mysql_port">
                    </div>
                    <div class="col-sm-4">
                        <p>可选, 默认端口3306</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">数据库用户名</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control valid require" placeholder="MySQL User" data-field="mysql_user">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">数据库密码</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control valid require" placeholder="MySQL Pass" data-field="mysql_pass">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">数据库库名</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control valid require" placeholder="MySQL DataBase Name" data-field="mysql_dbname">
                    </div>
                    <div class="col-sm-4">
                        <p>数据库不存在会尝试创建</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Memcache地址</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control valid require" placeholder="Memcache Host:Port" data-field="mc_host">
                    </div>
                    <div class="col-sm-4">
                        <p>缓存使用</p>
                    </div>
                </div>
            </div>
            <div id="step2" style="display:none;">
                <div class="form-group">
                    <label class="col-sm-3 control-label">管理员用户名</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control valid require" placeholder="Admin User" data-field="admin_user">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">管理员密码</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control valid require" placeholder="Admin Pass" data-field="admin_pass">
                    </div>
                </div>
            </div>
            <div id="step3" style="display:none;">
              <div class="small-box bg-green">
                <div class="inner">
                  <h3>100%</sup></h3>
                  <p>恭喜您，AOS安装成功！</p>
                </div>
                <div class="icon">
                  <i class="fa fa-thumbs-o-up"></i>
                </div>
                <a href="/" class="small-box-footer">
                  Enter Now <i class="fa fa-arrow-circle-right"></i>
                </a>
              </div>
            </div>
            <div class="social-auth-links text-center">
                <button class="btn bg-purple btn-flat margin bnt-install">下一步</button>
            </div>
        </div>
        <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->

    <div class="modal fade modal-danger alert-box" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">发生错误</h4>
                </div>
                <div class="modal-body form-login-error">
                    <p>One fine body…</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery 2.2.0 -->
    <script src="http://{$IMG_DOMAIN}/js/jquery-2.2.0.min.js"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="http://{$IMG_DOMAIN}/js/bootstrap/bootstrap.min.js"></script>
    <script>
        $(function() {
            var step = 1;
            var error_modal = function(content) {
                var target = $('.form-login-error');
                target.find('p').empty().append(content.replace("\n",'<br>'));
                $('.alert-box').modal('show');
            }

            $('.bnt-install').click(function() {
                var data = { } , unfilled = [], btn = $(this);
                if(btn.attr('disabled')) return;
                $('.valid:input').css({
                    'background-color': '#ffffff'
                });
                $('#step'+step).find('.valid:input').each(function() {
                    var self = $(this),
                        field = self.data('field'),
                        val = self.val();

                    if (val == '') {
                        self.hasClass('require') && unfilled.push(self);
                    } else {
                        data[field] = val;
                    }
                    data[field] = self.val();
                });
                if (unfilled.length > 0) {
                    error_modal('请将表单中的必要内容填写完整!')
                    for (var i in unfilled) {
                        var item = unfilled[i];
                        item.css({
                            'background-color': '#FFE4E1'
                        });
                    }
                } else {
                    btn.attr('disabled',true);
                    $.post('/rest/user/setup/',{
                        'update': data,
                        'method': 'POST',
                        'after_style': 'default',
                        'post_style': 'step'+step,
                    },function(d){
                        btn.attr('disabled',false);
                        if(d.errno!=0){
                            error_modal(d.error);
                        } else {
                            if(d.data && d.data.after.msg){
                                alert(d.data.after.msg)
                            }
                            $('#step'+step).hide();
                            step = step < 3 ? step + 1 : 3;
                            $('.progress-bar').css({ 'width': 33.34 * step + '%' });
                            $('#step'+step).show();
                            $('#step_show').text(step);
                            step == 3 && btn.hide();
                        }
                    },'json');
                }
            });
        });
    </script>
</body>

</html>
