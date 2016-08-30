<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation syetem, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: jichen zhou.
 */

namespace apps\user;

class MuuidApi extends \Ko_Mode_Uuid
{
    protected $_aConf = array(
        'cookiename' => 'uuid',
        'uuid' => 'uuid',
    );

    public function __construct()
    {
        $this->_aConf['domain'] = '.'.MAIN_DOMAIN;
        parent::__construct();
    }
}
