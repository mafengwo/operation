<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation system, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\ansible;

class MRest_ping
{
    public static $s_aConf = array(
        'unique' => 'string',
        'stylelist' => array(
            'default' => array('array'),
        ),
    );

    public function get($id, $style = 'default')
    {
        $api = new \apps\ansible\MApi();
        $data = $api->ping();
        if(count($data['connected']) + count($data['unreachable']) == 0){
            throw new \Exception('系统繁忙，请稍后再试', 1);
        }
        $server_api = new \apps\server\MnodesApi();
        $option = new \Ko_Tool_SQL();
        $option->oWhere('status = 1');
        $slist = array_keys($server_api->getNodesByCond($option,'identification'));
        $vips = array_keys($server_api->getVirtualIps());
        $slist = array_diff($slist,$vips);
        $unreachable = array();

        foreach ($slist as $ip) {
            $idx = array_search($ip, $data['connected']);
            if ($idx !== false) {
                unset($data['connected'][$idx]);
            } elseif ($idx === false){
                $unreachable[] = $ip;
            } elseif (array_search($ip, $data['unreachable']) !== false) {
                $unreachable[] = $ip;
            }
        }

        return array('unreachable' => $unreachable, 'connected' => $data['connected']);
    }
}
