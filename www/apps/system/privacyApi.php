<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation syetem, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: jichen zhou.
 */

namespace apps\system;

class MprivacyApi extends \Ko_Busi_Api
{
    private $admin;//管理员信息

    public function __construct()
    {
        //获取管理员信息 依赖system/user模块
        $loginApi = new \apps\user\MloginApi();
        $this->admin_uid = $loginApi->iGetLoginUid($uinfo);
    }

    //增加权限
    public function bAddMenuPri($admin_uid, $menu_id)
    {
        try {
            $insert_field = array(
                'admin_uid' => $admin_uid,
                'menu_id' => $menu_id,
                'add_uid' => $this->admin['id'],
            );
            $this->dbMenuPrivacyDao->aInsert($insert_field, $insert_field);

            return true;
        } catch (\Exception $ex) {
            return false;
        }
    }

    //获取某个用户的所有权限
    public function aGetMenuPriByAdminUid($admin_uid)
    {
        $option = new \Ko_Tool_SQL();
        $option->oWhere('admin_uid = ?', $admin_uid);
        $infos = $this->dbMenuPrivacyDao->aGetList($option);

        return \Ko_Tool_Utils::AObjs2map($infos, 'menu_id');
    }

    //获取有指定导航的用户
    public function aGetPriByMenuId($menu_id)
    {
        $option = new \Ko_Tool_SQL();
        $option->oWhere('menu_id = ?', $menu_id);
        $infos = $this->dbMenuPrivacyDao->aGetList($option);
        $result = array();
        if ($infos) {
            $admin_uids = \Ko_Tool_Utils::AObjs2ids($infos, 'admin_uid');
            $api = new CAdminUser();
            $result = $api->get_admin_user_byids($admin_uids);
        }

        return $result;
    }

    //获取所有导航有权限的用户
    public function aGetPrivacyGroupByMenuId($menu_ids = array())
    {
        $option = new \Ko_Tool_SQL();
        if ($menu_ids) {
            $option->oWhere('menu_id IN ('.implode(',', $menu_ids).')');
        }
        $infos = $this->dbMenuPrivacyDao->aGetList($option);
        $result = array();
        if ($infos) {
            $option = new \Ko_Tool_SQL();
            $node_info = \Ko_Tool_Utils::AObjs2map($this->dbMenuDao->aGetList($option), 'id');
            $tree_api = new \apps\system\MtreeApi();
            foreach ($infos as $info) {
                $parent = $tree_api->aGetParent($info['menu_id']);
                $result[$info['admin_uid']][] = array(
                    'id' => $info['id'],
                    'node_id' => $info['menu_id'],
                    'node_name' => $node_info[$info['menu_id']]['text'],
                    'parent' => $parent[0] > 0 ? $node_info[$parent[0]]['text'] : '',
                );
            }
        }

        return $result;
    }

    //删除某个用户的所有权限
    public function vDeleteMenuPriByAdminUid($admin_uid)
    {
        $menus = $this->aGetMenuPriByAdminUid($admin_uid);
        if ($menus) {
            foreach ($menus as $menu) {
                $this->iDeleteMenuPri($menu['id']);
            }
        }
    }

    //删除某个用户指定导航的权限
    public function vDeleteOneMenuPri($admin_uid, $menu_id)
    {
        $menus = $this->aGetMenuPriByAdminUid($admin_uid);
        if ($menus && isset($menus[$menu_id])) {
            $this->iDeleteMenuPri($menus[$menu_id]['id']);
        }
    }

    //删除单个权限
    public function iDeleteMenuPri($id)
    {
        return $this->dbMenuPrivacyDao->iDelete($id);
    }
}
