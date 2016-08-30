<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation system, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\operation\server;

//uri=/server/core/nodes 节点管理
\Ko_Web_Route::VGet('nodes', function () {
    $tag_name = \Ko_Web_Request::SInput('tag_name');
    $mulit_mode = \Ko_Web_Request::IInput('mulit');
    $q['ip'] = \Ko_Web_Request::SInput('ip');
    $q['status'] = \Ko_Web_Request::SInput('status');
    $q['page'] = \Ko_Web_Request::IInput('page');
    $mulit_mode ?  $q['page'] = 0 :
    intval($q['page']) < 1 && $q['page'] = 1;
    $q['pagesize'] = 20;
    $tag_id = \Ko_Web_Request::SInput('tag_id');
    $q['tag_id'] = $tag_id ? array_unique(explode(',', $tag_id)) : array();

    if ($tag_name) {
        $tag_api = new \apps\server\MtagsApi();
        $tag_ids = $tag_api->aGetIdsByName($tag_name);
        \Ko_Web_Response::VSetRedirect('nodes?ip=&tag_id='.implode(',', $tag_ids));
        \Ko_Web_Response::VSend();
    }

    $api = new \apps\server\MApi();
    $nodes = $api->nodesListByCond($q);

    $tag_api = new \apps\server\MtagsApi();
    $tag_list = $tag_api->aGetAll();

    foreach($tag_list as &$v){
        $v['type']==\apps\server\MtagsApi::TAG_SERVICE && $v['name'] .= ':'.$v['port'];
    }

    $raw_tag = new \apps\render\Mraw();
    $raw_tag->vSetRaw(json_encode(array_values($tag_list)));

    $tagmap_api = new \apps\server\MtagmapApi();
    $tag_map = $tagmap_api->aGetAllGroupByNodeId();

    $page_bar = '批量模式不支持分页';
    if($nodes['total']){
        $page_bar = new \apps\render\Mraw();
        $page_bar->vSetRaw(\apps\system\MFunc::getPagerHtml('',array(
            'ip' => $q['ip'],
            'status' => $q['status'],
            'tag_id' => $tag_id,
        ),$q['page'],$q['pagesize'],$nodes['total']));
    }

    $master_mode = false;
    if(DISTRIBUTION_MASTER){
        $ansible_api = new \apps\ansible\MApi();
        $master_mode = $ansible_api->isalive();
    }

    $render = new \apps\render\Mweb();
    $render->oSetTemplate('server/nodes.tpl')
        ->oSetData('IMG_DOMAIN', IMG_DOMAIN)
        ->oSetData('query', $q)
        ->oSetData('nodes_list', $nodes['list'])
        ->oSetData('tag_list', $tag_list)
        ->oSetData('raw_tag', $raw_tag)
        ->oSetData('tag_type', \apps\server\MtagsApi::$aTypeName)
        ->oSetData('tag_map', $tag_map)
        ->oSetData('sum', _get_muilt_array_count($nodes['list']))
        ->oSetData('total',$nodes['total'])
        ->oSetData('pagebar',$page_bar)
        ->oSetData('mulit_mode',$mulit_mode)
        ->oSetData('master_mode',$master_mode)
        ->oSend();
});

//uri=/server/core/tag 标签管理
\Ko_Web_Route::VGet('tag', function () {
    $q['page'] = \Ko_Web_Request::IInput('page');
    $q['type'] = \Ko_Web_Request::IInput('type');
    $q['name'] = \Ko_Web_Request::SInput('name');
    $q['page'] = \Ko_Web_Request::IInput('page');
    intval($q['page']) < 1 && $q['page'] = 1;
    $q['pagesize'] = 20;
    $tags_api = new \apps\server\MtagsApi();
    $tags = $tags_api->aGetByCond($q);

    $api = new \apps\server\MApi();
    $stat_num = $api->getOnlineStatNumByTag();

    $page_bar = new \apps\render\Mraw();
    $page_bar->vSetRaw(\apps\system\MFunc::getPagerHtml('',array(
        'type' => $q['type'],
        'name' => $q['name'],
    ),$q['page'],$q['pagesize'],$tags['total']));

    $render = new \apps\render\Mweb();
    $render->oSetTemplate('server/tag.tpl')
        ->oSetData('IMG_DOMAIN', IMG_DOMAIN)
        ->oSetData('query', $q)
        ->oSetData('stat_num', $stat_num)
        ->oSetData('pagebar', $page_bar)
        ->oSetData('total', $tags['total'])
        ->oSetData('tag_list', $tags['list'])
        ->oSetData('tag_type', \apps\server\MtagsApi::$aTypeName)
        ->oSend();
});

//count array
function _get_muilt_array_count($arr)
{
    $i = 0;
    $j = 0;
    foreach ($arr as $v) {
        ++$i;
        foreach ($v as $vv) {
            ++$j;
        }
    }

    return array($i, $j);
}
