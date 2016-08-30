<?php

error_reporting(E_ALL^E_NOTICE);

require($_SERVER['DOCUMENT_ROOT'].'/def.php');
require($_SERVER['DOCUMENT_ROOT'].'/../ko/ko.class.php');

Ko_Web_Event::On('ko.config', 'after', function () {
    $appname = Ko_Web_Config::SGetAppName();
    if ('' === $appname) {
        Ko_Web_Response::VSetRedirect('http://' . WWW_DOMAIN);
        Ko_Web_Response::VSend();
        exit;
    }
    if (!Ko_Tool_Safe::BCheckMethod(array('*.' . MAIN_DOMAIN))) {
        Ko_Web_Response::VSetHttpCode(403);
        Ko_Web_Response::VSend();
        exit;
    }
    $templateroot = Ko_Web_Config::SGetValue('templateroot');
    if (strlen($templateroot) && is_dir($templateroot)) {
        define('KO_TEMPLATE_DIR', $templateroot);
    }
    $host = Ko_Web_Request::SHttpHost();
    $script = Ko_Web_Request::SScriptName();
    if (WWW_DOMAIN === $host) {
		$login_ignore = array('/rest/user/','/system/user/','/system/setup/');
        $loginuid = Ko_App_Rest::VInvoke('user', 'GET', 'loginuid/');
		$need_login = true;
		foreach($login_ignore as $v){
			if(stripos($script,$v) === 0){
				$need_login = false;
				break;
			}
		}
		if (empty($loginuid) && $need_login) {
            Ko_Web_Response::VSetRedirect('http://'.WWW_DOMAIN.'/system/user/login');
            Ko_Web_Response::VSend();
            exit;
        }
    }
});

Ko_Web_Event::On('ko.error', '500', function ($errno, $errstr, $errfile, $errline, $errcontext) {
    Ko_Web_Error::V500($errno, $errstr, $errfile, $errline, $errcontext);
    exit;
});

Ko_Web_Event::On('ko.error', 'error', function ($e_code,$e_msg,$e_file,$e_line,$e_con) {
    echo "<p>A error occur in <span style='color:#337ab7;'>{$e_file}</span>
     at line <span style='color:#d9534f;'>{$e_line}</span></p>\n";
    echo "<p>$e_msg</p>";
    echo "\n<!--\nTrace Info:\n\n";
    print_r($e_con);
    echo "-->";
    exit;
});

Ko_Web_Event::On('ko.dispatch', 'before', function () {
});

Ko_Web_Event::On('ko.dispatch', '404', function () {
    Ko_Web_Route::V404();
    exit;
});

require_once(KO_DIR . 'web/Bootstrap.php');
