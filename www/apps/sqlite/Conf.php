<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation system, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: lfbear <https://github.com/lfbear>.
 */

namespace apps\sqlite;

class MConf
{
    /**
     * Distribution Map:
     * 1. db_file: this script creates from mysql db
     * 2. distribute host & file: db_file will be rsynced here, then it will copy to all servers
     * 3. target_file: the file path in all of the servers, final destination.
     **/
    const DB_FILE = CODE_WWW.'sqlite.db';//db file name created
    const DISTRIBUTE_HOST = '127.0.0.1';//distribution server host
    const DISTRIBUTE_FILE = 'data/ansible/sqlite.db';//distribution server path, DO NOT start with '/'
    const TARGET_FILE = '/data/ansible/sqlite.db';//file path in all servers
}
