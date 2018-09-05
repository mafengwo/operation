<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation syetem, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: jichen zhou.
 */

namespace apps\user;

class MuserApi extends \Ko_Mode_Item
{
    protected $_aConf = array(
        'item' => 'user',
    );

    public function getList($start, $length, &$sum)
    {
        $option = new \Ko_Tool_SQL();
        $option->oOffset($start)->oLimit($length)->oCalcFoundRows(true);
        $list = $this->aGetList($option);
        $sum = $option->iGetFoundRows();

        return $list;
    }

    public function getUser($uid)
    {
        return $this->aGet($uid);
    }

    public function getUsers($uids)
    {
        return $this->aGetListByKeys($uids);
    }

    public function getAllUsers(){
      $option = new \Ko_Tool_SQL();
      $option->oSelect('id,username')->oWhere('flag=1');
      return $this->aGetList($option);
    }

    public function addUser($params, $flag = 1)
    {
        $insert = array(
            'username' => $params['username'],
            'flag' => $flag,
            'add_uid' => $params['add_uid'],
            'ctime' => date('Y-m-d H:i:s'),
        );

        return $this->iInsert($insert);
    }

    public function editUser($id, $params)
    {
        return $this->iUpdate($id, $params);
    }

    public function doAfterLogin($uid)
    {
        $user = new self();
        $userInfo = $user->getUser($uid);
        if (!$userInfo) {
            return false;
        }
        $loginApi = new \apps\user\MloginApi();
        $loginApi->vSetLoginUid($uid);
        return true;
    }
}
