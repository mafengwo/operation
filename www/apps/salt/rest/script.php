<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation syetem, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\salt;

class MRest_script
{
    public static $s_aConf = array(
    'unique' => 'string',
    'poststylelist' => array(
      'default' => array(
        'hash', array(
          'path' => 'string',
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
            $path = \apps\salt\MApi::SALT_SCRIPT_PATH.$id;
            $api = new \apps\salt\MApi();
            $tmp = $api->wheel('file_roots.read', array('path' => $path));
            if ($tmp) {
                return $tmp[$path];
            } else {
                throw new \Exception('数据不存在', 2);
            }
        } else {
            throw new \Exception('缺少重要参数', 1);
        }
    }

    public function post($update, $after = null, $post_style = 'default')
    {
        if (empty($update['path']) || empty($update['data'])) {
            throw new \Exception('数据不完整', 1);
        }
        if (stripos($update['path'], '..') !== false || stripos($update['path'], '/') !== false) {
            throw new \Exception('文件名中存在非法字符', 2);
        }
        $path = 'scripts/'.$update['path'];
        $api = new \apps\salt\MApi();
        $tmp = $api->wheel('file_roots.write', array('data' => $update['data'], 'path' => $path));
        //print_r($tmp);
        return array('ret' => 1);
    }
}
