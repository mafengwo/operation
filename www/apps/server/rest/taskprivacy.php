<?Php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation system, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\server;

class MRest_taskprivacy
{
    public static $s_aConf = array(
      'unique' => 'int',
      'stylelist' => array(
          'default' => array('list', array(
            'id' => 'int',
            'text' => 'string',
          )),
      ),
     'poststylelist' => array(
         'user' => array(
             'hash', array(
                 'admin_uid' => 'int',
                 'task_id' => 'int',
             ),
         ),
     ),
   );

    public function post($update, $after = null, $post_style = 'default')
    {
        switch ($post_style) {
        case 'user': //为用户增加节点授权
            if ($update['admin_uid'] && $update['task_id']) {
                $api = new \apps\server\MtaskprivacyApi();

                return $api->bAddMenuPri($update['admin_uid'], $update['task_id']);
            } else {
                throw new \Exception('缺少必要参数', 3);
            }
        break;
      }
    }

    public function delete($id, $before = null)
    {
        if (empty($id)) {
            throw new \Exception('信息不全，无法操作', 1);
        }
        $oApi = new \apps\server\MtaskprivacyApi();

        return $oApi->iDeletePri($id);
    }
}
