<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation syetem, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: jichen zhou.
 */

namespace apps\user;

class MDao extends \Ko_Dao_Factory
{
    protected $_aDaoConf = array(
        'username' => array(
            'type' => 'db_single',
            'kind' => 'operation_user_username',
            'key' => array('username', 'src'),
        ),
        'hashpass' => array(
            'type' => 'db_single',
            'kind' => 'operation_user_hashpass',
            'key' => 'uid',
        ),
        'varsalt' => array(
            'type' => 'db_single',
            'kind' => 'operation_user_varsalt',
            'key' => 'uid',
        ),
    'user' => array(
        'type' => 'db_single',
        'kind' => 'operation_user',
        'key' => 'id',
    ),
    );
}
