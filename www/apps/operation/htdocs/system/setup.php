<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation system, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\operation\system;

\Ko_Web_Route::VGet('install', function () {
    if(KO_DB_HOST) {
        exit('Already installed.');
    }
    $render = new \apps\render\Mbase();
    $render->oSetTemplate('system/setup/install.tpl')
    ->oSetData('IMG_DOMAIN', IMG_DOMAIN)
    ->oSetData('WWW_DOMAIN', WWW_DOMAIN)
    ->oSend();
});
