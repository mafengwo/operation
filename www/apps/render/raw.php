<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation syetem, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\render;

class Mraw extends \Ko_View_Render_Base
{
    public function sRender()
    {
        return $this->_aData;
    }

    public function vSetRaw($value = '')
    {
        $this->_aData = $value;
    }
}
