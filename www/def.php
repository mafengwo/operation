<?php

error_reporting(E_ALL^E_NOTICE);

/* server config begin */
define('MAIN_DOMAIN', $_SERVER['HTTP_HOST']);
define('KO_DB_HOST', '');
define('KO_DB_USER', '');
define('KO_DB_PASS', '');
define('KO_DB_NAME', '');
define('KO_MC_HOST', '');
define('DISTRIBUTION_MASTER', true);//distribution server config file
/* server config end  */

/* basically no need to edit */
define('CODE_WWW', $_SERVER['DOCUMENT_ROOT'].'/');
define('WWW_DOMAIN',  MAIN_DOMAIN);
define('PASSPORT_DOMAIN',  MAIN_DOMAIN);
define('XHPROF_DOMAIN', 'xhprof.' . MAIN_DOMAIN);
define('IMG_DOMAIN', MAIN_DOMAIN.'/static');
define('COMMON_CLASS_PATH', CODE_WWW . 'include/');
define('COMMON_CONF_PATH', CODE_WWW . 'conf/');
define('COMMON_RUNDATA_PATH', CODE_WWW . 'rundata/');
define('KO_DEBUG', 1);
define('KO_APPS_NS','apps');
define('KO_APPS_DIR',CODE_WWW.KO_APPS_NS.'/');
define('KO_TEMPDIR', COMMON_RUNDATA_PATH . 'kotmp/');
define('KO_INCLUDE_DIR', COMMON_CLASS_PATH);
define('KO_SMARTY_INC', CODE_WWW . 'libs/smarty/libs/Smarty.class.php');
define('KO_TEMPLATE_C_DIR', COMMON_RUNDATA_PATH . 'templates_c/');
define('KO_CONFIG_SITE_INI', COMMON_CONF_PATH . 'all.ini');
define('KO_CONFIG_SITE_CACHE', COMMON_RUNDATA_PATH . 'all.php');
