<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation syetem, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: jichen zhou.
 */

namespace apps\system;

class MroleApi extends \Ko_Mode_Item
{
    const FLAG_NORMAL = 1;
    const FLAG_DELETE = -1;
    public static $flagStatus = array(
            self::FLAG_NORMAL => '正常',
            self::FLAG_DELETE => '已删除',
    );
    protected $_aConf = array(
            'item' => 'sqRole',
    );
    public function aGetRoleList($iStart, $iLength, $flag = null)
    {
        $option = new \Ko_Tool_SQL();
        $option->oOffset($iStart)->oLimit($iLength)->oCalcFoundRows(true);
        $option->oOrderBy('ctime desc');
        if (is_int($flag)) {
            $option->oWhere('flag = ?', $flag);
        }
        $aData = $this->aGetList($option);
        $aResult['total'] = $option->iGetFoundRows();
        $aResult['list'] = $aData;

        return $aResult;
    }

    public function aGetRoles($iRids)
    {
        return $this->aGetListByKeys($iRids);
    }

    public function iDeleteRole($iRid)
    {
        $aUpdate = array(
                'flag' => self::FLAG_DELETE,
        );

        return $this->iUpdateRole($iRid, $aUpdate);
    }

    public function iUpdateRole($iRid, $aUpdate, $aChange = array())
    {
        return $this->iUpdate($iRid, $aUpdate, $aChange);
    }

    public function iAddRole($aInsert)
    {
        return $this->iInsert($aInsert);
    }

    //获取权限方法,可以加mc或者mysql索引
    public function aGetMenuIdByAdminUid($iAdminUid)
    {
        $menuIds = array();
        $aRids = \Ko_Tool_Singleton::OInstance('KShequ_Menu_roleUserApi')->aGetRidsIdBuyAdminUid($iAdminUid);
        if (!empty($aRids)) {
            $aRids = \Ko_Tool_Utils::AObjs2ids(
                    array_filter(
                            $this->aGetRoles($aRids),
                            function ($v) {
                                return $v['flag'] == KShequ_Menu_roleApi::FLAG_NORMAL;
                            }
                    ),
                    'rid'
            );
            if (!empty($aRids)) {
                foreach ($aRids as $iRid) {
                    $menuIds = array_merge($menuIds, \Ko_Tool_Singleton::OInstance('KShequ_Menu_rolePrivacyApi')->aGetMenuIdsByRid($iRid));
                }
            }
            $menuIds = array_unique(\Ko_Tool_Utils::AObjs2ids($menuIds, 'menu_id'));
        }

        return $menuIds;
    }
}
