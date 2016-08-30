<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation system, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: XieZe <xieze@mafengwo.com>.
 */

namespace apps\server;

class McabinetApi extends \Ko_Mode_Item
{
    public function __construct()
    {
        $loginApi = new \apps\user\MloginApi();
        $this->admin_uid = $loginApi->iGetLoginUid($uinfo);
    }

    public function idc_ipAdd($update)
    {
        return $this->idc_ipDao->iInsert(MApi::_aArrTrim($update), array(), array(), null,
            $this->admin_uid);
    }

    public function idc_cabinetAdd($update)
    {
        return $this->idc_cabinetDao->iInsert(MApi::_aArrTrim($update), array(), array(), null,
            $this->admin_uid);
    }

    public function getIdByCabinet($cabinet)
    {
        $option = new \Ko_Tool_SQL();

        return $this->idc_cabinetDao->aGetList($option->oWhere('cabinet=?', $cabinet));
    }

    public function getIdByCabinetAndIDC($cabinet, $idc)
    {
        $option = new \Ko_Tool_SQL();

        $result = $this->idc_cabinetDao->aGetList($option->oWhere('cabinet=? and idc=?', $cabinet, $idc));

        return array_shift($result);
    }

    public function isExistCabinet($cabinet)
    {
        $option = new \Ko_Tool_SQL();
        $res = $this->idc_cabinetDao->aGetList($option->oWhere('cabinet=?', $cabinet));

        return $rows = count($res);
    }

    public function getIpByCid($cid)
    {
        $option = new \Ko_Tool_SQL();

        return $this->idc_ipDao->aGetList($option->oWhere('cid=?', $cid));
    }

    public function getIdByIdc($idc)
    {
        $option = new \Ko_Tool_SQL();

        return $this->idc_cabinetDao->aGetList($option->oWhere('idc=?', $idc));
    }

    public function getIpByIdc($idc)
    {
        $getId = $this->getIdByIdc($idc);
        foreach ($getId as $k => $v) {
            $res[] = $this->getIpByCid($v['id']);
            foreach ($res as $key => $val) {
                foreach ($val as $kk => $vv) {
                    $getIp[] = $vv;
                }
            }
        }

        return $getIp;
    }

    public function getAllIp()
    {
        $option = new \Ko_Tool_SQL();

        return $this->idc_ipDao->aGetList($option);
    }

    public function getOneIp($ip)
    {
        $option = new \Ko_Tool_SQL();

        return $this->idc_ipDao->aGetList($option->oWhere('ip like ?', '%'.$ip));
    }

    public function getId($ip)
    {
        $option = new \Ko_Tool_SQL();

        return $this->idc_ipDao->aGetList($option->oWhere('ip=?', $ip));
    }

    public function getByPid($pid)
    {
        $option = new \Ko_Tool_SQL();
        $list = $this->idc_ipDao->aGetList($option->oWhere('id=?', $pid));

        return array_shift($list);
    }

    public function getCount()
    {
        $option = new \Ko_Tool_SQL();
        $ret = $this->idc_ipDao->aGetList($option);

        return $rows = count($ret);
    }

    public function getAllCabinet()
    {
        $option = new \Ko_Tool_SQL();

        return $this->idc_cabinetDao->aGetList($option->oOrderBy('cabinet'));
    }

    public function getAllCabinetByIdc($idc)
    {
        $option = new \Ko_Tool_SQL();

        return $this->idc_cabinetDao->aGetList($option->oWhere('idc=?', $idc)->oOrderBy('cabinet'));
    }

    public function getCabinetById($id)
    {
        $option = new \Ko_Tool_SQL();

        return $this->idc_cabinetDao->aGet($id);
    }

    public function iUpdateByCidAndPosition($cid, $position, $update)
    {
        $option = new \Ko_Tool_SQL();

        return $this->idc_ipDao->iUpdateByCond($option->oWhere('cid = ? and position = ?', $cid, $position), MApi::_aArrTrim($update), array(), null,
            $this->admin_uid);
    }

    public function vUpdate($id, array $update)
    {
        return $this->idc_cabinetDao->iUpdate($id, MApi::_aArrTrim($update), array(), null,
            $this->admin_uid);
    }

    public function getPosition($cid, $position)
    {
        $option = new \Ko_Tool_SQL();
        $res = $this->idc_ipDao->aGetList($option->oWhere('cid=? and position=?', $cid, $position));

        return $rows = count($res);
    }

    public function deleteIpByCid($cid)
    {
        $option = new \Ko_Tool_SQL();

        return $this->idc_ipDao->iDeleteByCond($option->oWhere('cid=?', $cid));
    }
    public function deleteIpByCidAndPosition($cid, $position)
    {
        $option = new \Ko_Tool_SQL();

        return $this->idc_ipDao->iDeleteByCond($option->oWhere('cid=? and position=?', $cid, $position));
    }

    public function deleteCabinetById($id)
    {
        return $this->idc_cabinetDao->iDelete($id, null, $this->admin_uid);
    }
}
