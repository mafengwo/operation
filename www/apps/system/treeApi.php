<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation syetem, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: jichen zhou.
 */

namespace apps\system;

class MtreeApi extends \Ko_Mode_Tree
{
    const MAX_DEPTH = 4;

    protected $_aConf = array(
        'treeApi' => 'dbMenuTreeDao',
        'mc' => 'mcache',
        'maxdepth' => self::MAX_DEPTH,
    );
}
