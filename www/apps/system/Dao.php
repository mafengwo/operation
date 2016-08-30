<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation syetem, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: jichen zhou.
 */

namespace apps\system;

class MDao extends \Ko_Dao_Factory
{
    protected $_aDaoConf = array(
      'redis' => array(
          'type' => 'redis',
      ),
      'mcache' => array(
          'type' => 'mcache',
      ),
      'dbMenu' => array(
          'type' => 'db_single',
          'kind' => 'operation_menu',
          'key' => 'id',
      ),
      'dbMenuTree' => array(
          'type' => 'db_single',
          'kind' => 'operation_menu_tree',
          'key' => 'id',
      ),
      'dbMenuPrivacy' => array(
          'type' => 'db_single',
          'kind' => 'operation_menu_privacy',
          'key' => 'id',
      ),
      'dbRole' => array(
          'type' => 'db_single',
          'kind' => 'operation_role',
          'key' => 'rid',
      ),
      'dbRolePrivacy' => array(
          'type' => 'db_single',
          'kind' => 'operation_role_menu_privacy',
          'key' => 'id',
      ),
    );
}
