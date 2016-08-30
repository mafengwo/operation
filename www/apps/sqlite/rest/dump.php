<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation syetem, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\sqlite;

class MRest_dump {
    public static $s_aConf = array(
      'unique' => 'int',
      'stylelist' => array(
           'default' => array(
                'ret' => 'bool',
            ),
      ),
    );

    public function get() {
        $app = new \apps\sqlite\Mdump();
        return array('ret' => $app->run());
    }
}
