<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation system, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\ansible;

class MApi extends \Ko_Busi_Func
{
    const STAT_SUCC = 200;

    public static function get_alias_by_real_pb()
    {
        $data = array();
        foreach (MConf::$PB_ALIAS as $k => $v) {
            $data[$v[0]][] = substr($k, -4) == '.yml' ? substr($k, 0, -4) : $k;
        }

        return $data;
    }

    public static function dealwith_alias_in_task(array $task)
    {
        $alias = MConf::$PB_ALIAS[$task['file']];
        if (isset($alias)) {
            $task['file'] = $alias[0];
            $task['vars'] = array_merge($task['vars'], $alias[1]);
        }

        return $task;
    }

    public function __construct()
    {
        //获取管理员信息 依赖system/user模块
        $loginApi = new \apps\user\MloginApi();
        $this->admin_uid = $loginApi->iGetLoginUid($uinfo);
        if (!$this->admin_uid) {
            exit('access forbidden');
        }
    }

    public function isalive()
    {
        $data = $this->_run('/', array());
        return $data['rc'] === 0 ;
    }

    public function file_exist($filename)
    {
        $path_info = explode('|', $filename, 2);
        $sign = md5($path_info[0].$path_info[1].MConf::SIGN_KEY);
        $query = array(
          'type' => $path_info[0],
          'name' => $path_info[1],
          'sign' => $sign,
        );

        return $this->_run('/filexist?'.http_build_query($query), array());
    }

    public function parse_vars($filename)
    {
        $alias = MConf::$PB_ALIAS[$filename];
        isset($alias) && $filename = $alias[0];

        $sign = md5($filename.MConf::SIGN_KEY);
        $query = array(
          'name' => $filename,
          'sign' => $sign,
        );
        $list = $this->_run('/parsevars?'.http_build_query($query), array());
        if ($list['vars'] && is_array($alias[1]) && count($alias[1])) {
            $skip_keys = array_keys($alias[1]);
            $list['vars'] = array_diff($list['vars'], $skip_keys);
        }

        return $list;
    }

    public function file_list($path)
    {
        $sign = md5($path.MConf::SIGN_KEY);

        return $this->_run('/filelist?type='.$path.'&sign='.$sign, array());
    }

    public function file_read($filename)
    {
        $path_info = explode('|', $filename, 2);
        $path_info[0] == 'authkeys' && $path_info[1] .= '/authorized_keys';//authkeys特殊规则
        $sign = md5($path_info[0].$path_info[1].MConf::SIGN_KEY);
        $query = array(
          'type' => $path_info[0],
          'name' => $path_info[1],
          'sign' => $sign,
        );

        return $this->_run('/fileitem?'.http_build_query($query), array());
    }

    public function file_write($path, $file, $content)
    {
        $path == 'authkeys' && $file .= '/authorized_keys';//authkeys特殊规则
        $post = array(
          'p' => $path,
          'f' => $file,
          'c' => $content,
        );
        $post['s'] = md5($path.$file.MConf::SIGN_KEY);

        return $this->_run('/fileitem', $post);
    }

    public function ping()
    {
        $post = array(
          'm' => 'ping',
          't' => 'all',//the sum of targets must be less then 512
          'c' => '256',
          'a' => '',
          'r' => '',
        );

        $post['s'] = md5($post['m'].$post['t'].MConf::SIGN_KEY);
        $rst = $this->_run('/command', $post);
        $data = array('unreachable' => array(), 'connected' => array());
        if(is_array($rst['detail'])){
            foreach ($rst['detail'] as $ip => $d) {
                if ($d[0]['unreachable']) {
                    $data['unreachable'][] = $ip;
                } elseif ($d[0]['ping'] == 'pong') {
                    $data['connected'][] = $ip;
                }
            }
        }
        return $data;
    }

    public function runner($target, $module, $arg, $sudo)
    {
        $ip_list = $target;
        if ($target == 'all' && !MConf::HOSTNAME_ALL_DEPEND_ON_ANSIBLE_CONF) {
            $server_api = new \apps\server\MnodesApi();
            $ip_list = implode(',', $server_api->getAllNodeIp());
        }
        $post = array(
          'm' => $module,
          'a' => $arg, //todo 限制(提示) rm 操作
          't' => $ip_list,
          'r' => $sudo,
        );
        $post['s'] = md5($module.$ip_list.MConf::SIGN_KEY);
        $rst = $this->_run('/command', $post);
        //print_r($rst);
        list($rc, $data) = $this->_parse_result($rst, $target);
        $this->log($module, $target, $arg, $data, $rc);

        return $data;
    }

