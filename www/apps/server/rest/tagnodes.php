<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation system, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */
namespace apps\server;

class MRest_tagnodes
{
    public static $s_aConf = array(
        'unique' => 'int',
        'stylelist' => array(
            'default' => 'string',
        ),
        'poststylelist'   => array(
    			'default' => array(
    				'hash', array(
    					'id'   => 'int',
    					'list' => 'string'
    				),
    			),
    		),
    );

    public function get($id, $style = 'default')
    {
        $api = new \apps\server\MApi();
        $list = $api->getIpsByTagId($id);
        return $list ? implode("\n",$list) : '';
    }

    public function post($update, $after = null,$post_style='default')
    {
        $api = new \apps\server\MnodesApi();
        $tagmap_api = new \apps\server\MtagmapApi();
        if ($post_style == 'default') {
            if (empty($update['list']) || empty($update['id'])) {
                return false;
            }
            $list = explode("\n",$update['list']);
            $list = array_map(function($v){ return ip2long($v)>0 ? $v : ''; }, $list);
            $list = array_filter($list);
            $list = array_unique($list);
            if(count($list)){
              //del all map tag_id=$update['id']
              $tagmap_api->vDelByTagId($update['id']);
              $ip_list = $api->getNodeIdsByIps($list);
              //add new map (only add tag for first elem pre ip)
              foreach($ip_list as $v){
                $v[0]>0 && $tagmap_api->vAdd(array('tag_id'=>$update['id'],'node_id'=>$v[0]));
              }
            }
        }
        return array('key'=>true);
    }
}
