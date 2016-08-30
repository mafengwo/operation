<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation system, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: jichen zhou & lfbear.
 */

namespace apps\render;

class Mweb extends Mbase
{
    private $dynamic = array();//附件模板变量

    public function vSetCommon(array $dynamic = array())
    {
        $this->dynamic = $dynamic;
    }

    public function sRender()
    {
        $loginApi = new \apps\user\MloginApi();
        $uid = $loginApi->iGetLoginUid();
        $userApi = new \apps\user\MuserApi();
        $logininfo = $userApi->getUser($uid);

        $app = new \apps\system\MApp();

        $header = new \Ko_View_Smarty();
        $footer = new \Ko_View_Smarty();

        $header->vAssignHtml('IMG_DOMAIN', IMG_DOMAIN);
        $header->vAssignHtml('WWW_DOMAIN', WWW_DOMAIN);
        $header->vAssignHtml('PASSPORT_DOMAIN', PASSPORT_DOMAIN);
        $header->vAssignHtml('logininfo', $logininfo);
        list($top_menus, $cur_menu, $left_nav) = $app->aGetNavData();
        $header->vAssignHtml('__top_menus', $top_menus);
        $header->vAssignHtml('__cur_menus', $cur_menu);
        $header->vAssignRaw('__left_nav', $left_nav);
        $header->vAssignRaw('is_super', $super_admin);

        $footer->vAssignHtml('IMG_DOMAIN', IMG_DOMAIN);
        $footer->vAssignHtml('WWW_DOMAIN', WWW_DOMAIN);
        $footer->vAssignHtml('PASSPORT_DOMAIN', PASSPORT_DOMAIN);

        foreach ($this->dynamic as $k => $v) {
            $prefix = substr($k, 0, 6);
            if (in_array($prefix, array('footer', 'header'))) {
                $key = substr($k, 7);
                $$prefix->vAssignHtml($key, $v);
            }
        }
        //权限检查 首页默认均可访问
        if ($app->bAccess($logininfo['id']) || \apps\system\MFunc::sGetCurUri() == '/') {
            $body = parent::sRender();
        } else {
            $warnning = new \Ko_View_Smarty();
            $warnning->vAssignHtml('message','您没有这个节点的操作权限。如有需求，请向管理员请求授权。');
            $body = $warnning->sFetch('common/forbidden.tpl');
        }

        return $header->sFetch('common/header.tpl').$body.
                $footer->sFetch('common/footer.tpl');
    }
}
