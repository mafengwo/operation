<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation syetem, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\server;

class MRest_cabinet
{
    public static $s_aConf = array(
        'unique' => 'int',
        'stylelist' => array(
            'default' => 'list',
        ),
        'poststylelist' => array(
            'add_cabinet' => array(
                'hash', array(
                    'id' => 'int',
                    'idc' => 'int',
                    'cabinet' => 'string',
                    'ip' => 'list',
                ),
            ),
            'delete_cabinet' => array(
                'hash', array(
                    'tag' => 'string',
                    'id' => 'string',
                ),
            ),

        ),
    );

    public function get($id, $style = 'default')
    {
        $idc_api = new \apps\server\McabinetApi();
        $cabinet =  $idc_api->getCabinetById($id);
        if($cabinet){
            $cabinet['ip'] = \Ko_Tool_Utils::AObjs2map($idc_api->getIpByCid($cabinet['id']),'position');
            return $cabinet;
        } else {
            throw new \Exception('机柜信息不存在', 1);
        }
    }

    public function post($update, $after = null, $post_style)
    {
        $api = new \apps\server\McabinetApi();
        if ($post_style == 'add_cabinet') {
            if ($update['id']) {
                foreach ($update['ip'] as $k => $v) {
                    $nums = $api->getPosition($update['id'], $k);
                    if ($nums) {
                        if (!empty($v)) {
                            $api->iUpdateByCidAndPosition($update['id'], $k, array('ip' => $v));
                        } else {
                            $api->deleteIpByCidAndPosition($update['id'], $k);
                        }
                    } else {
                        $api->idc_ipAdd(array('ip' => $v, 'cid' => $update['id'], 'position' => $k));
                    }
                }
                $api->vUpdate($update['id'], array('cabinet' => $update['cabinet'], 'idc' => $update['idc']));
            } else {
                $cabinet = $api->getIdByCabinetAndIDC($update['cabinet'],$update['idc']);
                if (!$cabinet) {
                    $cid = $api->idc_cabinetAdd(array('cabinet' => $update['cabinet'], 'idc' => $update['idc']));
                } else {
                    throw new \Exception('机柜已存在', 3);
                }
                foreach ($update['ip'] as $k => $v) {
                    $api->idc_ipAdd(array('ip' => $v, 'cid' => $cid, 'position' => $k));
                }
            }
            return array('ret' => 1);

        } elseif ($post_style == 'delete_cabinet') {
            $api = new \apps\server\McabinetApi();
            $api->deleteCabinetById($update['id']);
            $api->deleteIpByCid($update['id']);

            return array('ret' => 1);
        }
    }
}
