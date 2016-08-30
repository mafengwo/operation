<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation system, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\ansible;

class MRest_cmd
{
    public static $s_aConf = array(
    'unique' => 'string',
    'poststylelist' => array(
        'default' => array('hash', array(
            'ip' => 'string',
            'command' => 'string',
            'type' => 'int',
            'sudo' => 'int',
            'vars' => 'array',
        )),
        'base' => array('hash', array(
            'task_id' => 'int',
            'parameter' => 'string',
            'vars' => 'array',
        )),
    ),
    'stylelist' => array(
        'default' => array(
            'list', array('hash', array(
                'ip' => 'string',
                'ret' => 'string',
                'rc' => 'int',
                'detail' => array('array', array('hash', array(
                    'task' => 'string',
                    'cmd' => 'string',
                    'changed' => 'int',
                    'out' => 'string',
                    'rc' => 'int',
                    'muiltout' => 'int',
                            ))),
                )), ),
            'base' => array('list', array('hash', array(
                'ip' => 'string',
                'ret' => 'string',
                'rc' => 'int',
                'detail' => array('array', array('hash', array(
                    'task' => 'string',
                    'cmd' => 'string',
                    'changed' => 'int',
                    'out' => 'string',
                    'rc' => 'int',
                    'muiltout' => 'int',
                ))),
             ))),
    ),
  );

    public function post($update, $after = null, $post_style = 'default')
    {
        if ($after['style'] == 'base') { //任务模式 从任务配置中获取操作参数
            $update['vars'] = $update['vars'] ? array_filter($update['vars']) : array();
            $task_privacy = new \apps\server\MtaskprivacyApi();
            if ($task_privacy->bHasTaskPriByUid($update['task_id'])) {
                $task_api = new \apps\server\MremotetaskApi();
                $task_info = $task_api->aGet($update['task_id']);
                $update['type'] = $task_info['type'];
                $update['command'] = $task_info['command'];
                trim($update['parameter'])!='' && $update['command'] .= ' '.trim($update['parameter']);
                $update['ip'] = $task_info['ip'];
                $update['sudo'] = $task_info['sudo'];
                $tmp = json_decode($task_info['vars'], true);
                $vars = $tmp ? array_filter($tmp) : array();
                $update['vars'] = array_merge($update['vars'],$vars);
            } else {
                throw new \Exception('对不起，您没有操作这个任务的权限', 4);
            }
        }
        $funs = array(1 => 'shell', 2 => 'playbook', 3 => 'script');
        $fun = $funs[$update['type']];
        if ($update['type'] == 3) {
            $tmp = explode(' ',$update['command'],2);
            substr($tmp[0], -3) != '.sh' && $update['command'] = $tmp[0].'.sh';
            $update['command'] = \apps\ansible\MConf::SCRIPT_PATH.$update['command'];
            trim($tmp[1])!='' && $update['command'] .= ' '.trim($tmp[1]);
        }
        //todo 部分用户不允许执行的shell含脚本中的 'mv','rm', 'kill', 'pkill'
        if (empty($fun)) {
            throw new \Exception('缺少命令类型', 3);
        }
        if (empty($update['ip']) || empty($update['command'])) {
            throw new \Exception('请将信息填写完全', 1);
        }
        $ip = array();
        $tag = '';
        $server_api = new \apps\server\MApi();
        $target = explode('-', $update['ip'], 2);
        switch ($target[0]) {
            case 'tag':
                $ips = $server_api->getIpsByTagId($target[1]);
                if (count($ips) == 0) {
                    throw new \Exception('此标签下无在线IP', 2);
                } else {
                    $tag_api = new \apps\server\MtagsApi();
                    $tag_info = $tag_api->aGet($target[1]);
                    $tag = $tag_info['name'];//将标签名记录下来 以便后续使用
                }
                break;

            case 'ip':
                $ips[] = $target[1];
                break;
        }

        $host = implode(',', $ips);
        $ext_vars = $update['vars'] ? $update['vars'] : array();

        $api = new \apps\ansible\MApi();
        $rs = array();
        if ($fun == 'playbook') {
            $docker_ips = $server_api->getIpsByTag('docker', \apps\server\MtagsApi::TAG_SYSTEM);
            $parts = $this->_array1to2($ips, $docker_ips);//将所有ip分为docker组合非docker组
            foreach ($parts as $k => $part) {
                $ext_vars['container_name'] = ($k == 'hit' && $tag) ? $tag : 'null';//docker组加入容器名称 否则置为null
                $tasks[] = $api->dealwith_alias_in_task(array(
                    'host' => implode(',', $part),
                    'file' => $update['command'].'.yml',
                    'vars' => $ext_vars,
                ));
            }

            $tmp = $api->playbooks($tasks);//多次运行playbook
            foreach ($tmp as $v) {
                $rs = array_merge($rs, $v);//结果合并
            }
        } else {
            $rs = $api->runner($host, $fun, $update['command'], $update['sudo']);
        }

        return array('key' => '', 'after' => $rs);
    }

    private function _array1to2(array $arr, array $std)
    {
        $data = array('hit' => array(), 'miss' => array());
        foreach ($arr as $v) {
            if (in_array($v, $std)) {
                $data['hit'][] = $v;
            } else {
                $data['miss'][] = $v;
            }
        }
        if (empty($data['hit'])) {
            unset($data['hit']);
        }

        if (empty($data['miss'])) {
            unset($data['miss']);
        }

        return $data;
    }
}
