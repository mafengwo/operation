<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation syetem, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\ansible;

class MRest_filexist
{
    public static $s_aConf = array(
    'unique' => 'string',
    'stylelist' => array(
      'default' => array('array'),
      ),
    );

    public function get($id, $style = 'default')
    {
        if ($id) {
            $api = new \apps\ansible\MApi();
            $tmp = $api->file_exist($id);
            if ($tmp['ret']) {
                return true;
            } else {
                throw new \Exception('数据不存在', 2);
            }
        } else {
            throw new \Exception('缺少重要参数', 1);
        }
    }
}
