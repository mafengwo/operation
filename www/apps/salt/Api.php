<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation syetem, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

//A past scheme achieved remote by using saltstack, but instead of ansible now
namespace apps\salt;

class MApi extends \Ko_Busi_Api
{
    const INTERFACE_URL = 'http://127.0.0.1:4507';
    const USERNAME = 'saltapi';
    const PASSWORD = 'saltNoPassapi';
    const AUTHWAY = 'pam';
    const TOKEN_MCKEY = 'saltapi_token';
    const STAT_SUCC = 200;
    const SALT_SCRIPT_PATH = '/srv/salt/scripts/';

    public function runCmd($ip, $cmd)
    {
        return $this->_run(array('client' => 'local', 'tgt' => $ip, 'fun' => 'cmd.run', 'arg' => $cmd));
    }

    public function runIns($ip, $cmd)
    {
        return $this->_run(array('client' => 'local', 'tgt' => $ip, 'fun' => $cmd));
    }

    public function runScript($ip, $file)
    {
        $ret = $this->_run(array('client' => 'local', 'tgt' => $ip, 'fun' => 'cmd.script', 'arg' => $file));
        $data = array();
        foreach ($ret as $k => $v) {
            $data[$k] = $v['retcode'] == 0 ? $v['stdout'] : $v['stderr'];
        }

        return $data;
    }

    public function runner($fun, array $args = array())
    {
        return $this->_run(array_merge(
      array('client' => 'runner', 'fun' => $fun), $args));
        //$this->_run(array('client'=>'runner','fun'=>'fileserver.file_list'));
    }

    public function wheel($fun, array $args = array())
    {
        return $this->_tiny_wheel($this->_run(array_merge(
        array('client' => 'wheel', 'fun' => $fun), $args)));
      //$x = $this->_run(array('client'=>'wheel','fun'=>'file_roots.read','path'=>'/srv/salt/scripts/test.sh'));
    }

    private function _tiny_wheel($rs)
    {
        if ($rs['data']['success']) {
            return is_array($rs['data']['return']) && isset($rs['data']['return'][0]) ?
            $rs['data']['return'][0] : $rs['data']['return'];
        } else {
            return array();
        }
    }

    private function _run($cmd)
    {
        list($status, $body) = self::curl('/', $cmd, $this->getToken());

        return self::STAT_SUCC == $status ? $body : array();
    }

    public function getMinions($id = '')
    {
        $id = $id ? '/'.$id : '';
        list($status, $body) = self::curl('/minions'.$id, array(), $this->getToken());

        return self::STAT_SUCC == $status ? $body : array();
    }

    public function getJobs($id = '')
    {
        $id = $id ? '/'.$id : '';
        list($status, $body) = self::curl('/jobs'.$id, array(), $this->getToken());

        return self::STAT_SUCC == $status ? $body : array();
    }

    public function getToken()
    {
        $ret = $this->mcDao->vGetObj(self::TOKEN_MCKEY);
        if (empty($ret) || $ret['expire'] < time()) {
            list($status, $info) = self::curl('/login', array(
        'eauth' => self::AUTHWAY,
        'username' => self::USERNAME,
        'password' => self::PASSWORD,
      ));
            if (self::STAT_SUCC == $status) {
                $this->mcDao->bSetObj(self::TOKEN_MCKEY, array(
          'start' => intval($info['start']),
          'token' => $info['token'],
          'expire' => intval($info['expire']),
        ));

                return $info['token'];
            } else {
                return fasle;
            }
        } else {
            return $ret['token'];
        }
    }

    private static function curl($act, array $post, $token = '')
    {
        //$post_str = $post ? http_build_query($post) : '';
        $post_str = $post ? json_encode($post) : '';
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curl, CURLOPT_URL, self::INTERFACE_URL.$act);

        $headers = array('Accept: application/json');
        $headers = array('Content-Type: application/json');
        $token && $headers[] = 'X-Auth-Token:'.$token;
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        if ($post_str) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post_str);
        }

        $response = curl_exec($curl);

        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $body = substr($response, $headerSize);
        if (self::STAT_SUCC == $code) {
            $tmp = json_decode($body, true);
            $body = $tmp['return'][0];
        }

        return array($code, $body);
    }
}
