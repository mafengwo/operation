<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AOS | Lockscreen - 自动化运维系统 | 锁屏</title>
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
<body class="hold-transition lockscreen">
<!-- Automatic element centering -->
<div class="lockscreen-wrapper">
  <div class="lockscreen-logo">
    <a href="/"><b>A</b>OS</a>
  </div>
  <!-- User name -->
  <div class="lockscreen-name">{$logininfo.username}</div>

  <!-- START LOCK SCREEN ITEM -->
  <div class="lockscreen-item">
    <!-- lockscreen image -->
    <div class="lockscreen-image">
      <img src="http://{$IMG_DOMAIN}/img/face.png">
    </div>
    <!-- /.lockscreen-image -->

    <!-- lockscreen credentials (contains the form) -->
    <form class="lockscreen-credentials">
      <div class="input-group">
        <input type="password" class="form-control" id="unlock_pass" placeholder="password" data-user="{$logininfo.username}">
        <div class="input-group-btn">
          <button type="button" class="btn btn-unlock"><i class="fa fa-arrow-right text-muted"></i></button>
        </div>
      </div>
    </form>
    <!-- /.lockscreen credentials -->

  </div>
  <!-- /.lockscreen-item -->
  <div class="help-block text-center">
    输入密码解除锁定
  </div>
  <div class="text-center">
    <a href="/">或者更换为另外一位用户身份</a>
  </div>
  <div class="lockscreen-footer text-center">
    Copyright &copy; {$smarty.now|date_format:'%Y'} <b>Mafengwo Operation Department</b><br>
    All rights reserved
  </div>
</div>
<!-- /.center -->

<!-- jQuery 2.2.0 -->
<script src="http://{$IMG_DOMAIN}/js/jquery-2.2.0.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="http://{$IMG_DOMAIN}/js/bootstrap/bootstrap.min.js"></script>
<script>
$(function(){

  function getUrlParam(name){
    //构造一个含有目标参数的正则表达式对象
    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
    //匹配目标参数
    var r = window.location.search.substr(1).match(reg);
    //返回参数值
    if (r!=null) return unescape(r[2]);
    return null;
  }

  $('.btn-unlock').click(function() {
      var unlock_elem = $('#unlock_pass');
      if (unlock_elem.val() && unlock_elem.data('user')) {
          $.post('/rest/user/login/', {
              'update': {
                  'username': unlock_elem.data('user'),
                  'passwd': unlock_elem.val()
              }
          }, function(data, status) {
              if (data.errno) {
                  alert('操作失败：'+data.error);
              } else {
                var back = getUrlParam('back');
                window.location.href = back ? back : '/';
              }
          }, 'json');
      }
  });
});
</script>
</body>
</html>
