<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation syetem, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: jichen zhou.
 */

namespace apps\user;

class MRest_login
{
    const FLAG_UNAUDITED = 0;//用户未审核状态
    public static $s_aConf = array(
        'unique' => 'string',
        'stylelist' => array(
            'default' => array(
                'hash', array(
                    'username' => 'string',
                    'uid' => 'int',
                ),
            ),
        ),
        'poststylelist' => array(
            'default' => array(
                'hash', array(
                    'username' => 'string',
                    'passwd' => 'string',
                ),
            ),
            'register' => array(
                'hash', array(
                    'username' => 'string',
                    'passwd' => 'string',
                ),
            ),
        ),
    );

    public function post($update, $after = null, $post_style)
    {
        $api = new \apps\user\MloginApi();
        if ($post_style == 'default') {
            if (empty($update['username']) || empty($update['passwd'])) {
                throw new \Exception('请将信息填写完全', 3);
            }
            $uid = $api->iLogin($update['username'], $update['passwd'], $errno);
            if (!$uid) {
                if (\Ko_Mode_User::E_LOGIN_USER == $errno) {
                    throw new \Exception('用户名不存在', 1);
                }
                if (\Ko_Mode_User::E_LOGIN_PASS == $errno) {
                    throw new \Exception('密码错误', 2);
                }
                throw new \Exception('登录失败，请重试', 2);
            }
            $uinfoApi = new \apps\user\MuserApi();
            $uinfo = $uinfoApi->getUser($uid);
            if ($uinfo['flag'] == self::FLAG_UNAUDITED) {
                throw new \Exception('您的账号还没有通过审核，请联系管理员审核。', 3);
            }
            $userApi = new \apps\user\MuserApi();
            $userApi->doAfterLogin($uid);
        } elseif ($post_style == 'register') {
            $userApi = new \apps\user\MuserApi();
            $insert['username'] = $update['username'];
            if (empty($update['username']) || empty($update['passwd'])) {
                throw new \Exception('请将注册信息填写完全', 3);
            }
            $is_reg = $api->iIsRegister($update['username']);
            if ($is_reg) {
                throw new \Exception('用户名已存在', 1);
            }
            $uid = $userApi->addUser($insert,0);//自助注册账号默认非激活状态
            if (!$uid) {
                throw new \Exception('注册失败,请重试', 2);
            } else {
                $ret = $api->bRegisterUid($uid, $update['username'], $update['passwd'], $err_no);
                if (!$ret) {
                    throw new \Exception('注册失败,请重试', $err_no);
                }
            }
        }

        return array('key' => 'login', 'after' => array('uid' => $uid, 'username' => $update['username']));
    }

    public function delete($id, $before = null)
    {
        $api = new \apps\user\MloginApi();
        $api->vSetLoginUid(0);

        return array('key' => $id);
    }
}
