<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation syetem, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\server;

class MremotetaskApi extends \Ko_Mode_Item
{
    protected $_aConf = array(
        'item' => 'remote_task',
        'itemlog' => 'oplog',
        'itemlog_kindfield' => 'kind',
        'itemlog_idfield' => 'infoid',
    );

    public function __construct()
    {
        //获取管理员信息 依赖system/user模块
        $loginApi = new \apps\user\MloginApi();
        $this->admin_uid = $loginApi->iGetLoginUid($uinfo);
    }

    public function vAdd($update)
    {
        return $this->iInsert($this->_ArrTrim($update), array(), array(), null,
          $this->admin_uid);
    }

    public function vUpdate($id, array $update)
    {
        return $this->iUpdate($id, $this->_ArrTrim($update), array(), null,
            $this->admin_uid);
    }

    public function aAllList()
    {
        $option = new \Ko_Tool_SQL();

        return $this->aGetList($option);
    }

    public function aGetValidByIds(array $ids){
      $option = new \Ko_Tool_SQL();
      $option->oWhere('status=1 and id in (?)',$ids);
      return \Ko_Tool_Utils::AObjs2map($this->aGetList($option), 'id');
    }

    public function aGetValidIdByIds(array $ids){
      $option = new \Ko_Tool_SQL();
      $option->oSelect('id')->oWhere('status=1 and id in (?)',$ids);
      return \Ko_Tool_Utils::AObjs2ids($this->aGetList($option), 'id');
    }

    public function vDel($id)
    {
        return $this->iUpdate($id, array('status' => 0), array(), null,
          $this->admin_uid);
    }

    private function _ArrTrim($arr)
    {
        $data = array();
        foreach ($arr as $k => $v) {
            $data[$k] = trim($v);
        }

        return $data;
    }
}
