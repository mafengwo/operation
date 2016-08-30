<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation syetem, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: jichen zhou.
 */

namespace apps\user;

class MRest_item
{
    public static $s_aConf = array(
        'unique' => 'int',
        'stylelist' => array(
            'default' => array(
                'hash', array(
                    'uid' => 'int',
                    'nickname' => 'string',
                    'logo' => 'string',
                ),
            ),
        ),
        'filterstylelist' => array(
            'default' => array('list', 'int'),
        ),
        'poststylelist' => array(
            'default' => array('hash', array(
                'username' => 'string',
                'passwd' => 'string',
                'nickname' => 'string',
            )),
        ),
        'putstylelist' => array(
            'passwd' => array('hash', array(
                'oldpasswd' => 'string',
                'newpasswd' => 'string',
            )),
            'profile' => array('hash', array(
                'nickname' => 'string',
            )),
            'audit' => array(),
        ),
    );

    public function post($update, $after = null)
    {
        throw new \Exception('注册功能已关闭', 1);
        if (!preg_match('/^[_0-9a-z]{4,16}$/i', $update['username'])) {
            throw new \Exception('登录名称只能使用字母，数字和下划线，4-16个字符', 1);
        }
        if (!preg_match('/^[_0-9a-z]{4,16}$/i', $update['passwd'])) {
            throw new \Exception('登录密码只能使用字母，数字和下划线，4-16个字符', 2);
        }

        $loginApi = new MloginApi();
        $uid = $loginApi->iRegister($update['username'], $update['passwd'], $errno);
        if (!$uid) {
            if (\Ko_Mode_User::E_REGISTER_ALREADY === $errno) {
                throw new \Exception('登录名称已经被使用了', 3);
            }
            throw new \Exception('注册失败', 4);
        }
        $loginApi->vSetLoginUid($uid, 'reg');

        return array('key' => $uid);
    }

    public function put($id, $update, $before = null, $after = null, $put_style = 'default')
    {
        $loginApi = new MloginApi();
        $uid = $loginApi->iGetLoginUid();

        if ('audit' == $put_style) {
            if (!in_array($uid, \apps\operation\MConf::$super_users)) {
                throw new \Exception('没有审核权限', 1);
            }
            $api = new MuserApi();
            $ret = $api->editUser($id, array('flag' => 1));

            return array('key' => $ret);
        }

        if (!$uid || $uid != $id) {
            throw new \Exception('修改密码失败', 1);
        }

        switch ($put_style) {
            case 'passwd':
                if (!preg_match('/^[_0-9a-z]{4,16}$/i', $update['newpasswd'])) {
                    throw new \Exception('登录密码只能使用字母，数字和下划线，4-16个字符', 2);
                }
                if (false === $loginApi->bChangePassword($uid, $update['oldpasswd'], $update['newpasswd'], $errno)) {
                    throw new \Exception('旧密码输入错误', 3);
                }
                break;
            case 'profile':
                if ('' == $update['nickname']) {
                    throw new \Exception('请输入昵称', 4);
                }
                //todo edit profile
                break;
        }

        return array('key' => $id);
    }
}
