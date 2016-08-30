<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation syetem, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\salt;

class MRest_cmd
{
    public static $s_aConf = array(
    'unique' => 'string',
    'poststylelist' => array(
      'default' => array(
        'hash', array(
          'ip' => 'string',
          'command' => 'string',
          'type' => 'int',
        ),
      ),
    ),
    'stylelist' => array(
      'default' => array(
        'list', array('hash', array(
          'ip' => 'string',
          'ret' => 'string', ),
        ),
      ),
    ),

  );

    public function post($update)
    {
        $api = new \apps\salt\MApi();
      //array('mods'=>'nginx.nginx','saltenv'=>'dev')
      // $x = $api->runner('state.orchestrate_high', array(
      //   'data' => array('one'=>array('salt.state'=>array(array('tgt'=>'172.18.8.13'),array('sls'=>'nginx.nginx'),
      //   array('saltenv'=>'dev')
      // )))
      // ));
      // print_r($x);die;
        $funs = array(1 => 'runCmd', 2 => 'runIns', 3 => 'runScript');
        $fun = $funs[$update['type']];
        if (empty($fun)) {
            throw new \Exception('缺少命令类型', 3);
        }
        if (empty($update['ip']) || empty($update['command'])) {
            throw new \Exception('请将信息填写完全', 1);
        }
        $ip = array();
        if (!ip2long($update['ip'])) { //角色名
            $server_api = new \apps\server\MApi();
            $ips = $server_api->getIpsByRole($update['ip']);
            if (count($ips) == 0) {
                throw new \Exception('此角色下无在线IP', 2);
            }
        } else {
            $ips[] = $update['ip'];
        }
        $jobid = '';
        $api = new \apps\salt\MApi();
        $rs = array();
        foreach ($ips as $ip) {
            $tmp = $api->$fun($ip, $update['command']);
            $rs = array_merge($rs, $tmp);
        }

        $list = array();
        foreach ($rs as $k => $v) {
            $list[] = array(
          'ip' => $k,
          'ret' => strip_tags($v),
        );
        }

        return array('key' => $jobid, 'after' => $list);
    }
}