    //批量执行多个palybook
    public function playbooks(array $tasks)
    {
        $all_data = array();
        foreach ($tasks as $key => $task) {
            if ($task['host'] && $task['file'] && is_array($task['vars'])) {
                $all_data[$key] = $this->playbook($task['host'], $task['file'], $task['vars']);
            }
        }

        return $all_data;
    }

    public function playbook($host, $file, array $vars = array())
    {
        $ip_list = $host;

        if ($host == 'all' && !MConf::HOSTNAME_ALL_DEPEND_ON_ANSIBLE_CONF) {
            $server_api = new \apps\server\MnodesApi();
            $ip_list = implode(',', $server_api->getAllNodeIp());
        }
        $post = array(
          'h' => $ip_list,
          'f' => $file,
        );

        //处理特殊配置
        if ($vars['#c'] > 0) {
            //并发数
          $post['c'] = $vars['#c'];
            unset($vars['#c']);
        }
        $post['s'] = md5($ip_list.$file.MConf::SIGN_KEY);
        if (count($vars)) {
            foreach ($vars as $k => $v) {
                $post['v_'.$k] = $v;
            }
        }
        $rst = $this->_run('/playbook', $post);
        //print_r($rst);
        list($rc, $data) = $this->_parse_result($rst, $host);
        $this->log('playbook', $host, $file, $data, $rc);

        return $data;
    }

    //解析api返回值
    private function _parse_result($rst, $host)
    {
        $data = array();
        $rc = 0;
        if ($rst['panic']) {
            $rc = 109;
            $data[] = array('rc' => $rc, 'ip' => $host, 'ret' => '[Error] Ansible-API not works');
        } elseif ($rst['error']) {
            $rc = 108;
            $data[] = array('rc' => $rc, 'ip' => $host, 'ret' => '[Fatal Error] '.$rst['error']);
        } elseif ($rst['detail']) {
            foreach ($rst['detail'] as $k => $v) {
                $single = array();
                foreach ($v as $vv) {
                    $out = '';
                    $rc += $vv['rc'];
                    if ($vv['task_name']) {
                        if ($vv['skipped']) { //捕捉skip
                $out = '[Skip Reason]'.$vv['skip_reason'];
                        } elseif ($vv['unreachable']) { //捕捉unreachable
                $out = '[Unreachable Reason]'.$vv['msg'];
                            $vv['rc'] = 106;
                        } elseif ($vv['failed']) { //捕捉失败
                $out = $vv['msg'];
                            $vv['rc'] = 105;
                        }
                        empty($out) && ($out = $vv['stdout'] ? strip_tags($vv['stdout']) : strip_tags($vv['stderr']));

                        $single[] = array(
                'cmd' => is_array($vv['cmd']) ? implode(' ', $vv['cmd']) : $vv['cmd'],
                'out' => $out, 'task' => $vv['task_name'], 'rc' => $vv['rc'],
                'muiltout' => (is_array($vv['stdout_lines']) && count($vv['stdout_lines']) > 1) ? 1 : 0, );
                    }
                }
                $data[] = array('rc' => 0, 'ip' => $k, 'ret' => '', 'detail' => $single);
            }
        }
        if (empty($data)) {
            $rc = 107;
            $data[] = array('rc' => $rc, 'ip' => $target, 'ret' => '[Ansible Error] None of return');
        }

        return array($rc, $data);
    }

    private function _run($cmd, $post)
    {
        list($status, $body) = self::curl($cmd, $post);
        $rst = json_decode($body, true);
        if (self::STAT_SUCC == $status && $rst) {
            return json_decode($body, true);
        } else {
            return $body ? array('error' => $body) : array('panic' => true);
        }
    }

    private function log($type, $target, $command, $result, $rc = 0)
    {
        if (is_array($target)) {
            $target = implode(';', $target);
        }
        $result = json_encode($result);

        return $this->remotelogDao->iInsert(array(
        'uid' => $this->admin_uid,
        'type' => $type,
        'target' => $target,
        'command' => $command,
        'result' => base64_encode($result),
        'rc' => $rc,
      ));
    }

    private static function curl($act, array $post)
    {
        //$post_str = $post ? http_build_query($post) : '';
        $post_str = $post ? json_encode($post) : '';
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 180);//http超时时间
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curl, CURLOPT_URL, MConf::INTERFACE_URL.$act);

        // $headers = array('Accept: application/json');
        // $headers = array('Content-Type: application/json');
        // $token && $headers[] = 'X-Auth-Token:'.$token;
        // curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        if ($post_str) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post_str);
        }

        $response = curl_exec($curl);

        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $body = substr($response, $headerSize);

        //if (self::STAT_SUCC == $code) {
            //$tmp = json_decode($body, true);
            //$body = $tmp['return'][0];
        //}

        return array($code, $body);
    }
}
