<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation syetem, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\user;

class MRest_setup
{
    public static $s_aConf = array(
        'unique' => 'string',
        'stylelist' => array(
            'default' => array(
                'msg' => 'string'
            ),
        ),

        'poststylelist' => array(
            'step1' => array('hash', array(
                'domain' => 'string',
                'mysql_host' => 'string',
                'mysql_port' => 'int',
                'mysql_user' => 'string',
                'mysql_pass' => 'string',
                'mysql_dbname' => 'string',
                'mc_host' => 'string',
            )),
            'step2' => array('hash', array(
                'admin_user' => 'string',
                'admin_pass' => 'string',
            )),
        ),
        'putstylelist' => array(
            'passwd' => array('hash', array(
                'oldpasswd' => 'string',
                'newpasswd' => 'string',
            )),
            'profile' => array('hash', array(
                'nickname' => 'string',
            )),
            'audit' => array(),
        ),
    );

    public function post($update, $after = null, $post_style = 'default')
    {
        $msg = '';//返回消息
        switch ($post_style) {
            case 'step1'://初始化数据库 写入配置文件
                //尝试连接mysql
                $mysql_host = $update['mysql_port'] > 0 ? $update['mysql_host'].':'.$update['mysql_port'] : $update['mysql_host'];
                if(!empty($update['mc_host'])){
                    $tmp = explode(':',$update['mc_host'],2);
                    $tmp[1] = intval($tmp[1]);
                    $tmp[1] == 0 && $tmp[1] = 11211;
                    $update['mc_host'] = implode(':',$tmp);
                }
                try {
                    $dsn = 'mysql:dbname='.$update['mysql_dbname'].';host='.$mysql_host;
                    $pdo = new \PDO($dsn, $update['mysql_user'], $update['mysql_pass'],
                        array(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION));
                } catch (\Exception $e) {
                    $err_code = $e->getCode();
                    if($err_code == 1049){ //需要建立数据库
                        if(class_exists('mysqli')){
                            $mysqli = new \mysqli($mysql_host, $update['mysql_user'], $update['mysql_pass']);
                            if(!$mysqli->connect_error){
                                if(!$mysqli->query('CREATE DATABASE `'.$update['mysql_dbname'].'` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;')){
                                    throw new \Exception('无法自动创建数据库 '.$update['mysql_dbname'].' (可能是该账号没有权限)，请手工创建!');
                                } else {
                                    $pdo = new \PDO($dsn, $update['mysql_user'], $update['mysql_pass'],
                                        array(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION));
                                }
                            } else {
                                throw new \Exception('无法自动创建数据库 '.$update['mysql_dbname'].' (mysqli连接错误)，请手工创建!');
                            }
                        } else {
                            throw new \Exception('无法自动创建数据库 '.$update['mysql_dbname'].' (未安装mysqli)，请手工创建!');
                        }
                    } else {
                        throw new \Exception("Mysql连接失败: \n".$e->getMessage(), $err_code);
                    }
                }
                //写入sql数据
                $db_file = CODE_WWW.'operation_export.sql';
                if(file_exists($db_file)){
                    $import_cmd = 'mysql -h'.$update['mysql_host'];
                    $update['mysql_port'] > 0 && $import_cmd .= ' -P'.$update['mysql_port'];
                    $import_cmd .= ' -u'.$update['mysql_user'].' -p'.$update['mysql_pass'].' '.$update['mysql_dbname'];
                    $import_cmd .= ' < '.$db_file;
                    system($import_cmd,$rst);
                    if($rst != 0){
                        $msg = "初始化数据库文件导入失败，请稍后手工执行如下命令导入:\n".$import_cmd;
                    }
                } else {
                    throw new \Exception('初始化数据库文件('.$db_file.')不存在，请重新下载安装文件', 1001);
                }
                //写def.php配置
                $def_file = CODE_WWW.'def.php';
                $cmd[] = 'sed -i "s/^.*\'MAIN_DOMAIN\'.*$/define(\'MAIN_DOMAIN\', \''.$update['domain'].'\');/" '.$def_file;
                $cmd[] = 'sed -i "s/^.*\'KO_DB_HOST\'.*$/define(\'KO_DB_HOST\', \''.$mysql_host.'\');/" '.$def_file;
                $cmd[] = 'sed -i "s/^.*\'KO_DB_USER\'.*$/define(\'KO_DB_USER\', \''.$update['mysql_user'].'\');/" '.$def_file;
                $cmd[] = 'sed -i "s/^.*\'KO_DB_PASS\'.*$/define(\'KO_DB_PASS\', \''.$update['mysql_pass'].'\');/" '.$def_file;
                $cmd[] = 'sed -i "s/^.*\'KO_DB_NAME\'.*$/define(\'KO_DB_NAME\', \''.$update['mysql_dbname'].'\');/" '.$def_file;
                $update['mc_host'] && $cmd[] = 'sed -i "s/^.*\'KO_MC_HOST\'.*$/define(\'KO_MC_HOST\', \''.$update['mc_host'].'\');/" '.$def_file;
                exec(implode(';',$cmd),$_,$rst);
                if($rst != 0){
                    throw new \Exception('无法写入配置文件，请检查文件权限('.$def_file.')', 1002);
                }
                //写all.ini
                $ini_file = CODE_WWW.'conf/all.ini';
                $ini_cfg = "[global]\n";
                $ini_cfg .= $update['domain']." = operation\n\n";
                $ini_cfg .= "[app_operation]\n";
                $ini_cfg .= "documentroot = ".CODE_WWW."apps/operation/htdocs\n";
                $ini_cfg .= "templateroot = ".CODE_WWW."apps/operation/templates\n";
                $ini_cfg .= "rewriteconf = ".CODE_WWW."apps/operation/rewrite.txt\n";
                $ini_cfg .= "rewritecache = ".CODE_WWW."rundata/rewrite/operation.php\n";
                if(is_writable($ini_file)){
                    file_put_contents($ini_file,$ini_cfg);
                } else {
                    throw new \Exception('无法写入配置文件，请检查文件权限('.$ini_file.')', 1003);
                }
                break;

            case 'step2': //设置超管
                $userApi = new \apps\user\MuserApi();
                if (empty($update['admin_user']) || empty($update['admin_pass'])) {
                    throw new \Exception('请将注册信息填写完全', 1011);
                }
                $insert = array(
                    'username' => $update['admin_user'],
                    'passwd' => $update['admin_pass'],
                );
                $uid = $userApi->addUser($insert);
                $api = new \apps\user\MloginApi();
                $ret = $api->bRegisterUid($uid, $insert['username'], $insert['passwd'], $err_no);
                if (!$uid || !$ret) {
                    throw new \Exception('数据库状态异常，请检查后再试!', $err_no);
                }
                $conf_file = CODE_WWW.'apps/operation/Conf.php';
                $conf_cnt = "<?php\n";
                $conf_cnt .= "namespace apps\operation;\n";
                $conf_cnt .= "class MConf{\n";
                $conf_cnt .= '    public static $super_users = array('.$uid.");\n";
                $conf_cnt .= "}\n";
                if(!file_put_contents($conf_file,$conf_cnt)){
                    throw new \Exception('无法写入配置文件，请检查文件权限('.$conf_file.')', 1013);
                }
                break;

            default:
                throw new \Exception('Where am I, who can tell me.', 9999);
                break;
        }

        return array('key' => '', 'after' => array('msg' => $msg));
    }

    public function put($id, $update, $before = null, $after = null, $put_style = 'default')
    {
        $loginApi = new MloginApi();
        $uid = $loginApi->iGetLoginUid();

        if ('audit' == $put_style) {
            if (!in_array($uid, \apps\operation\MConf::$super_users)) {
                throw new \Exception('没有审核权限', 1);
            }
            $api = new MuserApi();
            $ret = $api->editUser($id, array('flag' => 1));

            return array('key' => $ret);
        }

        if (!$uid || $uid != $id) {
            throw new \Exception('修改密码失败', 1);
        }

        switch ($put_style) {
            case 'passwd':
                if (!preg_match('/^[_0-9a-z]{4,16}$/i', $update['newpasswd'])) {
                    throw new \Exception('登录密码只能使用字母，数字和下划线，4-16个字符', 2);
                }
                if (false === $loginApi->bChangePassword($uid, $update['oldpasswd'], $update['newpasswd'], $errno)) {
                    throw new \Exception('旧密码输入错误', 3);
                }
                break;
            case 'profile':
                if ('' == $update['nickname']) {
                    throw new \Exception('请输入昵称', 4);
                }
                //todo edit profile
                break;
        }

        return array('key' => $id);
    }
}
