<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>AOS - 自动化运维系统</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link href="http://{$IMG_DOMAIN}/css/bootstrap/bootstrap.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="http://{$IMG_DOMAIN}/css/font-awesome/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="http://{$IMG_DOMAIN}/css/ionicons/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="http://{$IMG_DOMAIN}/css/adminLTE/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="http://{$IMG_DOMAIN}/css/adminLTE/skins/_all-skins.min.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- jQuery 2.2.0 -->
    <script src="http://{$IMG_DOMAIN}/js/jquery-2.2.0.min.js"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="http://{$IMG_DOMAIN}/js/bootstrap/bootstrap.js"></script>
    <!-- layer 2.3 -->
    <script src="http://{$IMG_DOMAIN}/js/plugins/layer.js"></script>
    <!-- js for app -->
    <script src="http://{$IMG_DOMAIN}/js/base.js"></script>
    <!-- FastClick -->
    <!-- if you will adapt mobile
      <script src="//cdn.bootcss.com/fastclick/1.0.6/fastclick.min.js"></script>
    -->
    <!-- AdminLTE App -->
    <script src="http://{$IMG_DOMAIN}/js/adminLTE/app.min.js"></script>
    <style>
        .color-palette {
            height: 35px;
            line-height: 35px;
            text-align: center;
        }

        .color-palette-set {
            margin-bottom: 15px;
        }

        .color-palette span {
            display: none;
            font-size: 12px;
        }

        .color-palette:hover span {
            display: block;
        }

        .color-palette-box h4 {
            position: absolute;
            top: 100%;
            left: 25px;
            margin-top: -40px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 12px;
            display: block;
            z-index: 7;
        }
        .alert-box {
          margin-bottom: 0px;
          min-width: 260px;
          min-height: 150px;
          display: none;
        }
        .wp-3 {
            padding-left: 3px;
            padding-right: 3px;
        }
        .bp-30 {
            padding-bottom: 30px;
        }
        .query-header {
            line-height: 45px;
        }
        .lh-row-head {
            line-height: 40px;
        }
        /*.layui-layer {
          background-color: transparent;
        }
        .layui-layer-shade {
          opacity: 0.5;
        }*/
    </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <header class="main-header">
            <!-- Logo -->
            <a href="/" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><b>A</b>OS</span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b>自动化</b>运维系统</span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top">
                <!-- Sidebar toggle button-->
                <a href="javascript:void(0);" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-fw fa-user"></i> <span class="hidden-xs">{$logininfo.username}</span>
                            </a>
                            <ul class="dropdown-menu" style="width:160px;">
                                <li class="user-body">
                                    <div>
                                        <a href="/system/user/passwd" class="btn text-muted"><i class="fa fa-key"></i>修改密码</a>
                                        <a href="/system/user/logout" class="btn text-muted"><i class="fa fa-sign-out"></i>退出系统</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->

            {if !$navhide && intval($__cur_menus.hidden) == 0 && count($__left_nav) > 0} {$show_nav = 1} {else} {$show_nav = 0} {/if}

            <section class="sidebar" {if !$show_nav} style="display: none;" {/if}>
                <!-- sidebar menu: : style can be found in sidebar.less -->
                {if $show_nav}
                <ul class="sidebar-menu">
                    <!-- <li class="header">MAIN NAVIGATION</li> -->
                    {foreach $__top_menus as $top}
                    <li class="treeview {if $__cur_menus.parent[0]==$top.id}active{/if}">
                        <a href="{$top.url}">
                            <i class="fa {if $top.icon}{$top.icon}{else}fa-folder{/if}"></i> <span>{$top.text}</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        {if count($__left_nav[$top.id])}
                        <ul class="treeview-menu level-2">
                            {foreach $__left_nav[$top.id] as $nav}
                            <li{if $nav.active} class="active" {/if}>
                                <a href="{$nav.url}"><i class="fa {if $nav.icon}{$nav.icon}{else}fa-circle-o{/if}"></i> {$nav.text}</a>
                                {if count($nav.sub)}
                                <ul class="treeview-menu level-3">
                                    {foreach $nav.sub as $sub}
                                    <li{if $sub.active} class="active" {/if}>
                                        <a href="{$sub.url}"><i class="fa {if $sub.icon}{$sub.icon}{else}fa-circle-o{/if}"></i> {$sub.text}</a>
                                        {if count($sub.sub)}
                                        <ul class="treeview-menu level-4">
                                            {foreach $sub.sub as $last}
                                            <li{if $last.active} class="active" {/if}>
                                                <a href="{$last.url}"><i class="fa fa-circle-o"></i> {$last.text}</a>
                                            </li>
                                            {/foreach}
                                        </ul>
                                        {/if}
                                    </li>
                                    {/foreach}
                                </ul>
                                {/if}
                            </li>
                          {/foreach}
                        </ul>
                    {/if}
                    </li>
                    {/foreach}
                    <!-- <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>Important</span></a></li>
        <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>Warning</span></a></li>
        <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>Information</span></a></li> -->
                </ul>
                {/if}
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <section class="content-header">
                {if $__cur_menus.text}
                <h1>{$__cur_menus.text}</h1>{/if}
                <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">UI</a></li>
        <li class="active">General</li>
      </ol> -->
            </section>
            <section class="content">
