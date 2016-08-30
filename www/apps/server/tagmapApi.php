<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation system, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\server;

class MtagmapApi extends \Ko_Mode_Item
{
    protected $_aConf = array(
        'item' => 'tag_map',
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
        return $this->iInsert($update, array(), array(), null,
            $this->admin_uid);
    }

    public function vUpdate($id, array $update)
    {
        return $this->iUpdate($id, $update, array(), null,
            $this->admin_uid);
    }

    public function aGetAllGroupByNodeId()
    {
        $data = array();
        $option = new \Ko_Tool_SQL();
        $list = $this->aGetList($option->oOrderBy('id'));
        foreach ($list as $v) {
            $data[$v['node_id']][] = $v;
        }

        return $data;
    }

    public function aGetAllGroupByTagId()
    {
        $data = array();
        $option = new \Ko_Tool_SQL();
        $list = $this->aGetList($option->oOrderBy('id'));
        foreach ($list as $v) {
            $data[$v['tag_id']][] = $v;
        }

        return $data;
    }

    public function aGetAll()
    {
        $option = new \Ko_Tool_SQL();

        return $this->aGetList($option);
    }

    public function aGetByNodeId($id)
    {
        $option = new \Ko_Tool_SQL();

        return $this->aGetList($option->oWhere('node_id = ?', $id));
    }

    public function aGetByTagId($id)
    {
        $option = new \Ko_Tool_SQL();

        return $this->aGetList($option->oWhere('tag_id = ?', $id));
    }

    public function aGetByTagIds(array $ids)
    {
        $option = new \Ko_Tool_SQL();

        return $this->aGetList($option->oWhere('tag_id in (?)', $ids));
    }

    public function aGetByTag($tag,$type='')
    {
        $api = new MtagsApi();
        $tids = $api->aGetIdsByNameAndType($tag,$type);
        $option = new \Ko_Tool_SQL();
        $option->oWhere('tag_id in (?)', $tids);
        return $this->aGetList($option);
    }

    public function vUpdateByBatchTags($node_id, array $tags)
    {
        $data = $this->aGetByNodeId($node_id);
        $ori_tags = \Ko_Tool_Utils::AObjs2ids($data, 'tag_id');
        $cur_tags = $tags;
        sort($ori_tags);
        sort($cur_tags);
        if ($ori_tags == $cur_tags) {
            return true;
        } else {
            $option = new \Ko_Tool_SQL();
            $option->oWhere('node_id = ?', $node_id);
            $this->iDeleteByCond($option);
            foreach ($tags as $t) {
                $this->iInsert(array('node_id' => $node_id, 'tag_id' => $t), array(), array(), null,
                $this->admin_uid);
            }

            return true;
        }
    }

    public function vDelByCond(array $data){
        $option = new \Ko_Tool_SQL();
        $option->oWhere(1);
        $data['tag_id'] && $option->oAnd('tag_id = ?',$data['tag_id']);
        $data['node_id'] && $option->oAnd('node_id = ?',$data['node_id']);

        return $this->iDeleteByCond($option);
    }

    public function vDelByTagId($tag_id)
    {
        $option = new \Ko_Tool_SQL();
        $option->oWhere('tag_id = ?', $tag_id);

        return $this->iDeleteByCond($option);
    }

    public function vDelByNodeId($node_id)
    {
        $option = new \Ko_Tool_SQL();
        $option->oWhere('node_id = ?', $node_id);

        return $this->iDeleteByCond($option);
    }

    public function vDel($id)
    {
        return $this->iDelete($id, null, $this->admin_uid);
    }
}
