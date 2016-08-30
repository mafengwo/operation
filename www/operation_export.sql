# init db for operation
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table cluster_assets
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cluster_assets`;

CREATE TABLE `cluster_assets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(20) NOT NULL DEFAULT '',
  `type` varchar(10) NOT NULL DEFAULT '',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `cpu` varchar(30) NOT NULL DEFAULT '',
  `mem` varchar(20) NOT NULL DEFAULT '',
  `disk` varchar(40) NOT NULL DEFAULT '',
  `raid` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table cluster_idc_cabinet
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cluster_idc_cabinet`;

CREATE TABLE `cluster_idc_cabinet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cabinet` varchar(32) NOT NULL,
  `idc` int(3) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cabinet` (`cabinet`,`idc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


# Dump of table cluster_idc_ip
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cluster_idc_ip`;

CREATE TABLE `cluster_idc_ip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL,
  `ip` varchar(25) NOT NULL,
  `position` int(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table cluster_identification
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cluster_identification`;

CREATE TABLE `cluster_identification` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identification` varchar(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table cluster_nodes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cluster_nodes`;

CREATE TABLE `cluster_nodes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` int(10) unsigned NOT NULL DEFAULT '0',
  `identification` varchar(64) NOT NULL DEFAULT '',
  `service_id` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `cluster_nodes` WRITE;
/*!40000 ALTER TABLE `cluster_nodes` DISABLE KEYS */;

INSERT INTO `cluster_nodes` (`id`, `ip`, `identification`, `service_id`, `status`)
VALUES
	(51,167772260,'10.0.0.100',0,1),
	(53,167772261,'10.0.0.101',0,1),
	(55,167772261,'10.0.0.101',0,1),
	(57,167772262,'10.0.0.102',0,1);

/*!40000 ALTER TABLE `cluster_nodes` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table cluster_oplog
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cluster_oplog`;

CREATE TABLE `cluster_oplog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kind` varchar(64) NOT NULL DEFAULT '',
  `infoid` varchar(128) NOT NULL DEFAULT '',
  `action` tinyint(4) NOT NULL DEFAULT '0',
  `content` mediumblob NOT NULL,
  `admin` blob NOT NULL,
  `ip` varchar(100) NOT NULL DEFAULT '',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `kind` (`kind`,`id`),
  KEY `kind_2` (`kind`,`infoid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table cluster_remote_task
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cluster_remote_task`;

CREATE TABLE `cluster_remote_task` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '',
  `memo` varchar(255) DEFAULT '',
  `ip` varchar(255) DEFAULT '',
  `command` varchar(255) DEFAULT '',
  `type` tinyint(1) DEFAULT '0',
  `vars` text,
  `sudo` tinyint(1) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table cluster_tag_map
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cluster_tag_map`;

CREATE TABLE `cluster_tag_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag_id` int(10) unsigned NOT NULL DEFAULT '0',
  `node_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag_id` (`tag_id`,`node_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `cluster_tag_map` WRITE;
/*!40000 ALTER TABLE `cluster_tag_map` DISABLE KEYS */;

INSERT INTO `cluster_tag_map` (`id`, `tag_id`, `node_id`)
VALUES
	(227,52,53),
	(223,58,51),
	(237,64,55),
	(241,64,57),
	(221,97,51),
	(239,97,57),
	(235,109,55),
	(225,115,51);

/*!40000 ALTER TABLE `cluster_tag_map` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table cluster_tags
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cluster_tags`;

CREATE TABLE `cluster_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `port` int(5) unsigned NOT NULL DEFAULT '0',
  `argument` varchar(256) NOT NULL DEFAULT '',
  `memo` varchar(256) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`,`type`,`port`),
  KEY `port` (`port`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `cluster_tags` WRITE;
/*!40000 ALTER TABLE `cluster_tags` DISABLE KEYS */;

INSERT INTO `cluster_tags` (`id`, `name`, `type`, `port`, `argument`, `memo`)
VALUES
	(52,'mysql',2,3306,'',''),
	(58,'nginx',2,80,'',''),
	(64,'redis',2,6379,'',''),
	(88,'admin',3,0,'',''),
	(97,'master',1,0,'',''),
	(109,'slave',1,0,'',''),
	(115,'web',3,0,'',''),
	(124,'docker',1,0,'',''),
	(127,'ansible',3,0,'','');

/*!40000 ALTER TABLE `cluster_tags` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table cluster_task_privacy
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cluster_task_privacy`;

CREATE TABLE `cluster_task_privacy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_uid` int(11) NOT NULL DEFAULT '0',
  `task_id` int(11) NOT NULL DEFAULT '0',
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `add_uid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_uid` (`admin_uid`,`task_id`),
  KEY `task_id` (`task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table operation_menu
# ------------------------------------------------------------

DROP TABLE IF EXISTS `operation_menu`;

CREATE TABLE `operation_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL DEFAULT '0',
  `type` varchar(20) NOT NULL DEFAULT '0',
  `hidden` tinyint(1) NOT NULL DEFAULT '0',
  `icon` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `url` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `operation_menu` WRITE;
/*!40000 ALTER TABLE `operation_menu` DISABLE KEYS */;

INSERT INTO `operation_menu` (`id`, `text`, `url`, `sort`, `type`, `hidden`, `icon`)
VALUES
    (1,'系统设置','/system/',0,'0',0,'fa-gears'),
    (2,'服务器','/server/',0,'0',0,'fa-server'),
    (3,'节点配置','/system/core/nodes',0,'0',0,'fa-bars'),
    (4,'用户管理','/system/core/administrator',0,'0',0,'fa-users'),
    (6,'节点列表','/server/core/nodes',0,'0',0,'fa-list'),
    (11,'标签管理','/server/core/tag',0,'0',0,'fa-tags'),
    (12,'远程管理','#',0,'0',0,'fa-terminal'),
    (42,'快速任务','/server/remote/base',0,'0',0,'fa-bolt'),
    (43,'高级操作','/server/remote/index',0,'0',0,'fa-tty'),
    (46,'Shell脚本管理','/server/remote/script',0,'0',0,'fa-file-code-o'),
    (49,'Playbook管理','/server/remote/playbook',0,'0',0,'fa-file-code-o'),
    (52,'Authkeys管理','/server/remote/authkeys',0,'0',0,'fa-file-code-o'),
    (55,'任务授权','/server/remote/task',0,'0',0,'fa-tasks'),
    (60,'机柜管理','/server/hardware/cabinet',0,'0',0,'fa-sitemap'),
    (61,'资产管理','/server/hardware/assets',0,'0',0,'fa-cubes'),
    (62,'硬件设备','#',0,'0',0,'fa-desktop');

/*!40000 ALTER TABLE `operation_menu` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table operation_menu_privacy
# ------------------------------------------------------------

DROP TABLE IF EXISTS `operation_menu_privacy`;

CREATE TABLE `operation_menu_privacy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_uid` int(11) NOT NULL DEFAULT '0',
  `menu_id` int(11) NOT NULL DEFAULT '0',
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `add_uid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_uid` (`admin_uid`,`menu_id`),
  KEY `menu_id` (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table operation_menu_tree
# ------------------------------------------------------------

DROP TABLE IF EXISTS `operation_menu_tree`;

CREATE TABLE `operation_menu_tree` (
  `id` int(11) unsigned NOT NULL DEFAULT '0',
  `pid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `operation_menu_tree` WRITE;
/*!40000 ALTER TABLE `operation_menu_tree` DISABLE KEYS */;

INSERT INTO `operation_menu_tree` (`id`, `pid`)
VALUES
	(1,0),
	(2,0),
	(3,1),
	(4,1),
	(6,2),
	(11,2),
	(12,2),
	(62,2),
	(5,5),
	(42,12),
	(43,12),
	(46,12),
	(49,12),
	(52,12),
	(55,12),
	(60,62),
	(61,62);

/*!40000 ALTER TABLE `operation_menu_tree` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table operation_remote_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `operation_remote_log`;

CREATE TABLE `operation_remote_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `type` varchar(10) NOT NULL DEFAULT '',
  `target` varchar(750) NOT NULL DEFAULT '',
  `command` varchar(255) NOT NULL DEFAULT '',
  `result` text,
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `rc` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table operation_role
# ------------------------------------------------------------

DROP TABLE IF EXISTS `operation_role`;

CREATE TABLE `operation_role` (
  `rid` int(11) NOT NULL AUTO_INCREMENT,
  `rname` varchar(40) NOT NULL DEFAULT '',
  `flag` tinyint(1) NOT NULL DEFAULT '0',
  `ctime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `add_uid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rid`),
  KEY `mtime` (`mtime`),
  KEY `ctime` (`ctime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table operation_role_menu_privacy
# ------------------------------------------------------------

DROP TABLE IF EXISTS `operation_role_menu_privacy`;

CREATE TABLE `operation_role_menu_privacy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL DEFAULT '0',
  `menu_id` int(11) NOT NULL DEFAULT '0',
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `add_uid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `rid_menu_id` (`rid`,`menu_id`),
  KEY `menu_id_rid` (`menu_id`,`rid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table operation_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `operation_user`;

CREATE TABLE `operation_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL DEFAULT '',
  `salt` int(11) NOT NULL DEFAULT '0',
  `flag` tinyint(4) NOT NULL DEFAULT '0',
  `add_uid` int(11) NOT NULL DEFAULT '0',
  `ctime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table operation_user_hashpass
# ------------------------------------------------------------

DROP TABLE IF EXISTS `operation_user_hashpass`;

CREATE TABLE `operation_user_hashpass` (
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `salt` varchar(64) NOT NULL DEFAULT '',
  `hash` varchar(64) NOT NULL DEFAULT '',
  UNIQUE KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table operation_user_username
# ------------------------------------------------------------

DROP TABLE IF EXISTS `operation_user_username`;

CREATE TABLE `operation_user_username` (
  `username` varchar(128) NOT NULL DEFAULT '',
  `src` varchar(32) NOT NULL DEFAULT '',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `username` (`username`,`src`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table operation_user_varsalt
# ------------------------------------------------------------

DROP TABLE IF EXISTS `operation_user_varsalt`;

CREATE TABLE `operation_user_varsalt` (
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `salt` varchar(64) NOT NULL DEFAULT '',
  `oldsalt` varchar(64) NOT NULL DEFAULT '',
  `mtime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
