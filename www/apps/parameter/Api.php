<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation system, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\parameter;

class MApi
{
    private $dynamic = array();

    private $config = array(
      /*
      //sample
      'playbook_name' => array(
          'tag_or_service_name' => array(
            'parameter1' => 'auto fill content1',
            'parameter2' => 'auto fill content2',
            'parameter3forselect' => 'option1|option2|optionN',
            '...',
            '#c' => 'concurrence number'
          ),
      ),
      */
    );

    public function getParamByRole($id, $role)
    {
        $config = $this->config[$id][$role];
        empty($config) && $config = $this->config[$id]['*'];//没有适合的角色则匹配*
        if ($config) {
            foreach ($config as $k => &$v) {
                isset($this->dynamic[$k]) && $v = $this->dynamic[$k];
                if ($v == '') {
                    return false;
                }
            }
            $this->dynamic = array();

            return $config;
        }

        return false;
    }

    public function setParam($key, $value)
    {
        assert($key != '' && $value != '');
        $this->dynamic[$key] = $value;
    }
}
