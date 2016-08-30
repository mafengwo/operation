<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation system, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\server;

class MRest_tag
{
    public static $s_aConf = array(
        'unique' => 'int',
        'stylelist' => array(
             'default' => array(
                     'hash', array(
                        'id' => 'int',
                        'type' => 'int',
                        'port' => 'int',
                        'name' => 'string',
                        'argument' => 'string',
                        'memo' => 'string',
                     ),
             ),
         ),
        'poststylelist' => array(
            'default' => array(
                'hash', array(
                    'id' => 'int',
                    'type' => 'int',
                    'port' => 'int',
                    'name' => 'string',
                    'argument' => 'string',
                    'memo' => 'string',
                ),
            ),
        ),
    );

    public function post($update, $after = null, $post_style = 'default')
    {
        $api = new \apps\server\MtagsApi();
        if ($post_style == 'default') {
            if ((empty($update['type']) || empty($update['name']))) {
                throw new \Exception('请将信息填写完全', 3);
            }
            if ($update['type'] == \apps\server\MtagsApi::TAG_SERVICE && empty($update['port'])) {
                throw new \Exception('服务类标签必须填写端口号', 4);
            }
            if ($update['id']) {
                $id = $update['id'];
                unset($update['id']);
                $ret = $api->vUpdate($id, $update);
            } else {
                $ret = $api->vAdd($update);
            }
            if (!$ret) {
                throw new \Exception('很可能这个标签已经存在。请确认无误后重试。', 2);
            }
        }

        return array('ret' => $ret);
    }

    public function get($id, $style = 'default')
    {
        $api = new \apps\server\MtagsApi();

        return $api->aGet($id);
    }

    public function delete($id, $before = null)
    {
        if (empty($id)) {
            throw new \Exception('信息不全，无法操作', 1);
        }
        $api = new \apps\server\MtagsApi();
        $ret = $api->vDel($id);
        if($ret) {
            $map_api = new \apps\server\MtagmapApi();
            $map_api->vDelByTagId($id);
        }

        return array('ret' => $ret);
    }
}
