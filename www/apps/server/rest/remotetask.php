<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation system, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\server;

class MRest_remotetask
{
    public static $s_aConf = array(
        'unique' => 'int',
        'stylelist' => array(
            'default' => array('hash', array(
              'id' => 'int',
              'name' => 'string',
              'memo' => 'string',
              'status' => 'int',
            )),
        ),
        'poststylelist' => array(
            'default' => array('hash', array(
                'id' => 'int',
                'name' => 'string',
                'memo' => 'string',
                'ip' => 'string',
                'command' => 'string',
                'type' => 'int',
                'vars' => 'array',
                'sudo' => 'int',
                'status' => 'int',
            ))
        ),
    );

    public function get($id, $style = 'default')
    {
        $api = new \apps\server\MremotetaskApi();

        return $api->aGet($id);
    }

    public function post($update, $after = null, $post_style = 'default')
    {
        $api = new \apps\server\MremotetaskApi();
        if (empty($update['id'])) {
            if (empty($update['name'])) {
                throw new \Exception('请填写任务名称', 1);
            }
            if (empty($update['ip']) || empty($update['command']) || empty($update['type'])) {
                throw new \Exception('必要参数缺失', 2);
            }
        }
        if (is_array($update['vars']) && count($update['vars'])) {
            $update['vars'] = json_encode($update['vars']);
            $update['vars'] = str_replace('"', '\\"', $update['vars']);
        }
        !empty($update['command']) && $update['command'] = str_replace('"', '\\"', $update['command']);
        !isset($update['status']) && $update['status'] = 1;
        if ($update['id']) {
            $id = $update['id'];
            unset($update['id']);
            $ret = $api->vUpdate($id, $update);
        } else {
            $ret = $api->vAdd($update);
        }

        if (!$ret) {
            throw new \Exception('操作失败，请重试', 3);
        }

        return array('key' => $ret);
    }

    public function delete($id, $before = null)
    {
        if (empty($id)) {
            throw new \Exception('信息不全，无法操作', 1);
        }
        $api = new \apps\server\MremotetaskApi();
        $ret = $api->vDel($id);

        return array('ret' => $ret);
    }
}
