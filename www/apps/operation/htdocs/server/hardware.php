<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation system, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: XieZe <xieze@mafengwo.com>.
 */

namespace apps\operation\server;

\Ko_Web_Route::VGet('cabinet', function () {
    $idc = \apps\server\MConf::$IDC_NAME;
    $cabinet_nums = 0;
    $hosts_nums = 0;
    $q['ip'] = \Ko_Web_Request::SInput('ip');
    $q['idc'] = \Ko_Web_Request::IInput('idc');

    $api = new \apps\server\McabinetApi();
    if (!empty($q['idc'])) {
        $cabinetAndIdc = $api->getAllCabinetByIdc($q['idc']);
    } else {
        $cabinetAndIdc = $api->getAllCabinet();
    }
    foreach ($cabinetAndIdc as $k => $v) {
        ++$cabinet_nums;
        $idc_ip = $api->getIpByCid($v['id']);
        foreach ($idc_ip as $key => $val) {
            ++$hosts_nums;
            $data['ip'][$v['cabinet']][$val['position']] = $val['ip'];
        }
        $data['cabinet'][] = $v['cabinet'];
        $data['idc'][$v['cabinet']] = $v['idc'];
        $data['id'][$v['cabinet']] = $v['id'];
    }

    $render = new \apps\render\Mweb();
    $render->oSetTemplate('server/cabinet.tpl')
        ->oSetData('IMG_DOMAIN', IMG_DOMAIN)
        ->oSetData('MAX_IP_PER_CABINET', \apps\server\MConf::MAX_IP_PER_CABINET)
        ->oSetData('idc', $idc)
        ->oSetData('query', $q)
        ->oSetData('cabinet_nums', $cabinet_nums)
        ->oSetData('hosts_nums', $hosts_nums)
        ->oSetData('data', $data)
        ->oSend();
});

\Ko_Web_Route::VGet('assets', function () {

    $idc = \apps\server\MConf::$IDC_NAME;
    $pagesize = 20;
    $page = \Ko_Web_Request::IInput('page');
    $q['idc'] = \Ko_Web_Request::IInput('idc');
    $q['ip'] = \Ko_Web_Request::SInput('ip');
    $page == 0 && $page = 1;

    $api = new \apps\server\MassetsApi();
    $app = new \apps\server\McabinetApi();

    $result = array();
    if($q['ip'] || $q['idc']) {
        $result1 = $result2 = array();
        $q['ip'] && $result1 = $app->getOneIp($q['ip']);
        $q['idc'] && $result2 = $app->getIpByIdc($q['idc']);
        if($q['ip'] && $q['idc']) {
            $result1 = \Ko_Tool_Utils::AObjs2map($result1,'id');
            $result2 = \Ko_Tool_Utils::AObjs2map($result2,'id');
            $result = array_intersect_key($result1,$result2);
        } else {
            $result = count($result1) ? $result1 : $result2;
        }
    } else {
        $result = $app->getAllIp();
    }

    $cabinet_raw = \Ko_Tool_Utils::AObjs2map($result,'id');
    $idc_raw = \Ko_Tool_Utils::AObjs2map($app->getAllCabinet(),'id');
    $assets_raw = $api->aGetAll();

    $assets_data = array();

    foreach($assets_raw as $k => $a){
        $pid = $a['pid'];
        if($cabinet_raw[$pid]){
            $cid = $cabinet_raw[$pid]['cid'];
            $cabinet_idc = $idc_raw[$cid];
            $a['idc'] = $cabinet_idc['idc'];
            $a['cabinet'] = $cabinet_idc['cabinet'];
            $a['ip'] = $cabinet_raw[$pid]['ip'];
            unset($cabinet_raw[$pid]);
            $assets_data[] = $a;
        } else {
            unset($assets_raw[$k]);
        }
    }
    $total = count($assets_data);
    $offset = ($page - 1) * $pagesize;
    $assets_data = array_slice($assets_data,$offset,$pagesize);
    $holdon_data = $cabinet_raw;

    $page_bar = new \apps\render\Mraw();
    $page_bar->vSetRaw(\apps\system\MFunc::getPagerHtml('', $q, $page, $pagesize, $total));
    $render = new \apps\render\Mweb();
    $render->oSetTemplate('server/assets.tpl')
        ->oSetData('IMG_DOMAIN', IMG_DOMAIN)
        ->oSetData('idc', $idc)
        ->oSetData('assets_data', $assets_data)
        ->oSetData('holdon_data', $holdon_data)
        ->oSetData('pagebar', $page_bar)
        ->oSetData('total', $total)
        ->oSetData('query', $q)
        ->oSend();
});
