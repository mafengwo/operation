<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation system, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\server;

class MDao extends \Ko_Dao_Factory
{
    protected $_aDaoConf = array(
        'modulemc' => array(
            'type' => 'mcache',
        ),
        'nodes' => array(
            'type' => 'db_single',
            'kind' => 'cluster_nodes',
            'key' => 'id',
        ),
        'tags' => array(
            'type' => 'db_single',
            'kind' => 'cluster_tags',
            'key' => 'id',
        ),
        'tag_map' => array(
            'type' => 'db_single',
            'kind' => 'cluster_tag_map',
            'key' => 'id',
        ),
        'remote_task' => array(
            'type' => 'db_single',
            'kind' => 'cluster_remote_task',
            'key' => 'id',
        ),
        'task_privacy' => array(
            'type' => 'db_single',
            'kind' => 'cluster_task_privacy',
            'key' => 'id',
        ),
        'identification' => array(
            'type' => 'db_single',
            'kind' => 'cluster_identification',
            'key' => 'id',
        ),
        'assets' => array(
            'type' => 'db_single',
            'kind' => 'cluster_assets',
            'key' => 'id',
        ),
        'idc_ip' => array(
            'type' => 'db_single',
            'kind' => 'cluster_idc_ip',
            'key' => 'id',
        ),
        'idc_cabinet' => array(
            'type' => 'db_single',
            'kind' => 'cluster_idc_cabinet',
            'key' => 'id',
        ),
        'oplog' => array(
            'type' => 'db_single',
            'kind' => 'cluster_oplog',
            'key' => 'id',
        ),
    );
}
