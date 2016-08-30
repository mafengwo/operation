<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation system, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\server;

class MtagsApi extends \Ko_Mode_Item
{
    const TAG_SYSTEM = 1; //系统标签 如 docker master
    const TAG_SERVICE = 2; //服务标签 具备服务特性 含端口信息
    const TAG_BIZ = 3; //业务标签 如 web admin 等
    const TAG_SCHEDULE = 4; //schedule标签
    //const TAG_VIRTUALIP = 5; //虚ip标签
    //const TAGNAME_VIRTUALIP = 'virtual_ip'; //虚ip的系统标签名称

    private $_aTypes = array(
        self::TAG_SYSTEM, self::TAG_SERVICE, self::TAG_BIZ, 
	//self::TAG_SCHEDULE, self::TAG_VIRTUALIP,
    );

    public static $aTypeName = array(
        self::TAG_SYSTEM => '系统标签',
        self::TAG_SERVICE => '服务标签',
        self::TAG_BIZ => '业务标签',
        self::TAG_SCHEDULE => 'Schedule标签',
        self::TAG_VIRTUALIP => '虚IP标签',
    );

    protected $_aConf = array(
        'item' => 'tags',
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
        if (in_array($update['type'], $this->_aTypes)) {
            return $this->iInsert($this->_ArrTrim($update), array(), array(), null,
            $this->admin_uid);
        } else {
            return false;
        }
    }

    public function vUpdate($id, array $update)
    {
        if (in_array($update['type'], $this->_aTypes)) {
            return $this->iUpdate($id, $this->_ArrTrim($update), array(), null,
            $this->admin_uid);
        } else {
            return false;
        }
    }

    public function aGetByCond($q)
    {
        $option = new \Ko_Tool_SQL();
        $option->oWhere('1');
        $q['type'] > 0 && $option->oAnd('type = ? ', $q['type']);
        $q['name'] != '' && $option->oAnd('name like ? or name like ?', $q['name'].'%', '%'.$q['name'].'%');
        if ($q['page'] >= 1 && $q['pagesize'] > 0) {
            $offset = ($q['page'] - 1) * $q['pagesize'];
            $option->oOffset($offset)->oLimit($q['pagesize'])->oCalcFoundRows(true);
        }
        $option->oOrderBy('type,name');

        return array('list' => $this->aGetList($option), 'total' => $option->iGetFoundRows());
    }

    public function aGetByIds(array $ids)
    {
        $option = new \Ko_Tool_SQL();
        $option->oWhere('id in (?)', $ids);

        return $this->aGetList($option);
    }

    public function aGetIdsByNameAndType($name, $type = '')
    {
        $option = new \Ko_Tool_SQL();
        $option->oWhere('name = ?', $name);
        if (!empty($type)) {
            if (is_array($type)) {
                $option->oAnd('type in (?)', $type);
            } else {
                $option->oAnd('type = ? ', $type);
            }
        }

        return \Ko_Tool_Utils::AObjs2ids($this->aGetList($option), 'id');
    }

    public function aGetIdsByName($name)
    {
        $option = new \Ko_Tool_SQL();
        $option->oWhere('name = ? ', $name);

        return \Ko_Tool_Utils::AObjs2ids($this->aGetList($option), 'id');
    }

    public function aGetAll()
    {
        $option = new \Ko_Tool_SQL();
        $list = $this->aGetList($option->oOrderBy('type,name'));

        return \Ko_Tool_Utils::AObjs2map($list, 'id');
    }

    public function aGetByType($type)
    {
        $option = new \Ko_Tool_SQL();
        if (is_array($type)) {
            $option->oWhere('type in (?)', $type);
        } else {
            $option->oWhere('type = ?', $type);
        }
        $list = $this->aGetList($option->oOrderBy('type,name'));

        return \Ko_Tool_Utils::AObjs2map($list, 'id');
    }

    public function bCheckTags(array $tags)
    {
        $service_tag_num = 0;
        $list = $this->aGetByIds($tags);
        foreach ($list as $v) {
            if ($v['type'] == self::TAG_SERVICE) {
                $service_tag_num += 1;
            }
        }

        return $service_tag_num > 1 ? false : true;
    }

    public function vDel($id)
    {
        return $this->iDelete($id, null, $this->admin_uid);
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
