<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation system, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
* User: XieZe <xieze@mafengwo.com>.
 */

namespace apps\server;

class MassetsApi extends \Ko_Mode_Item
{
    protected $_aConf = array(
        'item' => 'assets',
    );

    public function __construct()
    {
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
        $option = new \Ko_Tool_SQL();
        return $this->iUpdate($id, MApi::_aArrTrim($update), array(), null,$this->admin_uid);
    }

    public function aAllList($num,$page)
    {
        $option = new \Ko_Tool_SQL();
        $offset = ($page-1)*$num;
        return $this->aGetList($option->oOffset($offset)->oLimit($num));
    }

    public function aGetAll()
    {
        $option = new \Ko_Tool_SQL();
        return $this->aGetList($option->oOrderBy('id desc'));
    }

    public function getPid($pid)
    {
        $option = new \Ko_Tool_SQL();
        return $this->aGetList($option->oWhere('pid=?',$pid));
    }

    public function aAllListNums()
    {
        $option = new \Ko_Tool_SQL();
        $ret = $this->aGetList($option);
        return $rows = count($ret);
    }

    public function vDelete($id)
    {
        return $this->iDelete($id);
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
