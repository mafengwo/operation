<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation syetem, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\operation\system;

\Ko_Web_Route::VGet('nodes', function () {

  $oApi = new \apps\system\MApi();
  $oTreeApi = new \apps\system\MtreeApi();

  $aList = $oApi->aGetAll();
  $aTree = $oTreeApi->aGetChild(0, 0);
  $nodes = _get_node_tree($aTree, $aList);

  $api = new \apps\user\MuserApi();
  $users = $api->getAllUsers();

  $render = new \apps\render\Mweb();
  $render->oSetTemplate('system/nodes.tpl')
      ->oSetData('IMG_DOMAIN', IMG_DOMAIN)
      ->oSetData('nodes', $nodes)
      ->oSetData('users', $users)
      ->oSend();
});

\Ko_Web_Route::VGet('administrator', function () {
    $api = new \apps\user\MuserApi();
    $list = $api->getList(0, 20, $sum);

    $loginApi = new \apps\user\MloginApi();
    $uid = $loginApi->iGetLoginUid();

    $privacy_api = new \apps\system\MprivacyApi();
    $privacy_list = $privacy_api->aGetPrivacyGroupByMenuId();

    $render = new \apps\render\Mweb();
    $render->oSetTemplate('system/administrator.tpl')
    ->oSetData('list', $list)
    ->oSetData('sum', $sum)
    ->oSetData('privacy_list', $privacy_list)
    ->oSetData('super_list', \apps\operation\MConf::$super_users)
    ->oSetData('super', in_array($uid, \apps\operation\MConf::$super_users))
    ->oSend();
});

function _get_node_tree($aTree, $aList, $iLevel = 0)
{
    $aMenu = array();
    foreach ($aTree as $iId => $aSubTree) {
        $aMenu[] = array(
          'id' => $iId,
          'text' => $aList[$iId]['text'],
          'hidden' => $aList[$iId]['hidden'],
          'sub' => !empty($aSubTree) ? _get_node_tree($aSubTree, $aList, $iLevel + 1) : array(),
        );
    }
    return $aMenu;
}

function _get_node_options($iCurrentId, $aTree, $aList, $sPrefix = '', $iLevel = 0)
{
    $aMenuOption = array();
    foreach ($aTree as $iId => $aSubTree) {
        if ($iLevel + 1 == \apps\system\MtreeApi::MAX_DEPTH) {
            continue;
        }
        $aMenuOption[] = array(
          'id' => $iId,
          'text' => $aList[$iId]['text'],
          'sub' => _get_node_options($iCurrentId, $aSubTree, $aList, $sCurrentPrefix, $iLevel + 1)
        );
    }

    return $aMenuOption;
}

/*
\Ko_Web_Route::VGet('privacy', function () {
    $iId = \Ko_Web_Request::IInput('id');
    $oApi = new \apps\system\MApi();
    $oTreeApi = new \apps\system\MtreeApi();
    $oPriApi = new \apps\system\MprivacyApi();
    $aList = $oApi->aGetAll();
    $aMenu = $aList[$iId];

    $aParent = $oTreeApi->aGetParent($iId, 0);
    unset($aParent[count($aParent) - 1]);
    array_unshift($aParent, $iId);
    $pri_list = $oPriApi->aGetPrivacyGroupByMenuId($aParent);

    $tree_list = array();
    $aParent = array_reverse($aParent);
    $split = 0;
    foreach ($aParent as $menu_id) {
        $menu = $aList[$menu_id];
        $menu['pri'] = isset($pri_list[$menu_id]) ? $pri_list[$menu_id] : array();
        $html = _getMenuHtml($menu, $split);
        $tree_list[] = $html;
        ++$split;
    }

    $smarty = new \Ko_View_Smarty();
    $smarty->vAssignHtml(array(
        'title' => '权限管理',
        'info' => $aMenu,
        'tree_list' => implode("\n", $tree_list),
    ), null, array('tree_list'));
    echo $smarty->sFetch('system/privacy.tpl');
    exit;
});
*/
