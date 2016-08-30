<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation syetem, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>
 */

namespace apps\operation\server;

//default index at /server/
\Ko_Web_Route::VGet('index', function () {
    $render = new \apps\render\Mweb();
    $render->oSetTemplate('server/index.tpl')
        ->oSend();
});
