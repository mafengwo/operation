<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation syetem, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\render;

class Mjson extends \Ko_View_Render_JSON
{
    public function oSend()
    {
        \Ko_Web_Response::VSend($this);

        return $this;
    }
}
