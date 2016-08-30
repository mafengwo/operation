<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation system, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\operation\server;

//uri=/server/remote/index 远程管理
\Ko_Web_Route::VGet('index', function () {
    recognize_ansible();
    $api = new \apps\server\MnodesApi();
    $ip_list = $api->getAllNodeIp();

    $tag_api = new \apps\server\MtagsApi();
    $tag_list = $tag_api->aGetByType(array(
        \apps\server\MtagsApi::TAG_SYSTEM,
        \apps\server\MtagsApi::TAG_SERVICE,
        \apps\server\MtagsApi::TAG_BIZ,
    ));

    $loginApi = new \apps\user\MloginApi();
    $uid = $loginApi->iGetLoginUid();
    $userApi = new \apps\user\MuserApi();
    $logininfo = $userApi->getUser($uid);

    $playbook = get_playbook_list(false);
    $raw_pb = new \apps\render\Mraw();
    $raw_pb->vSetRaw(json_encode($playbook));

    $script = get_script_list(false);
    $raw_spt = new \apps\render\Mraw();
    $raw_spt->vSetRaw(json_encode($script));

    $render = new \apps\render\Mweb();
    $render->oSetTemplate('server/remote.tpl')
        ->oSetData('IMG_DOMAIN', IMG_DOMAIN)
        ->oSetData('TAG_SERVICE', \apps\server\MtagsApi::TAG_SERVICE)
        ->oSetData('tag_list', $tag_list)
        ->oSetData('ip_list', $ip_list)
        ->oSetData('play_json', $raw_pb)
        ->oSetData('script_json', $raw_spt)
        ->oSetData('logininfo', $logininfo)
        ->oSend();
});

//uri=/server/remote/index 远程管理
\Ko_Web_Route::VGet('base', function () {
    recognize_ansible();
    $loginApi = new \apps\user\MloginApi();
    $uid = $loginApi->iGetLoginUid();
    $userApi = new \apps\user\MuserApi();
    $logininfo = $userApi->getUser($uid);

    $task_privacy = new \apps\server\MtaskprivacyApi();
    $task_id = $task_privacy->aGetTaskPriByUid($uid);
    $task_api = new \apps\server\MremotetaskApi();
    $task = $task_api->aGetValidByIds($task_id);

    $render = new \apps\render\Mweb();
    $render->oSetTemplate('server/remote_base.tpl')
    ->oSetData('IMG_DOMAIN', IMG_DOMAIN)
    ->oSetData('task', $task)
    ->oSetData('logininfo', $logininfo)
    ->oSend();
});

//uri=/server/remote/playbook ansible playbook 管理
\Ko_Web_Route::VGet('playbook', function () {
    recognize_ansible();
    $list = get_playbook_list();
    $render = new \apps\render\Mweb();
    //$render->vSetCommon(array('header_navhide' => true));
    $render->oSetTemplate('server/script.tpl')
        //->oSetData('IMG_DOMAIN', IMG_DOMAIN)
        ->oSetData('show_nav', 0)
        ->oSetData('list', $list)
        ->oSetData('type', 'playbook')
        ->oSend();
});

//uri=/server/remote/script ansible shell脚本管理
\Ko_Web_Route::VGet('script', function () {
    recognize_ansible();
    $list = get_script_list();
    $render = new \apps\render\Mweb();
    //$render->vSetCommon(array('header_navhide' => true));
    $render->oSetTemplate('server/script.tpl')
        ->oSetData('IMG_DOMAIN', IMG_DOMAIN)
        ->oSetData('list', $list)
        ->oSetData('type', 'script')
        ->oSend();
});

//uri=/server/remote/authkeys ansible 远程认证公钥管理
\Ko_Web_Route::VGet('authkeys', function () {
    recognize_ansible();
    $api = new \apps\ansible\MApi();
    $rst = $api->file_list('authkeys');
    $list = array();

    if ($rst['list']) {
        foreach ($rst['list'] as $v) {
            $fisrt = substr($v, 0, 1);
            $list[$fisrt][] = $v;
        }
    }
    $list = format_script_data($list);
    $render = new \apps\render\Mweb();
    //$render->vSetCommon(array('header_navhide' => true));
    $render->oSetTemplate('server/script.tpl')
        ->oSetData('IMG_DOMAIN', IMG_DOMAIN)
        ->oSetData('list', $list)
        ->oSetData('type', 'authkeys')
        ->oSend();
});

//uri=/server/remote/task ansible任务管理
\Ko_Web_Route::VGet('task', function () {
    recognize_ansible();
    $api = new \apps\server\MremotetaskApi();
    $list = $api->aAllList();

    $user_api = new \apps\user\MuserApi();
    $users = \Ko_Tool_Utils::AObjs2map($user_api->getAllUsers(), 'id');

    $pri_api = new \apps\server\MtaskprivacyApi();
    $privacy = $pri_api->aGetUidGroupByTask();
    $render = new \apps\render\Mweb();
    $render->oSetTemplate('server/task.tpl')
        ->oSetData('IMG_DOMAIN', IMG_DOMAIN)
        ->oSetData('users', $users)
        ->oSetData('list', $list)
        ->oSetData('privacy', $privacy)
        ->oSetData('types', array(1 => 'Shell命令', 2 => 'Playbook', 1 => 'Shell脚本'))
        ->oSend();
});

function recognize_ansible()
{
    $api = new \apps\ansible\MApi();
    if(!$api->isalive()){
        $msg = '本功能依赖Ansible，但并没有发现可用的<a href="https://github.com/mafengwo/ansible-api" target="_blank">Ansible-API</a>，请安装后在 apps/ansible/Conf.php 中做相应配置以启用。';
        $raw_html = new \apps\render\Mraw();
        $raw_html->vSetRaw($msg);
        $render = new \apps\render\Mweb();
        $render->oSetTemplate('common/forbidden.tpl')
            ->oSetData('IMG_DOMAIN', IMG_DOMAIN)
            ->oSetData('message', $raw_html)
            ->oSend();
        exit;
    }
}

//sort for array
function format_script_data($list)
{
    foreach ($list as &$v) {
        sort($v);
    }
    ksort($list);

    return $list;
}

//get playbook data
function get_playbook_list($divide = true)
{
    $api = new \apps\ansible\MApi();
    $rst = $api->file_list('playbook');
    $list = array();//供给playbook管理的列表
    $flat = array();//供给select2的列表(已做别名处理)

    $pb_alias = \apps\ansible\MApi::get_alias_by_real_pb();

    if ($rst['list']) {
        foreach ($rst['list'] as $v) {
            $fisrt = substr($v, 0, 1);
            if (substr($v, -4) == '.yml') { //别名处理
                if ($pb_alias[$v]) {
                    $flat = array_merge($flat, $pb_alias[$v]);
                } else {
                    $flat[] = str_replace('.yml', '', $v);
                }
                $list[$fisrt][] = $v;
            }
        }
    }

    return $divide ? format_script_data($list) : $flat;
}

//get script data
function get_script_list($divide = true)
{
    $api = new \apps\ansible\MApi();
    $rst = $api->file_list('script');
    $list = array();
    $flat = array();
    if ($rst['list']) {
        foreach ($rst['list'] as $v) {
            $fisrt = substr($v, 0, 1);
            if (substr($v, -3) == '.sh') {
                $flat[] = str_replace('.sh', '', $v);
                $list[$fisrt][] = $v;
            }
        }
    }

    return $divide ? format_script_data($list) : $flat;
}
