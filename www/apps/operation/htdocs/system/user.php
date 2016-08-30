<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation syetem, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\operation\system;

\Ko_Web_Route::VGet('login', function () {
    $render = new \apps\render\Mbase();
    $render->oSetTemplate('system/user/login.tpl')
    ->oSetData('IMG_DOMAIN', IMG_DOMAIN)
    ->oSend();
});

\Ko_Web_Route::VGet('register', function () {
    $render = new \apps\render\Mbase();
    $render->oSetTemplate('system/user/register.tpl')
    ->oSetData('IMG_DOMAIN', IMG_DOMAIN)
    ->oSend();
});

\Ko_Web_Route::VGet('logout', function () {
    $api = new \apps\user\MloginApi();
    $api->vSetLoginUid(0);
    \Ko_Web_Response::VSetRedirect('/');
    \Ko_Web_Response::VSend();
});

\Ko_Web_Route::VGet('passwd', function () {
    $api = new \apps\user\MloginApi();
    $uid = $api->iGetLoginUid();
    if ($uid) {
        $render = new \apps\render\Mbase();
        $render->oSetTemplate('system/user/passwd.tpl')
        ->oSetData('IMG_DOMAIN', IMG_DOMAIN)
        ->oSetData('uid', $uid)
        ->oSend();
    } else {
        \Ko_Web_Response::VSetRedirect('/system/user/login');
        \Ko_Web_Response::VSend();
    }
});

\Ko_Web_Route::VGet('lockscreen', function () {
    $api = new \apps\user\MloginApi();
    $uid = $api->iGetLoginUid();
    if ($uid) {
        $userApi = new \apps\user\MuserApi();
        $logininfo = $userApi->getUser($uid);
        $render = new \apps\render\Mbase();
        $api->vSetLoginUid(0);//logout enter lockscreen
        $render->oSetTemplate('system/user/lockscreen.tpl')
        ->oSetData('IMG_DOMAIN', IMG_DOMAIN)
        ->oSetData('logininfo', $logininfo)
        ->oSend();
    } else {
        \Ko_Web_Response::VSetRedirect('/system/user/login');
        \Ko_Web_Response::VSend();
    }
});
