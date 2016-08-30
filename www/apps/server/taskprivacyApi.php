<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation system, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\server;

class MtaskprivacyApi extends \Ko_Mode_Item
{
    protected $_aConf = array(
        'item' => 'task_privacy',
    );
    private $admin;//管理员信息

    public function __construct()
    {
      //获取管理员信息 依赖system/user模块
      $loginApi = new \apps\user\MloginApi();
      $this->admin_uid = $loginApi->iGetLoginUid($uinfo);
    }

    //增加权限
    public function bAddMenuPri($admin_uid, $task_id)
    {
        try {
            $insert_field = array(
                'admin_uid' => $admin_uid,
                'task_id' => $task_id,
                'add_uid' => $this->admin['id'],
            );
            $this->iInsert($insert_field, array(), array(), null,
                $this->admin_uid);

            return true;
        } catch (\Exception $ex) {
            return false;
        }
    }

    //获取某个权限下的所有用户
    public function aGetUidGroupByTask()
    {
      $data = array();
      $option = new \Ko_Tool_SQL();
      $infos = $this->aGetList($option);
      foreach($infos as $v){
        $data[$v['task_id']][] = $v;
      }
      return $data;
    }

    //判断某个用户是否有task权限
    public function bHasTaskPriByUid($task_id){
      //此处需要依赖user模块的loginuid
      $loginApi = new \apps\user\MloginApi();
      $uid = $loginApi->iGetLoginUid();
      $option = new \Ko_Tool_SQL();
      $option->oWhere('admin_uid = ? and task_id = ?', $uid, $task_id);
      $infos = $this->aGetList($option);
      return (is_array($infos) && count($infos)==1) ? true : false;
    }

    //获取某个用户的所有task权限
    public function aGetTaskPriByUid($admin_uid)
    {
        $option = new \Ko_Tool_SQL();
        $option->oWhere('admin_uid = ?', $admin_uid);
        $infos = $this->aGetList($option);
        $task_id = \Ko_Tool_Utils::AObjs2ids($infos, 'task_id');
        $task_api = new MremotetaskApi();
        return $task_api->aGetValidIdByIds($task_id);
    }

    //删除某个用户的所有task权限
    public function vDeletePriByAdminUid($admin_uid)
    {
        $menus = $this->aGetTaskPriByUid($admin_uid);
        if ($menus) {
            foreach ($menus as $menu) {
                $this->iDeletePri($menu['id']);
            }
        }
    }

    //删除单个task权限
    public function iDeletePri($id)
    {
        return $this->iDelete($id, null, $this->admin_uid);
    }
}
