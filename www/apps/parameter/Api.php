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
      //nginx
      'publish_nginx' => array(
          'admin' => array(
              'src_path' => 'svntrunkdir/server_conf/product/admin/nginx',
              'dest_path' => '/usr/local/nginx',
              'param' => 'sync_conf|test|reload',
          ),
          'web' => array(
              'src_path' => 'svntrunkdir/server_conf/web/nginx/conf',
              'dest_path' => '/usr/local/ngx_fvhost',
              'param' => 'sync_conf|test|reload',
          ),
          'web_proxy' => array(
              'src_path' => 'svntrunkdir/server_conf/web_proxy/nginx/conf',
              'dest_path' => '/usr/local/nginx',
              'param' => 'sync_conf|test|reload',
          ),
          'fdfs_storaged' => array(
              'src_path' => 'svntrunkdir/server_conf/product/file_storage/nginx',
              'dest_path' => '/usr/local/nginx',
              'param' => 'sync_conf|test|reload',
          ),
      ),

      //fastcgi
      'publish_fastcgi' => array(
          'web' => array(
              'src_path' => 'svntrunkdir/server_conf/product/web/fastcgi',
              'dest_path' => '/usr/local/phpfastcgi_5_3_28',
              'param' => 'sync_conf|test|reload',
              '#c' => 1,
          ),
          'web_debug' => array(
              'src_path' => 'svntrunkdir/server_conf/debug/web/fastcgi',
              'dest_path' => '/usr/local/phpfastcgi_5_3_28',
              'param' => 'sync_conf|test|reload',
              '#c' => 1,
          ),
          'admin' => array(
              'src_path' => 'svntrunkdir/server_conf/product/admin/fastcgi',
              'dest_path' => '/usr/local/phpfastcgi',
              'param' => 'sync_conf|test|reload',
              '#c' => 1,
          ),
          'fdfs_storaged' => array(
              'src_path' => 'svntrunkdir/server_conf/product/file_storage/fastcgi',
              'dest_path' => '/usr/local/phpfastcgi_5_3_28_9002',
              'param' => 'sync_conf|test|reload',
              '#c' => 1,
          ),
      ),

      //zabbix
      'publish_zabbix' => array(
          'all' => array(
              'src_path' => 'svntrunkdir/server_conf/zabbix',
              'dest_path' => '/etc/zabbix',
              'param' => 'sync_conf|restart',
          ),
          '*' => array(
              'src_path' => 'svntrunkdir/server_conf/zabbix',
              'dest_path' => '/etc/zabbix',
              'param' => 'sync_conf|restart',
          ),
      ),

      //haproxy
      'publish_haproxy' => array(
          'all' => array(
              'src_path' => 'svntrunkdir/server_conf/product/haproxy/conf',
              'dest_path' => '/usr/local/haproxy-1.5.12',
              'param' => 'sync_conf|start|stop|restart',
          ),
          '*' => array(
              'src_path' => 'svntrunkdir/server_conf/product/haproxy/conf',
              'dest_path' => '/usr/local/haproxy-1.5.12',
              'param' => 'sync_conf|start|stop|restart',
          ),
      ),

      //kxi-*mand
      'publish_kxi' => array(
          '*' => array(
            'src_path' => 'svntrunkdir/server_conf/product/kxi',
            'dest_path' => '/usr/local/kxi',
            'param' => 'sync_conf',
          ),
      ),

      //searchd(coreseek)
      'publish_coreseek' => array(
          'searchd' => array(
              'src_path' => 'svntrunkdir/server_conf/coreseek',
              'dest_path' => '/usr/local/coreseek',
              'param' => 'sync_conf|restart',
              '#c' => 1,
          ),
      ),

      //scribed
      'publish_scribe' => array(
          'scribed' => array(
              'src_path' => 'svntrunkdir/server_conf/scribe',
              'dest_path' => '/usr/local/scribe',
              'param' => 'sync_conf|reload',
          ),
      ),

      //for debug
      'test' => array('web' => array('uname' => 'a|b|c')),
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
