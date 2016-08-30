<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation system, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: XieZe <xieze@mafengwo.com>.
 */

namespace apps\server;

class MRest_assets
{
    public static $s_aConf = array(
        'unique' => 'int',
        'poststylelist' => array(
            'default' => array(
                'hash', array(
                    'id' => 'int',
                    'ip' => 'string',
                    'type' => 'string',
                    'cpu' => 'string',
                    'mem' => 'string',
                    'disk' => 'string',
                    'raid' => 'string',
                    'date' => 'string',
                ),
            ),
        ),

        'stylelist' => array(
            'default' => array(
                'hash',array(
                    'id' => 'int',
                    'ip' => 'string',
                    'type' => 'string',
                    'cpu' => 'string',
                    'mem' => 'string',
                    'disk' => 'string',
                    'raid' => 'string',
                    'date' => 'string'
                ),
            ),
        ),
    );

    public function post($update, $after = null, $post_style = 'default')
    {
        $api = new \apps\server\MassetsApi();
        $app = new \apps\server\McabinetApi();
        if($post_style == 'default'){
            if (empty($update['id'])) { //新增
                $getIdByIp = $app->getId($update['ip']);
                $getPidById = $api->getPid($getIdByIp[0]['id']);
                if($getPidById[0]['pid']){
                    throw new \Exception('此配置已存在，请在修改选项编辑', 1);
                }else{
                    unset($update['ip']);
                    $update['pid'] = $getIdByIp[0]['id'];
                    $api->vAdd($update);
                }
            }else { //修改
                $info = $api->aGet($update['id']);
                if($info){
                    unset($update['id'],$update['ip']);
                    $rst = $api->vUpdate($info['id'],$update);
                }else{
                    throw new \Exception('此配置信息不存在，请先新增', 2);
                }
            }
            return array('ret'=>$rst);
        }
    }

    public function get($id, $style = 'default')
    {
        $api = new \apps\server\MassetsApi();
        $idc_api = new \apps\server\McabinetApi();
        $info =  $api->aGet($id);
        if(empty($info)){
            throw new \Exception('没有数据可能修改，请先新增', 1);
        }
        $cabinet = $idc_api->getByPid($info['pid']);
        $info['ip'] = $cabinet['ip'];
        return $info;
    }

    public function delete($id, $before = null)
    {
        if (empty($id)) {
            throw new \Exception('必要信息缺失，无法操作', 1);
        }
        $api = new \apps\server\MassetsApi();
        $ret = $api->vDelete($id);

        return array('ret' => $ret);
    }
}
