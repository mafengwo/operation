<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation system, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\ansible;

class MDao extends \Ko_Dao_Factory
{
    protected $_aDaoConf = array(
        'mc' => array(
            'type' => 'mcache',
        ),
        'remotelog' => array(
            'type' => 'db_single',
            'kind' => 'operation_remote_log',
            'key' => 'id',
        ),
    );
}
