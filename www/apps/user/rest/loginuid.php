<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation syetem, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: jichen zhou.
 */

namespace apps\user;

class MRest_loginuid
{
    public static $s_aConf = array(
        'stylelist' => array(
            'default' => 'int',
        ),
        'putstylelist' => array(
            'default' => 'int',
        ),
    );

    public function get($id, $style = null)
    {
        $api = new MloginApi();

        return $api->iGetLoginUid();
    }

    public function put($id, $update, $before = null, $after = null, $put_style)
    {
        $api = new MloginApi();
        $api->vSetLoginUid($update);
    }
}
