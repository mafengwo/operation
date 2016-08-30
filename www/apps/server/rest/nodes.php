<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation system, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\server;

class MRest_nodes
{
    public static $s_aConf = array(
        'unique' => 'int',
        'stylelist' => array(
            'default' => array('hash', array(
                'id' => 'int',
                'ip' => 'string',
                'tag_ids' => 'array',
                'identification' => 'string',
                'status' => 'int',
            )),
            'postdone' => array('id' => 'int'),
         ),
        'poststylelist' => array(
            'default' => array('hash', array(
                'id' => 'int',
                'ip' => 'string',
                'tag_ids' => 'string',
                'identification' => 'string',
                'status' => 'int',
            )),
        ),
        'putstylelist' => array(
            'online' => array('hash', array(
                'id' => 'int',
            )),
            'offline' => array('hash', array(
                'id' => 'int',
            )),
        ),
    );

    public function post($update, $after = null, $post_style = 'default')
    {
        $api = new \apps\server\MnodesApi();
        if ($post_style == 'default') {
            if (empty($update['ip']) || !isset($update['tag_ids'])) {
                throw new \Exception('请将信息填写完全', 3);
            }
            !isset($update['status']) && $update['status'] = 1;
            $tag_ids = explode(',', $update['tag_ids']);
            unset($update['tag_ids']);

            $tag_api = new \apps\server\MtagsApi();
            if(!$tag_api->bCheckTags($tag_ids)){
                throw new \Exception('每个节点只可以选择一个服务标签，若有多个服务标签请增加多个节点。', 2);
            }

            if ($update['id']) {
                $id = $update['id'];
                unset($update['id']);
                $api->updateNodes($id, $update);
            } else {
                $id = $api->addNodes($update);
            }
            if (!$id) {
                throw new \Exception('操作失败，请重试', 1);
            }
            $tagmap_api = new \apps\server\MtagmapApi();
            $tagmap_api->vUpdateByBatchTags($id, $tag_ids);
        }

        return array('key' => $id, 'after' => array('id' => $id));
    }

    public function put($id, $update, $before = null, $after = null, $put_style)
    {
        $api = new \apps\server\MnodesApi();
        if (empty($id)) {
            throw new \Exception('请将信息填写完全', 3);
        }

        switch ($put_style) {
            case 'online':
                $ret = $api->updateNodes($id, array('status' => 1));
                break;
            case 'offline':
                $ret = $api->updateNodes($id, array('status' => 0));
                break;
        }

        return array('ret' => $ret);
    }

    public function get($id, $style = 'default')
    {
        $api = new \apps\server\MnodesApi();
        $data = $api->getNodeById($id);
        $tagmap_api = new \apps\server\MtagmapApi();
        $data['tag_ids'] = \Ko_Tool_Utils::AObjs2ids($tagmap_api->aGetByNodeId($id), 'tag_id');

        return $data;
    }

    public function delete($id, $before = null)
    {
        if (empty($id)) {
            throw new \Exception('信息不全，无法操作', 1);
        }
        $api = new \apps\server\MnodesApi();
        $ret = $api->delNodes($id);
        $tagmap_api = new \apps\server\MtagmapApi();
        $tagmap_api->vDelByNodeId($id);

        return array('ret' => $ret);
    }
}
