<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation system, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\server;

class MApi extends \Ko_Busi_Api
{
    //节点列表页方法
    public function nodesListByCond($query)
    {
        $nodes_id = array();
        if ($query['tag_id']) { //通过分组id获取分组范围
            $tagmap_api = new \apps\server\MtagmapApi();
            $tag_map = $tagmap_api->aGetByTagIds($query['tag_id']);

            $tag_group = array();
            foreach ($tag_map as $v) {
                $tag_group[$v['tag_id']][] = $v['node_id'];
            }
            foreach (array_values($tag_group) as $k => $v) {
                if ($k == 0) {
                    $nodes_id = $v;
                } else {
                    $nodes_id = array_intersect($nodes_id, $v);
                }
            }
            if (empty($nodes_id)) {
                return array();//查无结果
            }
        }
        $option = new \Ko_Tool_SQL();
        $option->oWhere('1');
        if (count($nodes_id)) {
            $option->oAnd('id in (?) ', $nodes_id);
        }
        if ($query['ip']) {
            if (ip2long($query['ip'])) {
                $option->oAnd('identification = ?', $query['ip']);
            } else {
                $option->oAnd('identification like ?', '%'.$query['ip']);
            }
        }
        if ($query['status'] == 'online') {
            $option->oAnd('status=1');
        }

        if ($query['page']>=1 && $query['pagesize'] > 0) {
            $offset = ($query['page']-1) * $query['pagesize'];
            $option->oOffset($offset)->oLimit($query['pagesize'])->oCalcFoundRows(true);
        }

        $nodes_api = new MnodesApi();

        $list = $nodes_api->getNodesByCond($option, 'identification',$total);

        return array('list'=>$list,'total'=>$total);
    }

    //通过标签id查找ip列表
    public function getIpsByTagId($tag_id)
    {
        $tagmap_api = new \apps\server\MtagmapApi();
        $option = new \Ko_Tool_SQL();
        $tagmap = $tagmap_api->aGetByTagId($tag_id);
        $node_ids = \Ko_Tool_Utils::AObjs2ids($tagmap, 'node_id');
        if (count($node_ids)) {
            $nodes_api = new MnodesApi();

            return $nodes_api->getFieldByIds('ip', $node_ids);
        }

        return array();
    }

    //通过标签名称查找ip列表
    public function getIpsByTag($tag, $type = '')
    {
        $tagmap_api = new \apps\server\MtagmapApi();
        $option = new \Ko_Tool_SQL();
        $tagmap = $tagmap_api->aGetByTag($tag, $type);
        $node_ids = \Ko_Tool_Utils::AObjs2ids($tagmap, 'node_id');
        if (count($node_ids)) {
            $nodes_api = new MnodesApi();

            return $nodes_api->getFieldByIds('ip', $node_ids);
        } else {
            return array();
        }
    }

    //按照标签获取在线状态数量
    public function getOnlineStatNumByTag()
    {
        $tagmap_api = new \apps\server\MtagmapApi();
        $maps = $tagmap_api->aGetAllGroupByTagId();

        $nodes_api = new \apps\server\MnodesApi();
        $option = new \Ko_Tool_SQL();
        $list = \Ko_Tool_Utils::AObjs2map($nodes_api->getNodesByCond($option), 'id');

        $data = array();
        foreach ($maps as $tag_id => $maps) {
            foreach ($maps as $v) {
                if ($list[$v['node_id']]['status']) {
                    $data[$tag_id]['online'] += 1;
                } else {
                    $data[$tag_id]['offline'] += 1;
                }
            }
        }

        return $data;
    }

    //array trim tool
    static public function _aArrTrim($arr)
    {
        $data = array();
        foreach ($arr as $k => $v) {
            $data[$k] = trim($v);
        }

        return $data;
    }
}
