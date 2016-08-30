<?Php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation syetem, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\system;

class MRest_privacy
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
                 'node_id' => 'int',
             ),
         ),
     ),
   );

    public function post($update, $after = null, $post_style = 'default')
    {
        $api = new \apps\user\MloginApi();
        $uid = $api->iGetLoginUid();
        if (!in_array($uid, \apps\operation\MConf::$super_users)) {
            throw new \Exception('没有权限,请求被拒绝', 1);
        }
        if (in_array($update['admin_uid'], \apps\operation\MConf::$super_users)) {
            throw new \Exception('超级管理员无需进行授权', 2);
        }
        switch ($post_style) {
        case 'user': //为用户增加节点授权
            if ($update['admin_uid'] && $update['node_id']) {
                $api = new \apps\system\MprivacyApi();

                return $api->bAddMenuPri($update['admin_uid'], $update['node_id']);
            } else {
                throw new \Exception('缺少必要参数', 3);
            }
        break;
      }
    }

    public function get($id, $style = 'default')
    {
        $api = new \apps\user\MuserApi();
        $list = $api->getAllUsers();
        foreach ($list as &$v) {
            $v['text'] = $v['username'];
        }

        return $list;
    }

    public function delete($id, $before = null){
      if (empty($id)) {
          throw new \Exception('信息不全，无法操作', 1);
      }
      $api = new \apps\user\MloginApi();
      $uid = $api->iGetLoginUid();
      if (!in_array($uid, \apps\operation\MConf::$super_users)) {
          throw new \Exception('没有权限,请求被拒绝', 2);
      }
      $oApi = new \apps\system\MprivacyApi();

      return $oApi->iDeleteMenuPri($id);
    }
}
