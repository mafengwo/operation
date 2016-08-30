<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation system, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\server;

class MnodesApi extends \Ko_Mode_Item
{
    protected $_aConf = array(
        'item' => 'nodes',
        'itemlog' => 'oplog',
        'itemlog_kindfield' => 'kind',
        'itemlog_idfield' => 'infoid',
    );

    public function getNodesByCond(\Ko_Tool_SQL $option, $groupby = '',&$total = 0)
    {
        $list = $this->aGetList($option);
        $option->bCalcFoundRows() && $total = $option->iGetFoundRows();
        $list = $this->_adjustServerList($list);

        return $groupby ? $this->_GroupBy($list, $groupby) : $list;
    }

    public function getAllNodes($groupby = '')
    {
        $option = new \Ko_Tool_SQL();

        return $this->getNodesByCond($option,$groupby);
    }

    public function getAllNodeIp()
    {
        $option = new \Ko_Tool_SQL();
        $option->oSelect('ip')->oWhere('status = 1');
        $list = $this->aGetList($option);
        $list = array_unique(\Ko_Tool_Utils::AObjs2ids($list, 'ip'));
        $list = array_map('long2ip', $list);
        sort($list);

        return $list;
    }

    public function getNodeById($id)
    {
        $rs = $this->aGet($id);

        return $this->_adjustServer($rs);;
    }

    public function getFieldByIds($field, array $ids)
    {
        assert(in_array($field,array('identification','ip')));
        $option = new \Ko_Tool_SQL();
        $option->oSelect($field)->oWhere('status=1 and id in (?)', $ids);
        $items = $this->getNodesByCond($option);

        return array_unique(\Ko_Tool_Utils::AObjs2ids($items, $field));

    }

    public function getNodeIdsByIps(array $ip_list)
    {
        $ip_list = array_map(function ($v) { return ip2long($v) > 0 ? ip2long($v) : ''; }, $ip_list);
        $ip_list = array_filter($ip_list);
        $option = new \Ko_Tool_SQL();
        $option->oWhere('ip in (?)', $ip_list);
        $list = $this->aGetList($option);
        $data = array();
        foreach ($list as $v) {
            $data[$v['ip']][] = $v['id'];
        }

        return $data;
    }

    public function getVirtualIps(){
        $map_api = new MtagmapApi();
        $tagmaps = $map_api->aGetByTag(MtagsApi::TAGNAME_VIRTUALIP,MtagsApi::TAG_SYSTEM);
        $node_ids = \Ko_Tool_Utils::AObjs2ids($tagmaps,'node_id');
        $option = new \Ko_Tool_SQL();
        $option->oWhere('id in (?)', $node_ids);
        return $this->getNodesByCond($option,'identification');
    }

    public function addNodes($update)
    {
        $update['ip'] = ip2long($update['ip']);

        return $this->iInsert($this->_ArrTrim($update), array(), array(), null, $this->admin_uid);
    }

    public function updateNodes($id, array $update)
    {
        $update['ip'] && $update['ip'] = ip2long($update['ip']);

        return $this->iUpdate($id, $this->_ArrTrim($update), array(), null, $this->admin_uid);
    }

    public function delNodes($id)
    {
        $this->iDelete($id, null, $this->admin_uid);
    }

    private function _adjustServerList($list)
    {
        $new = array();
        foreach ($list as $item) {
            $new[] = $this->_adjustServer($item);
        }
        return $new;
    }

    private function _adjustServer($item)
    {
        if (isset($item['ip'])) {
            $item['ip'] = long2ip($item['ip']);
        }
        return $item;
    }

    private function _GroupBy($obj, $key)
    {
        $data = array();
        foreach ($obj as $v) {
            $data[$v[$key]][] = $v;
        }
        ksort($data);

        return $data;
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
