<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation system, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\ansible;

class MConf
{
    //ansible api uri
    const INTERFACE_URL = 'http://127.0.0.1:8765';
    //ansible api signature
    const SIGN_KEY = 'YOUR_SIGNATURE_KEY_HERE';
    //path of shell scripts for ansible
    const SCRIPT_PATH = '/data/ansible/scripts/';
    //the host name 'all' follows hosts config at /etc/ansibe/hosts, false is follows server nodes in mysql
    //all组成员 true: 为所有配置文件中定义的服务器, false: 为节点列表中的上线服务器
    const HOSTNAME_ALL_DEPEND_ON_ANSIBLE_CONF = false;

    //playbook alias
    //playbook 别名 为真实的playbook提供参数组合 作为别名提供快捷操作使用
    public static $PB_ALIAS = array(
        //'alias.yml' => array('realname.yml',array('argv1'=>'argument1 value for realname.yml','#c'=>'number of concurrent')),//sample
    );
}
