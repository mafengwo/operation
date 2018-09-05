<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation syetem, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: jichen zhou.
 */

namespace apps\user;

class MloginApi extends \Ko_Mode_User
{
    const SESSION_TOKEN_NAME = 'token';
    const PERSISTENT_TOKEN_NAME = 'p';

    protected $_aConf = array(
        'username' => 'username',
        'hashpass' => 'hashpass',
        'varsalt' => 'varsalt',
    );

    public function iGetLoginUid(&$exinfo = '')
    {
        static $s_iUid;
        if (is_null($s_iUid)) {
            $token = \Ko_Web_Request::SCookie(self::SESSION_TOKEN_NAME);
            $s_iUid = $token ? $this->iCheckSessionToken($token, $exinfo, $iErrno) : 0;
            if (!$s_iUid) {
                $token = \Ko_Web_Request::SCookie(self::PERSISTENT_TOKEN_NAME);
                $s_iUid = $token ? $this->iCheckPersistentToken($token, $newtoken, $iErrno) : 0;

                //print_r($token);die;
                if ($s_iUid) {
                    \Ko_Web_Response::VSetCookie(self::PERSISTENT_TOKEN_NAME, $newtoken, time() + 2592000, '/', '.'.MAIN_DOMAIN);
                }
            }
            if ($s_iUid) {
                $token = $s_iUid ? $this->sGetSessionToken($s_iUid, $exinfo) : '';
                \Ko_Web_Response::VSetCookie(self::SESSION_TOKEN_NAME, $token, 0, '/', '.'.MAIN_DOMAIN);
            }
        }

        return $s_iUid;
    }

    public function vSetLoginUid($uid, $exinfo = '')
    {
        $token = $uid ? $this->sGetSessionToken($uid, $exinfo) : '';
        \Ko_Web_Response::VSetCookie(self::SESSION_TOKEN_NAME, $token, 0, '/', '.'.MAIN_DOMAIN);

        $token = $uid ? $this->sGetPersistentToken($uid) : '';
        \Ko_Web_Response::VSetCookie(self::PERSISTENT_TOKEN_NAME, $token, time() + 2592000, '/', '.'.MAIN_DOMAIN);
    }

    public function iOauth2Login($sSrc)
    {
        $aTokeninfo = $this->oauth2_Api->aGetTokenInfo($sSrc);
        if (!$this->oauth2_Api->bGetUserinfoByTokeninfo($sSrc, $aTokeninfo, $sUsername, $aUserinfo)) {
            return 0;
        }
        $uid = $this->_iGetExternalUid($sUsername, $sSrc);
        if ($uid) {
            $this->oauth2_Api->bSaveUserToken($sSrc, $uid, $aTokeninfo);
            $this->baseinfoApi->bUpdateOauth2info($uid, $aUserinfo);
        }

        return $uid;
    }

    private function _iGetExternalUid($sUsername, $sSrc)
    {
        $uid = $this->iRegisterExternal($sUsername, $sSrc, $iErrno);
        if (!$uid && \Ko_Mode_User::E_REGISTER_ALREADY == $iErrno) {
            $uid = $this->iIsRegister($sUsername, $sSrc);
        }

        return $uid;
    }
}
