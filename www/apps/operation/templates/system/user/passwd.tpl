<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AOS | EditPass - 自动化运维系统 | 改密</title>
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
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    修改密码
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">请填写您的旧密码和新密码</p>
      <div class="form-group input-group">
        <span class="input-group-addon"><i class="fa fa-unlock"></i></span>
        <input type="password" class="form-control oldpasswd" placeholder="Current Password" autocomplete="off">
      </div>
      <div class="form-group input-group">
        <span class="input-group-addon"><i class="fa fa-key"></i></span>
        <input type="password" class="form-control newpasswd" placeholder="New Password" autocomplete="off">
      </div>
      <div class="row">
        <!-- /.col -->
        <div class="col-xs-offset-2 col-xs-8">
          <button type="submit" class="btn btn-primary btn-block btn-flat" id="editpass-submit-btn">确定修改</button>
        </div>
        <!-- /.col -->
      </div>
    <a href="javascript:history.go(-1);"><i class="fa fa-chevron-circle-left"></i> 返回</a>
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.0 -->
<script src="http://{$IMG_DOMAIN}/js/jquery-2.2.0.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="http://{$IMG_DOMAIN}/js/bootstrap/bootstrap.min.js"></script>
<script>
$(function() {
    $('.oldpasswd').focus();
    $('#editpass-back-btn').click(function() {
        history.go(-1);
    });
    $('#editpass-submit-btn').click(function() {
        var uid = '{$uid}' * 1;
        if (!uid) {
            alert('请先登录');
            return false;
        }
        var oldpasswd = $('.oldpasswd').val();
        var newpasswd = $('.newpasswd').val();
        $.post('/rest/user/item/' + uid, {
            'method': 'PUT',
            'put_style': 'passwd',
            'update': {
                'oldpasswd': oldpasswd,
                'newpasswd': newpasswd
            }
        }, function(data, status) {
            if (data.errno) {
                alert('操作失败：'+data.error);
            } else {
                alert('密码修改成功,请重新登录.')
                window.location.href = '/';
            }
        }, 'json');
    });
});
</script>
</body>
</html>
