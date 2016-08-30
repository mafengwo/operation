<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation system, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\ansible;

class MRest_parameter
{
    public static $s_aConf = array(
        'unique' => 'string',
        'stylelist' => array(
            'basevars' => 'list',
            'parsevars' => 'list',
            'autofill' => 'list',
        ),
    );

    public function get($id, $style)
    {
        if ($id) {
            if ($style['style'] == 'basevars') {
                $id = intval($id);
                $task_privacy = new \apps\server\MtaskprivacyApi();
                if ($task_privacy->bHasTaskPriByUid($id)) {
                    $task_api = new \apps\server\MremotetaskApi();
                    $task_info = $task_api->aGet($id);
                    $vars = json_decode($task_info['vars'],true);
                    $vars && $vars = array_filter($vars);
                    if($task_info['type']==2){
                        $del_keys = array('hosts', 'container_name');
                        $vars && $del_keys = array_merge(array_keys($vars),$del_keys);
                        $pb_vars = self::get_vars_by_playbook($task_info['command'],$del_keys);
                        if (false === $data) {
                            throw new \Exception('数据不存在', 2);
                        }
                        return $pb_vars;
                    } else if($task_info['type']==3){
                        return array('__parameter__');
                    }
                } else {
                    throw new \Exception('对不起，您没有操作这个任务的权限', 3);
                }
            } elseif ($style['style'] == 'parsevars') {
                $data = self::get_vars_by_playbook($id);
                if (false === $data) {
                    throw new \Exception('数据不存在', 2);
                }
                return $data;
            } elseif ($style['style'] == 'autofill') {
                $info = explode('|', $id, 2);
                $target = explode('-', $info[1], 2);
                $tag = '';
                if ('tag' == $target[0]) {
                    $tag_api = new \apps\server\MtagsApi();
                    $tag_info = $tag_api->aGet($target[1]);
                    $tag = $tag_info['name'];
                }

                $api = new \apps\parameter\MApi();
                $rs = $api->getParamByRole($info[0], $tag);
                if ($rs) {
                    return $rs;
                } else {
                    throw new \Exception('数据不存在', 2);
                }
            }
        } else {
            throw new \Exception('缺少重要参数', 1);
        }
    }

    private static function get_vars_by_playbook($pb_name,array $del_keys = array('hosts', 'container_name'))
    {
        $api = new \apps\ansible\MApi();
        $tmp = $api->parse_vars($pb_name.'.yml');
        if (is_array($tmp['vars'])) {
            foreach ($tmp['vars'] as $k => $v) {
                if (in_array($v, $del_keys)) {
                    unset($tmp['vars'][$k]);
                }
            }

            return array_values($tmp['vars']);
        }

        return false;
    }
}
