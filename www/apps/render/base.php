<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation syetem, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: jichen zhou.
 */

namespace apps\render;

class Mbase extends \Ko_View_Render_Smarty
{
    public function oSend()
    {
        \Ko_Web_Response::VSend($this);

        return $this;
    }
}
