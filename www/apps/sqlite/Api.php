<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation syetem, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\sqlite;

class MApi {
    const DB_FILE = 'sqlite.db';
    private $handler;

    public function __construct($dbfile = '') {
        $dbfile = $dbfile == '' ? self::DB_FILE : $dbfile;
        $this->handler = new \SQLite3($dbfile);
    }

    public function __destruct(){
        $this->handler->close();
    }

    public function query($sql) {
        $result = $this->handler->query($sql);

        return $result->fetchArray(SQLITE3_ASSOC);
    }

    public function exec($sql) {
        return $this->handler->exec($sql);
    }

    public function insert($table, array $fields, array $data) {
        $format = 'INSERT INTO '.$table.' ( '.implode(',', $fields).' ) VALUES ( %s );';
        //use transaction to keep fast insert
        $this->exec("begin transaction");
        foreach ($data as $d) {
            $sql = '';
            $segment = array();
            foreach ($fields as $f) {
                $segment[] = '"'.str_replace('"', '\\"', $d[$f]).'"';
            }
            $sql = sprintf($format, implode(',', $segment));
            $this->exec($sql);
        }
        $this->exec("commit transaction");
    }

    public function create_table(array $table_struct) {
        foreach ($table_struct as $t) {
            $this->exec($t);
        }
    }
}
