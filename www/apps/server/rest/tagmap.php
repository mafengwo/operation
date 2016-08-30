<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation system, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\server;

class MRest_tagmap
{
    public static $s_aConf = array(
        'unique' => 'int',
        'stylelist' => array(
            'default' => 'int',
        ),
        'poststylelist' => array(
            'default' => array(
                'hash', array(
                    'id' => 'int',
                    'node_id' => 'int',
                    'tag_id' => 'int',
                ),
            ),
            'add' => array(
                'hash', array(
                    'id' => 'int',
                    'node_ids' => 'list'
                ),
            ),
            'del' => array(
                'hash', array(
                    'id' => 'int',
                    'node_ids' => 'list'
                ),
            ),
        ),
    );

    public function post($update, $after = null, $post_style = 'default')
    {
        $api = new \apps\server\MtagmapApi();
        if ($post_style == 'default') {
            if (empty($update['tag_id'])) {
                throw new \Exception('请将信息填写完全', 3);
            }
            if ($update['id']) {
                $id = $update['id'];
                unset($update['id']);
                $ret = $api->vUpdate($id, $update);
            } else {
                $ret = $api->vAdd($update);
            }

            if (!$ret) {
                throw new \Exception('操作失败，请重试', 2);
            }
        } else if (in_array($post_style,array('add','del'))){
            if($update['id'] && count($update['node_ids'])){
                $acts = array('add'=>'vAdd','del'=>'vDelByCond');
                $api = new \apps\server\MtagmapApi();
                foreach($update['node_ids'] as $nid){
                    $actname = $acts[$post_style];
                    $api->$actname(array('tag_id'=>$update['id'],'node_id'=>$nid));
                }
                $ret = true;
            } else {
                throw new \Exception('请将信息补充完全', 1);
            }
        }
        return array('key' => $ret);
    }

    public function delete($tagmap_id, $before = null)
    {
        if (empty($tagmap_id)) {
            throw new \Exception('信息不全，无法操作', 1);
        }
        $api = new \apps\server\MtagmapApi();
        $ret = $api->vDel($tagmap_id);

        return array('ret' => $ret);
    }
}
