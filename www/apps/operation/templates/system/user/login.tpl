<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AOS | Login - 自动化运维系统 | 登录</title>
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
    <b>AOS</b> 自动运维系统
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg"><b>登录</b> 请填写您的用户名和密码</p>
      <div class="form-group input-group">
        <span class="input-group-addon"><i class="fa fa-user"></i></span>
        <input type="text" class="form-control login-username" placeholder="User Name">
      </div>
      <div class="form-group input-group">
        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
        <input type="password" class="form-control login-passwd" placeholder="Password">
      </div>
      <div class="row">
        <div class="col-xs-offset-2 col-xs-8">
          <button type="submit" class="btn btn-primary btn-block btn-flat" id="login-submit-btn">登录</button>
        </div>
      </div>
      <div class="social-auth-links text-center">
        <p>- OR -</p>
        <a href="register" class="btn bg-purple btn-flat margin">新用户注册</a>
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
                <button type="button" class="btn btn-outline" data-dismiss="modal">Close</button>
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
  var error_modal = function(content) {
      var target = $('.form-login-error');
      target.find('p').empty().append(content);
      $('.alert-box').modal('show');
  }

  $('.form-login-error').click(function() {
      $(this).fadeOut();
  })

  $("body").keydown(function() {
      if (event.keyCode == "13") {
          $('#login-submit-btn').trigger('click');
      }
  });

  typeof(window.localStorage.loginname)!='undefined' && $('.login-username').val(window.localStorage.loginname);

  $('.login-username').focus();

  $('#login-submit-btn').click(function() {
      var username = $('.login-username').val();
      var passwd = $('.login-passwd').val();
      $.post('/rest/user/login/', {
          'after_style': 'default',
          'update': {
              'username': username,
              'passwd': passwd
          }
      }, function(data, status) {
          if (data.errno) {
              error_modal(data.error);
          } else {
              if(data.data.after && data.data.after.username) {
                  window.localStorage.setItem('loginname',data.data.after.username);
              }
              window.location.href = '/';
          }
      }, 'json');
  });
});
</script>
</body>
</html>
