<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation syetem, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: jichen zhou.
 */

namespace apps\system;

class MApi extends \Ko_Busi_Api
{
    const MC_ALL_MENU = 'operation:menu';

    public function aGetAll()
    {
        $ret = $this->mcacheDao->vGet(self::MC_ALL_MENU);
        if ($ret && false) {
            return \Ko_Tool_Enc::ADecode($ret);
        } else {
            $oOption = new \Ko_Tool_SQL();
            $oOption->oOrderBy('sort desc');
            $infos = $this->dbMenuDao->aGetList($oOption);
            if ($infos) {
                $infos = \Ko_Tool_Utils::AObjs2map($infos, 'id');
                $this->mcacheDao->bSet(self::MC_ALL_MENU, \Ko_Tool_Enc::SEncode($infos), 86400);
            }
            return $infos;
        }
    }

    public function aGet($id)
    {
        return $this->dbMenuDao->aGet($id);
    }

    public function iDelete($iId)
    {
        $oTreeApi = new MtreeApi();
        $aParent = $oTreeApi->aGetParent($iId, 1);
        $iParentId = empty($aParent) ? 0 : $aParent[0];
        try {
            $this->dbMenuDao->iDelete($iId);
            $oTreeApi->bDel($iId, $iParentId);
            $this->bClearCache();

            return $iId;
        } catch (Exception $e) {
            return 0;
        }
    }

    public function iCreate($sText, $sUrl, $iParentId = 0, $iFullScreen = 0)
    {
        $oTreeApi = new MtreeApi();
        try {
            $iId = $this->dbMenuDao->iInsert(array(
                'text' => $sText,
                'url' => $sUrl,
                'hidden' => $iFullScreen ? 1 : 0,
            ));
            $oTreeApi->bAdd($iId, $iParentId);
            $this->bClearCache();

            return $iId;
        } catch (Exception $e) {
            return 0;
        }
    }

    public function iUpdate($iId, $sText, $sUrl, $iParentId = 0, $iFullScreen = 0)
    {
        $oTreeApi = new MtreeApi();
        $aParent = $oTreeApi->aGetParent($iId, 1);
        $iCurrentParentId = empty($aParent) ? 0 : $aParent[0];
        try {
            $this->dbMenuDao->iUpdate($iId, array(
                'text' => $sText,
                'url' => $sUrl,
                'hidden' => $iFullScreen ? 1 : 0,
            ));
            if ($iCurrentParentId != $iParentId) {
                $oTreeApi->bDel($iId, $iCurrentParentId);
                $oTreeApi->bAdd($iId, $iParentId);
            }
            $this->bClearCache();
        } catch (Exception $e) {
            return 0;
        }

        return $iId;
    }

    public function aGetByUri($sUri)
    {
        if (strpos($sUri, '.php/') !== false) {
            $aTemp = explode('.php/', $sUri);
            $sUri = current($aTemp).'.php';
        }
        $all_menu = $this->aGetAll();
        $result = array();
        if ($all_menu) {
            $sUri = strtolower($sUri);
            $length = strlen($sUri);
            foreach ($all_menu as $menu) {
                if (substr(strtolower($menu['url']), 0, $length) == $sUri) {
                    $result[] = $menu;
                }
            }
        }

        return $result;
        //        $oOption = new Ko_Tool_SQL();
        //        $oOption->oWhere('url like ?', $sUri . '%');
        //        return $this->dbMenuDao->aGetList($oOption);
    }

    private function bClearCache()
    {
        $this->mcacheDao->bDelete(self::MC_ALL_MENU);
    }
}
