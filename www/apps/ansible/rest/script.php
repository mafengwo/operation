<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation syetem, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\ansible;

class MRest_script
{
    public static $s_aConf = array(
    'unique' => 'string',
    'poststylelist' => array(
      'default' => array(
        'hash', array(
            'path' => 'string',
            'file' => 'string',
            'data' => 'string',
        ),
      ),
    ),
    'stylelist' => array(
        'default' => array('string'),
    ),

  );

    public function get($id, $style = 'default')
    {
        if ($id) {
            $api = new \apps\ansible\MApi();
            $tmp = $api->file_read($id);
            if ($tmp['content']) {
                return $tmp['content'];
            } else {
                throw new \Exception('数据不存在', 2);
            }
        } else {
            throw new \Exception('缺少重要参数', 1);
        }
    }

    public function post($update, $after = null, $post_style = 'default')
    {
        if (empty($update['path']) || empty($update['file']) || empty($update['data'])) {
            throw new \Exception('数据不完整', 1);
        }
        if (stripos($update['file'], '..') !== false || stripos($update['file'], '/') !== false) {
            throw new \Exception('文件名中存在非法字符', 2);
        }
        $api = new \apps\ansible\MApi();
        $tmp = $api->file_write($update['path'], $update['file'], $update['data']);

        return array('ret' => 1);
    }
}
