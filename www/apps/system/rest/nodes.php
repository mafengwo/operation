<?Php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation syetem, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\system;

class MRest_nodes
{
    public static $s_aConf = array(
      'unique' => 'int',
      'stylelist' => array(
          'default' => array('hash', array(
            'id' => 'int',
            'text' => 'string',
            'url' => 'string',
            'parentid' => 'int',
            'mode' => 'int',
          )),
      ),
     'poststylelist' => array(
         'default' => array(
             'hash', array(
                 'id' => 'int',
                 'text' => 'string',
                 'url' => 'string',
                 'parentid' => 'int',
                 'mode' => 'int',
             ),
         ),
     ),
  );

    public function post($update, $after = null, $post_style = 'default')
    {
        if ($update['id'] == $update['parentid']) {
            throw new \Exception('不能将上级节点设置为自己', 3);
        }
        if ($update['text'] && $update['url']) {
            $oApi = new \apps\system\MApi();
            $api = new \apps\user\MloginApi();
            $uid = $api->iGetLoginUid();
            if (!in_array($uid, \apps\operation\MConf::$super_users)) {
                throw new \Exception('没有权限,请求被拒绝', 1);
            } else {
                return $update['id'] ? $oApi->iUpdate($update['id'], $update['text'],
                      $update['url'], $update['parentid'], $update['mode'])
                      : $oApi->iCreate($update['text'], $update['url'], $update['parentid'], $update['mode']);
            }
        } else {
            throw new \Exception('请将信息填写完整', 2);
        }
    }

    public function get($id, $style)
    {
        $iId = $id;
        $iParentId = \Ko_Web_Request::IInput('parentid');
        $oApi = new \apps\system\MApi();
        $oTreeApi = new \apps\system\MtreeApi();

        $aNode = $oApi->aGet($id);
        $aParent = $oTreeApi->aGetParent($id, 1);
        $aNode['mode'] = $aNode['hidden'];
        $aNode['parentid'] = empty($aParent) ? 0 : $aParent[0];

        return $aNode;
    }

    public function delete($id, $before = null)
    {
        if (empty($id)) {
            throw new \Exception('信息不全，无法操作', 1);
        }
        $api = new \apps\user\MloginApi();
        $uid = $api->iGetLoginUid();
        if (!in_array($uid, \apps\operation\MConf::$super_users)) {
            throw new \Exception('没有权限,请求被拒绝', 2);
        }
        $oApi = new \apps\system\MApi();

        return $oApi->iDelete($id);
    }
}
