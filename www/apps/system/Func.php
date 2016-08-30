<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation syetem, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: Jichen Zhou.
 */

namespace apps\system;

class MFunc
{
    public static function sGetCurUri()
    {
        return self::getUri($_SERVER['REQUEST_URI']);
    }

    public static function sGetDirUriName()
    {
        return $_SERVER['SCRIPT_NAME'];
    }

    public static function getUri($sUri)
    {
        $iPos = strpos($sUri, '?');
        if (false === $iPos) {
            return $sUri;
        }

        return substr($sUri, 0, $iPos);
    }

    public static function aGetCurUriParams()
    {
        return self::aGetUriParams($_SERVER['REQUEST_URI']);
    }

    public static function aGetUriParams($sUri)
    {
        $aParams = array();
        $iPos = strpos($sUri, '?');
        if (false === $iPos) {
            return $aParams;
        }
        $sParams = substr($sUri, $iPos + 1);
        if ($sParams) {
            $tmp = explode('&', $sParams);
            foreach ($tmp as $v) {
                list($key, $value) = explode('=', $v);
                $aParams[$key] = $value;
            }
        }

        return $aParams;
    }

    /*
     * @param $link 主链接
     * @param $params 查询条件
     * @param $cur_page 当前页
     * @param $page_size 每页数量
     * @param $total  结果总量
     * */
    public static function getPagerHtml($link, $params = array(), $cur_page = 1, $page_size = 15, $total = 0)
    {
        $paginator = self::calcPaginators($total, $cur_page, $page_size);
        unset($params['page']);
        $condition = array();
        $page_str = $link.'?';
        if (is_array($params) && count($params)) {
            foreach ($params as $key => $param) {
                $condition[] = $key.'='.$param;
            }
            $page_str .= implode('&', $condition);
        }

        $smarty = new \Ko_View_Smarty();
        $smarty->vAssignHtml('page_str', $page_str);
        $smarty->vAssignHtml('paginator', $paginator);

        return $smarty->sFetch('common/paginator.tpl');
    }

    public static function setPagerHtml(&$smarty, $iTotal, $iPage, $iLength, $aParams = array())
    {
        list($url) = explode('?', $_SERVER['REQUEST_URI']);
        $pager = KShequ_Func::getPagerHtml($url, $aParams, $iPage, $iLength, $iTotal);
        $smarty->vAssignRaw('page_html', $pager);
    }

    public static function calcPaginators($totalRecs, $curPage, $recsPerPage = 9)
    {
        $ret = array();
        if (empty($totalRecs) || $totalRecs <= 0) {
            return $ret;
        }
        $curPage = !empty($curPage) ? $curPage : 1;
        $totalPages = ceil($totalRecs / $recsPerPage);
        $ret['cur_page'] = $curPage;
        $ret['total_pages'] = $totalPages;
        $minPage = $curPage - 3;
        $maxPage = $curPage + 3;
        $minPage = max($minPage, 1);
        $maxPage = min($maxPage, $totalPages);
        $ret['pages'] = range($minPage, $maxPage);
        $ret['minpage'] = $minPage;
        $ret['maxpage'] = $maxPage;
        $ret['totalRecs'] = $totalRecs;

        return $ret;
    }

    public static function vOutput($vData, $sType = 'text')
    {
        $_SESSION['back_url'] = '';
        if (strtoupper($sType) == 'JSON') {
            // 返回JSON数据
            header('Content-Type:text/html; charset=utf-8');
            echo json_encode($vData);
        } else {
            echo $vData;
        }
        exit();
    }
}
