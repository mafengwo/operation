<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation syetem, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: Jichen Zhou.
 */

namespace apps\system;

class MApp
{
    protected $aPriMenuIds = array();

    public function aGetNavData()
    {
        $tree_api = new \apps\system\MtreeApi();
        $menu_api = new \apps\system\MApi();

        $cur_menu = $this->_aGetCurrentMenu();
        $relation_menu = array();
        if ($cur_menu) {
            $relation_menu = $tree_api->aGetParent($cur_menu['id'], 0);
            if ($relation_menu) {
                unset($relation_menu[count($relation_menu) - 1]);
            }
            $top_menu_id = $relation_menu ? $relation_menu[count($relation_menu) - 1] : $cur_menu['id'];
            if ($relation_menu) {
                $relation_menu = array_reverse($relation_menu);
                $relation_menu[] = $cur_menu['id'];
            }
            $cur_menu['parent'] = $relation_menu;
        }
        // 如果没有关联的导航则以第一个子节点为关联导航
        // if (!$top_menu_id) {
        //     $top_menu_id = 1;
        // }
        // 获取全部导航信息
        $all_menu = $menu_api->aGetAll();
        // 获取顶级导航
        $top_ids = array_keys($tree_api->aGetChild(0, 1));
        $top_menus = array();
        foreach ($top_ids as $top_id) {
            $top_menus[$top_id] = $all_menu[$top_id];
            $menu_tree = $tree_api->aGetChild($top_id, 0);
            if ($menu_tree) {
                $this->_vSetAllPriMenuIds($menu_tree);
            }
            $left_nav[$top_id] = $this->_sRenderingNav($menu_tree, $all_menu, $relation_menu);
        }
        //menu_tree 左侧菜单树
        //relation_menu 当前节点父节点集合
        //顶层导航a,当前节点a,左侧菜单数据
        return array($top_menus, $cur_menu, $left_nav);
    }

    //权限判断
    public function bAccess($uid,$cur_uri=''){
      $super_admin = in_array($uid, \apps\operation\MConf::$super_users);
      if(!$super_admin){
        $privacy_api = new \apps\system\MprivacyApi();
        $privacy_list = \Ko_Tool_Utils::AObjs2ids($privacy_api->
                        aGetMenuPriByAdminUid($uid),'menu_id');
        $cur = $this->_aGetCurrentMenu($cur_uri);
        $access = false;
        $tree_api = new \apps\system\MtreeApi();
        foreach($privacy_list as $p){
            $children = array_keys($tree_api->aGetChild($p));
            $children[] = $p;
            if(in_array($cur['id'],$children)){
                $access = true;
                break;
            }
        }
        return $access;
      }
      return true;
    }

    private function _aGetCurrentMenu($cur_uri='')
    {
        if($cur_uri=='') $cur_uri = MFunc::sGetCurUri();
        if($cur_uri=='/') return array();
        $cur_uri_params = MFunc::aGetCurUriParams();
        $has_params = false;
        $cur_menu = array();
        if ($cur_uri) {
            $menu_api = new \apps\system\MApi();
            $cur_menus = $menu_api->aGetByUri($cur_uri);
            if ($cur_menus) {
                foreach ($cur_menus as $menu) {
                    $tmp_menu_params = MFunc::aGetUriParams($menu['url']);
                    if ($cur_uri_params === $tmp_menu_params || count(array_intersect_assoc($tmp_menu_params, $cur_uri_params)) > 0) {
                        $cur_menu = $menu;
                        $has_params = true;
                        break;
                    }
                }
            }
        }
        if (!$has_params && !empty($cur_menus)) {
            $cur_menu = array_shift($cur_menus);
        }

        return $cur_menu;
    }

    private function _vSetAllPriMenuIds($item)
    {
        foreach ($item as $key => $value) {
            if (in_array($key, $this->aPriMenuIds, true)) {
                $sub_keys = array();
                $this->_aGetAllSubKeys($value, $sub_keys);
                if ($sub_keys) {
                    $this->aPriMenuIds = array_merge($this->aPriMenuIds, $sub_keys);
                }
            } else {
                $this->_vSetAllPriMenuIds($value);
            }
        }
    }

    private function _aGetAllSubKeys($menu_tree, array &$sub_keys)
    {
        foreach ($menu_tree as $key => $value) {
            $sub_keys[] = $key;
            if ($value) {
                $this->_aGetAllSubKeys($value, $sub_keys);
            }
        }
    }

    //渲染导航
    //$aTree 左侧菜单树
    //$all_menu 全部菜单
    //$aCurrent 当前节点父节点集合,包括自己,
    private function _sRenderingNav($aTree, $all_menu, $aCurrent = array(), $iLevel = 0)
    {
        $aNav = array();
        foreach ($aTree as $iId => $aSubTree) {
            // 是否有权限
            // if (!$this->is_admin && !in_array($iId, $this->aPriMenuIds, true)) {
            //     continue;
            // }
            //URL为空并且没有子菜单则跳过渲染 或者 设置为隐藏左侧导航栏
            if (($all_menu[$iId]['url'] == '' && empty($aSubTree)) || $all_menu[$iId]['hidden']) {
                continue;
            }
            $aSub = array();
            if (!empty($aSubTree)) {
                $aSub = $this->_sRenderingNav($aSubTree, $all_menu, $aCurrent, $iLevel + 1);
                if (empty($aSub)) {
                    continue;
                }
            }
            $aNav[] = array(
              'active'  =>  in_array($iId, $aCurrent),
              'url' => $all_menu[$iId]['url'],
              'text' => $all_menu[$iId]['text'],
              'icon' => $all_menu[$iId]['icon'],
              'selected' => $iLevel == 0 && in_array($iId, $aCurrent),
              'sub' => $aSub
            );
        }
        return $aNav;
    }
}
