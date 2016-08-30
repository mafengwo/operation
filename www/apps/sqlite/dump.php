<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation syetem, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\sqlite;

class Mdump
{
    /**
     * Distribution Map:
     * 1. db_file: this script creates from mysql db
     * 2. distribute host & file: db_file will be rsynced here, then it will copy to all servers
     * 3. target_file: the file path in all of the servers, final destination.
     **/
    private $table_struct = array(
      //tag表结构
      'cluster_tags' => 'CREATE TABLE cluster_tags ('.
              'id INT PRIMARY KEY NOT NULL, '.
              'name TEXT NOT NULL,'.
              'type INT NOT NULL,'.
              'port INT NOT NULL,'.
              'argument TEXT NOT NULL);'.
              'CREATE INDEX ct_type ON cluster_tags(type);',
      //tagmap表结构
      'cluster_tag_map' => 'CREATE TABLE cluster_tag_map ('.
              'id INT PRIMARY KEY NOT NULL, '.
              'tag_id INT NOT NULL,'.
              'node_id INT NOT NULL);'.
              'CREATE INDEX ctm_tag ON cluster_tag_map(tag_id);'.
              'CREATE INDEX ctm_node ON cluster_tag_map(node_id);',
      //node表结构
      'cluster_nodes' => 'CREATE TABLE cluster_nodes ('.
              'id INT PRIMARY KEY NOT NULL, '.
              'ip TEXT NOT NULL,'.
              'identification TEXT NOT NULL,'.
              'status INT NOT NULL);'.
              'CREATE INDEX cn_ip ON cluster_nodes (ip);',
    );

    private function _tiny_original($type)
    {
        switch ($type) {
            case 'tag':
              $api = new \apps\server\MtagsApi();

              return $api->aGetAll();
            break;

            case 'node':
              $api = new \apps\server\MnodesApi();

              return $api->getAllNodes();
            break;

            case 'tag_map':
              $api = new \apps\server\MtagmapApi();

              return $api->aGetAll();
            break;
      }
    }

    private function ms($s)
    {
        //echo $s."\t\t".microtime(true)."\n";
    }

    public function run()
    {
        $this->ms('delfile');
        file_exists(MConf::DB_FILE) && unlink(MConf::DB_FILE);
        $this->ms('db ready');
        $db = new \apps\sqlite\MApi(MConf::DB_FILE);
        $this->ms('db create');
        $db->create_table($this->table_struct);
        $this->ms('db init');
        $db->insert('cluster_tags', array('id', 'name', 'type', 'port', 'argument'), $this->_tiny_original('tag'));
        $db->insert('cluster_nodes', array('id', 'ip', 'identification', 'status'), $this->_tiny_original('node'));
        $db->insert('cluster_tag_map', array('id', 'tag_id', 'node_id'), $this->_tiny_original('tag_map'));
        $this->ms('check & sync');
        if ($this->check() && $this->sync2master() && true === DISTRIBUTION_MASTER) {
            //test passed and copy db file to master
            $api = new \apps\ansible\MApi();
            $this->ms('copy to all');
            $tmp_file = MConf::TARGET_FILE.'.hold';
            $api->runner('all', 'copy', 'src=/'.MConf::DISTRIBUTE_FILE.' dest='.$tmp_file, true);
            $this->ms('mv file');
            $api->runner('all', 'shell', '/bin/cp '.$tmp_file.' '.MConf::TARGET_FILE.
            ' && /bin/rm -f '.$tmp_file, true);
            $this->ms('done');

            return true;
        } else {
            return false;
        }
    }

    public function check()
    {
        $nodes_api = new \apps\server\MnodesApi();
        $tagmap_api = new \apps\server\MtagmapApi();
        $sum1 = count($tagmap_api->aGetAll());
        $sum2 = count($nodes_api->getAllNodes());
        $db = new \apps\sqlite\MApi(MConf::DB_FILE);
        $check1 = $db->query('select count(*) as sum from cluster_tag_map');
        $check2 = $db->query('select count(*) as sum from cluster_nodes');

        return $sum1 * $sum2 > 0 && $sum1 == $check1['sum'] && $sum2 == $check2['sum'];
    }

    public function sync2master()
    {
        $rsync_cmd = '/usr/bin/rsync';
        if (!is_file($rsync_cmd)) {
            return false;
        }
        system($rsync_cmd.' -a '.MConf::DB_FILE.' '.MConf::DISTRIBUTE_HOST.'::'.MConf::DISTRIBUTE_FILE, $rst);

        return $rst == 0;
    }
}
