<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation syetem, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: jichen zhou.
 */

namespace apps\render;

class Mlogin extends Mbase
{
    public function sRender()
    {
        $loginApi = new \apps\user\MloginApi();
        $uid = $loginApi->iGetLoginUid();
        $userApi = new \apps\user\MuserApi();
        $logininfo = $userApi->getUser($uid);
        $head = new \Ko_View_Render_Smarty();
        $head->oSetTemplate('user/header.tpl')
            ->oSetData('IMG_DOMAIN', IMG_DOMAIN)
            ->oSetData('WWW_DOMAIN', WWW_DOMAIN)
            ->oSetData('PASSPORT_DOMAIN', PASSPORT_DOMAIN)
            ->oSetData('logininfo', $logininfo);

        $tail = new \Ko_View_Render_Smarty();
        $tail->oSetTemplate('user/footer.tpl');
        return $head->sRender().parent::sRender().$tail->sRender();
    }
}
