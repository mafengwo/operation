<?php
/**
 * DBPDO.
 *
 * @author zhangchu
 */

/**
 * 使用PDO的方式来连接Mysql.
 */
class Ko_Data_DBPDO
{
    const SLEEP_TIME = 25;//连接闲置时间
    private $last_time;//最后一次链接时间
    private static $s_AInstance = array();

    private $_oPDO;

    protected function __construct($sTag)
    {
    }

    public static function OInstance($sName = '')
    {
        if (empty(self::$s_AInstance[$sName])) {
            self::$s_AInstance[$sName] = new self($sName);
        }

        return self::$s_AInstance[$sName];
    }

    /**
     * 一条sql查询.
     */
    public function aSingleQuery($sKind, $iHintId, $sSql, $iCacheTime, $bMaster)
    {
        $pdo = $this->_oGetPDO();
        //解决MySQL server has gone away的问题 方案 连接超过特定时间 重新建立链接
        if (time() - $this->last_time > self::SLEEP_TIME) {
            unset($this->_oPDO);
            $pdo = $this->_oGetPDO(true);
        }
        $pdos = $pdo->prepare($sSql);
        $pdos->execute();
        if (false == $pdos) {
            $einfo = $pdo->errorInfo();
            if ($einfo[0] == 'HY000' && $einfo[1] == '2006') {
                $pdo = $this->_oGetPDO(true);
                $pdos = $pdo->prepare($sSql);
                $pdos->execute();
            }
        }
        $this->last_time = time();
        $data = $pdos->fetchAll(PDO::FETCH_ASSOC);

        return array(
            'data' => $data,
            'rownum' => count($data),
            'insertid' => intval($pdo->lastInsertId()),
            'affectedrows' => $pdos->rowCount(),
        );
    }

    /**
     * 多条sql查询.
     */
    public function aMultiQuery($sKind, $iHintId, $aSqls, $iCacheTime, $bMaster)
    {
        $ret = array();
        foreach ($aSqls as $k => $sSql) {
            $ret[$k] = $this->aSingleQuery($sKind, $iHintId, $sSql, $iCacheTime, $bMaster);
        }

        return $ret;
    }

    private function _oGetPDO($reconnect = false)
    {
        if (is_null($this->_oPDO) || $reconnect) {
            $dsn = 'mysql:dbname='.KO_DB_NAME.';host='.KO_DB_HOST.';charset=UTF8';
            $this->_oPDO = new PDO($dsn, KO_DB_USER, KO_DB_PASS
            // , array(
            //     PDO::ATTR_TIMEOUT => 20,
            //     PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            //   )
            );
        }

        return $this->_oPDO;
    }
}
