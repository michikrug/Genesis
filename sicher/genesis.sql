# MySQL-Front 3.2  (Build 14.3)

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES latin1 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='SYSTEM' */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE */;
/*!40101 SET SQL_MODE='' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES */;
/*!40103 SET SQL_NOTES='ON' */;


# Host: localhost    Database: genesis_squeeeze
# ------------------------------------------------------
# Server version 5.0.18-nt-log

#
# Table structure for table gen_chat
#

CREATE TABLE `gen_chat` (
  `id` bigint(12) unsigned NOT NULL auto_increment,
  `user_id` int(11) default '0',
  `name` char(30) default NULL,
  `text` char(255) default NULL,
  `intern` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

#
# Dumping data for table gen_chat
#

/*!40101 SET NAMES utf8 */;


/*!40101 SET NAMES latin1 */;

#
# Table structure for table gen_comments
#

CREATE TABLE `gen_comments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `zeit` int(10) unsigned NOT NULL default '0',
  `news_id` int(10) unsigned NOT NULL default '0',
  `name` varchar(30) NOT NULL default '',
  `text` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

#
# Dumping data for table gen_comments
#


#
# Table structure for table gen_news
#

CREATE TABLE `gen_news` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `zeit` int(10) unsigned NOT NULL default '0',
  `headline` varchar(40) NOT NULL default '',
  `text` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

#
# Dumping data for table gen_news
#


#
# Table structure for table gen_shoutbox
#

CREATE TABLE `gen_shoutbox` (
  `id` int(11) NOT NULL auto_increment,
  `zeit` int(11) unsigned NOT NULL default '0',
  `name` varchar(30) NOT NULL default '',
  `text` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

#
# Dumping data for table gen_shoutbox
#


#
# Table structure for table genesis_aktionen
#

CREATE TABLE `genesis_aktionen` (
  `id` int(11) NOT NULL auto_increment,
  `startzeit` int(11) unsigned NOT NULL default '0',
  `endzeit` int(11) unsigned NOT NULL default '0',
  `basis1` char(8) NOT NULL default '',
  `basis2` char(8) default NULL,
  `typ` char(10) NOT NULL default '',
  `aktion` char(10) NOT NULL default '',
  `einheiten` char(100) default NULL,
  `ress` char(100) default NULL,
  `zusatz` char(100) default NULL,
  `done` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `endzeit` (`endzeit`),
  KEY `typ` (`typ`),
  KEY `basis1` (`basis1`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

#
# Dumping data for table genesis_aktionen
#


#
# Table structure for table genesis_allianzen
#

CREATE TABLE `genesis_allianzen` (
  `id` int(11) NOT NULL auto_increment,
  `tag` varchar(10) NOT NULL default '',
  `name` varchar(100) NOT NULL default '',
  `beschreibung` longtext,
  `intern` longtext,
  `url` varchar(100) default NULL,
  `forum` varchar(100) default NULL,
  `bild` varchar(100) default NULL,
  `anz` int(3) unsigned NOT NULL default '1',
  `punkte` int(10) unsigned NOT NULL default '0',
  `punktek` int(10) unsigned NOT NULL default '0',
  `punktef` int(10) unsigned NOT NULL default '0',
  `punktem` int(10) unsigned NOT NULL default '0',
  `punkted` int(10) unsigned NOT NULL default '0',
  `kampfpkt` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

#
# Dumping data for table genesis_allianzen
#


#
# Table structure for table genesis_att
#

CREATE TABLE `genesis_att` (
  `id` char(10) default NULL,
  `alli` char(12) default NULL,
  `name` char(30) NOT NULL default '',
  `koords` char(8) NOT NULL default '',
  `prod1` int(6) unsigned NOT NULL default '0',
  `prod2` int(6) unsigned NOT NULL default '0',
  `prod3` int(6) unsigned NOT NULL default '0',
  `prod4` int(6) unsigned NOT NULL default '0',
  `prod5` int(6) unsigned NOT NULL default '0',
  `prod6` int(6) unsigned NOT NULL default '0',
  `prod7` int(6) unsigned NOT NULL default '0',
  `prod8` int(6) unsigned NOT NULL default '0',
  `prodv1` int(6) unsigned NOT NULL default '0',
  `prodv2` int(6) unsigned NOT NULL default '0',
  `prodv3` int(6) unsigned NOT NULL default '0',
  `prodv4` int(6) unsigned NOT NULL default '0',
  `prodv5` int(6) unsigned NOT NULL default '0',
  `prodv6` int(6) unsigned NOT NULL default '0',
  `prodv7` int(6) unsigned NOT NULL default '0',
  `prodv8` int(6) unsigned NOT NULL default '0',
  `ress1` int(10) unsigned NOT NULL default '0',
  `ress2` int(10) unsigned NOT NULL default '0',
  `ress3` int(10) unsigned NOT NULL default '0',
  `ress4` int(10) unsigned NOT NULL default '0',
  `ress5` int(10) unsigned NOT NULL default '0',
  `kp` int(7) unsigned NOT NULL default '0',
  `bonus` int(11) NOT NULL default '0',
  KEY `id` (`id`),
  KEY `name` (`name`),
  KEY `alli` (`alli`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

#
# Dumping data for table genesis_att
#


#
# Table structure for table genesis_basen
#

CREATE TABLE `genesis_basen` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `koordx` int(2) unsigned NOT NULL default '0',
  `koordy` int(2) unsigned NOT NULL default '0',
  `koordz` int(2) unsigned NOT NULL default '0',
  `ress1` int(10) unsigned NOT NULL default '0',
  `ress2` int(10) unsigned NOT NULL default '0',
  `ress3` int(10) unsigned NOT NULL default '0',
  `ress4` int(10) unsigned NOT NULL default '0',
  `ress5` int(10) unsigned NOT NULL default '0',
  `name` char(30) NOT NULL default '',
  `bname` char(30) NOT NULL default '',
  `konst1` int(3) unsigned NOT NULL default '0',
  `konst2` int(3) unsigned NOT NULL default '0',
  `konst3` int(3) unsigned NOT NULL default '0',
  `konst4` int(3) unsigned NOT NULL default '0',
  `konst5` int(3) unsigned NOT NULL default '0',
  `konst6` int(3) unsigned NOT NULL default '0',
  `konst7` int(3) unsigned NOT NULL default '0',
  `konst8` int(3) unsigned NOT NULL default '0',
  `konst9` int(3) unsigned NOT NULL default '0',
  `konst10` int(3) unsigned NOT NULL default '0',
  `konst11` int(3) unsigned NOT NULL default '0',
  `konst12` int(3) unsigned NOT NULL default '0',
  `konst13` int(3) unsigned NOT NULL default '0',
  `konst14` int(3) unsigned NOT NULL default '0',
  `konst15` int(3) unsigned NOT NULL default '0',
  `konst16` int(3) unsigned NOT NULL default '0',
  `konst17` int(3) unsigned NOT NULL default '0',
  `prod1` int(6) unsigned NOT NULL default '0',
  `prod2` int(6) unsigned NOT NULL default '0',
  `prod3` int(6) unsigned NOT NULL default '0',
  `prod4` int(6) unsigned NOT NULL default '0',
  `prod5` int(6) unsigned NOT NULL default '0',
  `prod6` int(6) unsigned NOT NULL default '0',
  `prod7` int(6) unsigned NOT NULL default '0',
  `prod8` int(1) unsigned NOT NULL default '0',
  `vert1` int(6) unsigned NOT NULL default '0',
  `vert2` int(6) unsigned NOT NULL default '0',
  `vert3` int(6) unsigned NOT NULL default '0',
  `punkte` int(10) unsigned NOT NULL default '0',
  `resszeit` int(11) unsigned NOT NULL default '0',
  `verbrauch` int(10) unsigned NOT NULL default '0',
  `typ` int(1) unsigned NOT NULL default '0',
  `bonus` int(11) unsigned NOT NULL default '0',
  `bild` int(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `koordx` (`koordx`),
  KEY `koordy` (`koordy`),
  KEY `koordz` (`koordz`),
  KEY `name` (`name`),
  KEY `resszeit` (`resszeit`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

#
# Dumping data for table genesis_basen
#

INSERT INTO `genesis_basen` VALUES (1,1,1,1,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4);
INSERT INTO `genesis_basen` VALUES (2,1,1,2,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4);
INSERT INTO `genesis_basen` VALUES (3,1,1,3,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (4,1,1,4,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4);
INSERT INTO `genesis_basen` VALUES (5,1,1,5,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (6,1,1,6,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3);
INSERT INTO `genesis_basen` VALUES (13,1,2,1,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,6);
INSERT INTO `genesis_basen` VALUES (14,1,2,2,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,6);
INSERT INTO `genesis_basen` VALUES (15,1,2,3,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (16,1,2,4,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4);
INSERT INTO `genesis_basen` VALUES (17,1,2,5,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,6);
INSERT INTO `genesis_basen` VALUES (18,1,2,6,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,2);
INSERT INTO `genesis_basen` VALUES (25,1,3,1,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (26,1,3,2,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3);
INSERT INTO `genesis_basen` VALUES (27,1,3,3,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,2);
INSERT INTO `genesis_basen` VALUES (28,1,3,4,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,6);
INSERT INTO `genesis_basen` VALUES (29,1,3,5,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,2);
INSERT INTO `genesis_basen` VALUES (30,1,3,6,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4);
INSERT INTO `genesis_basen` VALUES (37,1,4,1,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3);
INSERT INTO `genesis_basen` VALUES (38,1,4,2,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,6);
INSERT INTO `genesis_basen` VALUES (39,1,4,3,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4);
INSERT INTO `genesis_basen` VALUES (40,1,4,4,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,5);
INSERT INTO `genesis_basen` VALUES (41,1,4,5,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,5);
INSERT INTO `genesis_basen` VALUES (42,1,4,6,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,6);
INSERT INTO `genesis_basen` VALUES (49,1,5,1,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,6);
INSERT INTO `genesis_basen` VALUES (50,1,5,2,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,2);
INSERT INTO `genesis_basen` VALUES (51,1,5,3,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (52,1,5,4,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4);
INSERT INTO `genesis_basen` VALUES (53,1,5,5,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4);
INSERT INTO `genesis_basen` VALUES (54,1,5,6,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (61,1,6,1,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,5);
INSERT INTO `genesis_basen` VALUES (62,1,6,2,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4);
INSERT INTO `genesis_basen` VALUES (63,1,6,3,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (64,1,6,4,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (65,1,6,5,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,2);
INSERT INTO `genesis_basen` VALUES (66,1,6,6,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,6);
INSERT INTO `genesis_basen` VALUES (145,2,1,1,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,2);
INSERT INTO `genesis_basen` VALUES (146,2,1,2,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3);
INSERT INTO `genesis_basen` VALUES (147,2,1,3,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4);
INSERT INTO `genesis_basen` VALUES (148,2,1,4,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4);
INSERT INTO `genesis_basen` VALUES (149,2,1,5,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,5);
INSERT INTO `genesis_basen` VALUES (150,2,1,6,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4);
INSERT INTO `genesis_basen` VALUES (157,2,2,1,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4);
INSERT INTO `genesis_basen` VALUES (158,2,2,2,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (159,2,2,3,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,2);
INSERT INTO `genesis_basen` VALUES (160,2,2,4,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4);
INSERT INTO `genesis_basen` VALUES (161,2,2,5,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,2);
INSERT INTO `genesis_basen` VALUES (162,2,2,6,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (169,2,3,1,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (170,2,3,2,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,6);
INSERT INTO `genesis_basen` VALUES (171,2,3,3,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3);
INSERT INTO `genesis_basen` VALUES (172,2,3,4,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,5);
INSERT INTO `genesis_basen` VALUES (173,2,3,5,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (174,2,3,6,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3);
INSERT INTO `genesis_basen` VALUES (181,2,4,1,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3);
INSERT INTO `genesis_basen` VALUES (182,2,4,2,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,6);
INSERT INTO `genesis_basen` VALUES (183,2,4,3,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,2);
INSERT INTO `genesis_basen` VALUES (184,2,4,4,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,5);
INSERT INTO `genesis_basen` VALUES (185,2,4,5,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,2);
INSERT INTO `genesis_basen` VALUES (186,2,4,6,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (193,2,5,1,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (194,2,5,2,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,5);
INSERT INTO `genesis_basen` VALUES (195,2,5,3,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,5);
INSERT INTO `genesis_basen` VALUES (196,2,5,4,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (197,2,5,5,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,2);
INSERT INTO `genesis_basen` VALUES (198,2,5,6,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4);
INSERT INTO `genesis_basen` VALUES (205,2,6,1,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (206,2,6,2,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4);
INSERT INTO `genesis_basen` VALUES (207,2,6,3,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3);
INSERT INTO `genesis_basen` VALUES (208,2,6,4,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,6);
INSERT INTO `genesis_basen` VALUES (209,2,6,5,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,6);
INSERT INTO `genesis_basen` VALUES (210,2,6,6,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4);
INSERT INTO `genesis_basen` VALUES (289,3,1,1,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4);
INSERT INTO `genesis_basen` VALUES (290,3,1,2,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (291,3,1,3,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4);
INSERT INTO `genesis_basen` VALUES (292,3,1,4,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3);
INSERT INTO `genesis_basen` VALUES (293,3,1,5,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4);
INSERT INTO `genesis_basen` VALUES (294,3,1,6,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (301,3,2,1,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3);
INSERT INTO `genesis_basen` VALUES (302,3,2,2,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,6);
INSERT INTO `genesis_basen` VALUES (303,3,2,3,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,5);
INSERT INTO `genesis_basen` VALUES (304,3,2,4,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (305,3,2,5,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,6);
INSERT INTO `genesis_basen` VALUES (306,3,2,6,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (313,3,3,1,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,6);
INSERT INTO `genesis_basen` VALUES (314,3,3,2,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3);
INSERT INTO `genesis_basen` VALUES (315,3,3,3,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,5);
INSERT INTO `genesis_basen` VALUES (316,3,3,4,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,2);
INSERT INTO `genesis_basen` VALUES (317,3,3,5,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,2);
INSERT INTO `genesis_basen` VALUES (318,3,3,6,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,5);
INSERT INTO `genesis_basen` VALUES (325,3,4,1,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,2);
INSERT INTO `genesis_basen` VALUES (326,3,4,2,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (327,3,4,3,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4);
INSERT INTO `genesis_basen` VALUES (328,3,4,4,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (329,3,4,5,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4);
INSERT INTO `genesis_basen` VALUES (330,3,4,6,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (337,3,5,1,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3);
INSERT INTO `genesis_basen` VALUES (338,3,5,2,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (339,3,5,3,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,5);
INSERT INTO `genesis_basen` VALUES (340,3,5,4,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,2);
INSERT INTO `genesis_basen` VALUES (341,3,5,5,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (342,3,5,6,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (349,3,6,1,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,6);
INSERT INTO `genesis_basen` VALUES (350,3,6,2,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (351,3,6,3,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4);
INSERT INTO `genesis_basen` VALUES (352,3,6,4,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,6);
INSERT INTO `genesis_basen` VALUES (353,3,6,5,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (354,3,6,6,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,5);
INSERT INTO `genesis_basen` VALUES (433,4,1,1,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (434,4,1,2,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,6);
INSERT INTO `genesis_basen` VALUES (435,4,1,3,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (436,4,1,4,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (437,4,1,5,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4);
INSERT INTO `genesis_basen` VALUES (438,4,1,6,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,5);
INSERT INTO `genesis_basen` VALUES (445,4,2,1,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (446,4,2,2,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3);
INSERT INTO `genesis_basen` VALUES (447,4,2,3,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (448,4,2,4,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,2);
INSERT INTO `genesis_basen` VALUES (449,4,2,5,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,6);
INSERT INTO `genesis_basen` VALUES (450,4,2,6,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,5);
INSERT INTO `genesis_basen` VALUES (457,4,3,1,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,6);
INSERT INTO `genesis_basen` VALUES (458,4,3,2,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (459,4,3,3,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,5);
INSERT INTO `genesis_basen` VALUES (460,4,3,4,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (461,4,3,5,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (462,4,3,6,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3);
INSERT INTO `genesis_basen` VALUES (469,4,4,1,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,6);
INSERT INTO `genesis_basen` VALUES (470,4,4,2,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (471,4,4,3,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3);
INSERT INTO `genesis_basen` VALUES (472,4,4,4,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,2);
INSERT INTO `genesis_basen` VALUES (473,4,4,5,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,6);
INSERT INTO `genesis_basen` VALUES (474,4,4,6,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4);
INSERT INTO `genesis_basen` VALUES (481,4,5,1,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4);
INSERT INTO `genesis_basen` VALUES (482,4,5,2,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,6);
INSERT INTO `genesis_basen` VALUES (483,4,5,3,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (484,4,5,4,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4);
INSERT INTO `genesis_basen` VALUES (485,4,5,5,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (486,4,5,6,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (493,4,6,1,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4);
INSERT INTO `genesis_basen` VALUES (494,4,6,2,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3);
INSERT INTO `genesis_basen` VALUES (495,4,6,3,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,2);
INSERT INTO `genesis_basen` VALUES (496,4,6,4,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (497,4,6,5,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3);
INSERT INTO `genesis_basen` VALUES (498,4,6,6,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3);
INSERT INTO `genesis_basen` VALUES (577,5,1,1,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (578,5,1,2,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,5);
INSERT INTO `genesis_basen` VALUES (579,5,1,3,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,2);
INSERT INTO `genesis_basen` VALUES (580,5,1,4,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3);
INSERT INTO `genesis_basen` VALUES (581,5,1,5,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (582,5,1,6,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,5);
INSERT INTO `genesis_basen` VALUES (589,5,2,1,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,2);
INSERT INTO `genesis_basen` VALUES (590,5,2,2,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,6);
INSERT INTO `genesis_basen` VALUES (591,5,2,3,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,5);
INSERT INTO `genesis_basen` VALUES (592,5,2,4,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (593,5,2,5,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,2);
INSERT INTO `genesis_basen` VALUES (594,5,2,6,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4);
INSERT INTO `genesis_basen` VALUES (601,5,3,1,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4);
INSERT INTO `genesis_basen` VALUES (602,5,3,2,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3);
INSERT INTO `genesis_basen` VALUES (603,5,3,3,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3);
INSERT INTO `genesis_basen` VALUES (604,5,3,4,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,2);
INSERT INTO `genesis_basen` VALUES (605,5,3,5,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (606,5,3,6,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4);
INSERT INTO `genesis_basen` VALUES (613,5,4,1,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3);
INSERT INTO `genesis_basen` VALUES (614,5,4,2,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,5);
INSERT INTO `genesis_basen` VALUES (615,5,4,3,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,2);
INSERT INTO `genesis_basen` VALUES (616,5,4,4,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,5);
INSERT INTO `genesis_basen` VALUES (617,5,4,5,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,2);
INSERT INTO `genesis_basen` VALUES (618,5,4,6,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (625,5,5,1,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (626,5,5,2,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (627,5,5,3,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (628,5,5,4,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,6);
INSERT INTO `genesis_basen` VALUES (629,5,5,5,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,6);
INSERT INTO `genesis_basen` VALUES (630,5,5,6,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,5);
INSERT INTO `genesis_basen` VALUES (637,5,6,1,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (638,5,6,2,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3);
INSERT INTO `genesis_basen` VALUES (639,5,6,3,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,6);
INSERT INTO `genesis_basen` VALUES (640,5,6,4,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3);
INSERT INTO `genesis_basen` VALUES (641,5,6,5,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3);
INSERT INTO `genesis_basen` VALUES (642,5,6,6,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3);
INSERT INTO `genesis_basen` VALUES (721,6,1,1,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3);
INSERT INTO `genesis_basen` VALUES (722,6,1,2,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (723,6,1,3,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4);
INSERT INTO `genesis_basen` VALUES (724,6,1,4,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (725,6,1,5,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,5);
INSERT INTO `genesis_basen` VALUES (726,6,1,6,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (733,6,2,1,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (734,6,2,2,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (735,6,2,3,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,5);
INSERT INTO `genesis_basen` VALUES (736,6,2,4,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,6);
INSERT INTO `genesis_basen` VALUES (737,6,2,5,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,2);
INSERT INTO `genesis_basen` VALUES (738,6,2,6,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,2);
INSERT INTO `genesis_basen` VALUES (745,6,3,1,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,2);
INSERT INTO `genesis_basen` VALUES (746,6,3,2,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4);
INSERT INTO `genesis_basen` VALUES (747,6,3,3,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (748,6,3,4,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (749,6,3,5,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4);
INSERT INTO `genesis_basen` VALUES (750,6,3,6,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,2);
INSERT INTO `genesis_basen` VALUES (757,6,4,1,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (758,6,4,2,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4);
INSERT INTO `genesis_basen` VALUES (759,6,4,3,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,2);
INSERT INTO `genesis_basen` VALUES (760,6,4,4,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,6);
INSERT INTO `genesis_basen` VALUES (761,6,4,5,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3);
INSERT INTO `genesis_basen` VALUES (762,6,4,6,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3);
INSERT INTO `genesis_basen` VALUES (769,6,5,1,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,6);
INSERT INTO `genesis_basen` VALUES (770,6,5,2,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (771,6,5,3,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4);
INSERT INTO `genesis_basen` VALUES (772,6,5,4,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (773,6,5,5,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,6);
INSERT INTO `genesis_basen` VALUES (774,6,5,6,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4);
INSERT INTO `genesis_basen` VALUES (781,6,6,1,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,2);
INSERT INTO `genesis_basen` VALUES (782,6,6,2,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,2);
INSERT INTO `genesis_basen` VALUES (783,6,6,3,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
INSERT INTO `genesis_basen` VALUES (784,6,6,4,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3);
INSERT INTO `genesis_basen` VALUES (785,6,6,5,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,6);
INSERT INTO `genesis_basen` VALUES (786,6,6,6,0,0,0,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3);

#
# Table structure for table genesis_berichte
#

CREATE TABLE `genesis_berichte` (
  `id` char(10) NOT NULL default '',
  `typ` int(1) unsigned NOT NULL default '0',
  `zeit` int(11) unsigned NOT NULL default '0',
  `koords` char(8) NOT NULL default '',
  `konst1` int(3) unsigned NOT NULL default '0',
  `konst2` int(3) unsigned NOT NULL default '0',
  `konst3` int(3) unsigned NOT NULL default '0',
  `konst4` int(3) unsigned NOT NULL default '0',
  `konst5` int(3) unsigned NOT NULL default '0',
  `konst6` int(3) unsigned NOT NULL default '0',
  `konst7` int(3) unsigned NOT NULL default '0',
  `konst8` int(3) unsigned NOT NULL default '0',
  `konst9` int(3) unsigned NOT NULL default '0',
  `konst10` int(3) unsigned NOT NULL default '0',
  `konst11` int(3) unsigned NOT NULL default '0',
  `konst12` int(3) unsigned NOT NULL default '0',
  `konst13` int(3) unsigned NOT NULL default '0',
  `konst14` int(3) unsigned NOT NULL default '0',
  `konst15` int(3) unsigned NOT NULL default '0',
  `konst16` int(3) unsigned NOT NULL default '0',
  `konst17` int(3) unsigned NOT NULL default '0',
  `forsch1` int(3) unsigned NOT NULL default '0',
  `forsch2` int(3) unsigned NOT NULL default '0',
  `forsch3` int(3) unsigned NOT NULL default '0',
  `forsch4` int(3) unsigned NOT NULL default '0',
  `forsch5` int(3) unsigned NOT NULL default '0',
  `forsch6` int(3) unsigned NOT NULL default '0',
  `forsch7` int(3) unsigned NOT NULL default '0',
  `forsch8` int(3) unsigned NOT NULL default '0',
  `ress1` int(10) unsigned NOT NULL default '0',
  `ress2` int(10) unsigned NOT NULL default '0',
  `ress3` int(10) unsigned NOT NULL default '0',
  `ress4` int(10) unsigned NOT NULL default '0',
  `ress5` int(10) unsigned NOT NULL default '0',
  `filter` char(43) NOT NULL default '0',
  `zusatz` char(100) default NULL,
  PRIMARY KEY  (`id`),
  KEY `typ` (`typ`),
  KEY `zeit` (`zeit`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

#
# Dumping data for table genesis_berichte
#


#
# Table structure for table genesis_check
#

CREATE TABLE `genesis_check` (
  `id` tinyint(1) NOT NULL auto_increment,
  `aktiv` int(1) unsigned NOT NULL default '0',
  `zeit` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

#
# Dumping data for table genesis_check
#

INSERT INTO `genesis_check` VALUES (1,0,1230510673);

#
# Table structure for table genesis_codes
#

CREATE TABLE `genesis_codes` (
  `ip` char(15) NOT NULL default '',
  `zeit` bigint(12) unsigned NOT NULL default '0',
  `code` char(10) NOT NULL default '',
  PRIMARY KEY  (`ip`),
  KEY `zeit` (`zeit`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

#
# Dumping data for table genesis_codes
#


#
# Table structure for table genesis_deff
#

CREATE TABLE `genesis_deff` (
  `id` char(10) default NULL,
  `alli` char(12) NOT NULL default '',
  `name` char(30) default NULL,
  `koords` char(8) default NULL,
  `prod1` int(6) unsigned NOT NULL default '0',
  `prod2` int(6) unsigned NOT NULL default '0',
  `prod3` int(6) unsigned NOT NULL default '0',
  `prod4` int(6) unsigned NOT NULL default '0',
  `prod5` int(6) unsigned NOT NULL default '0',
  `prod6` int(6) unsigned NOT NULL default '0',
  `prod7` int(6) unsigned NOT NULL default '0',
  `prod8` int(6) unsigned NOT NULL default '0',
  `vert1` int(6) unsigned NOT NULL default '0',
  `vert2` int(6) unsigned NOT NULL default '0',
  `vert3` int(6) unsigned NOT NULL default '0',
  `prodv1` int(6) unsigned NOT NULL default '0',
  `prodv2` int(6) unsigned NOT NULL default '0',
  `prodv3` int(6) unsigned NOT NULL default '0',
  `prodv4` int(6) unsigned NOT NULL default '0',
  `prodv5` int(6) unsigned NOT NULL default '0',
  `prodv6` int(6) unsigned NOT NULL default '0',
  `prodv7` int(6) unsigned NOT NULL default '0',
  `prodv8` int(6) unsigned NOT NULL default '0',
  `vertv1` int(6) unsigned NOT NULL default '0',
  `vertv2` int(6) unsigned NOT NULL default '0',
  `vertv3` int(6) unsigned NOT NULL default '0',
  `kp` int(7) unsigned NOT NULL default '0',
  `bonus` int(11) NOT NULL default '0',
  KEY `id` (`id`),
  KEY `name` (`name`),
  KEY `alli` (`alli`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

#
# Dumping data for table genesis_deff
#


#
# Table structure for table genesis_handel
#

CREATE TABLE `genesis_handel` (
  `id` int(11) NOT NULL auto_increment,
  `zeit` int(11) unsigned NOT NULL default '0',
  `sucher` int(11) unsigned NOT NULL default '0',
  `bieter` int(11) unsigned NOT NULL default '0',
  `typ_geb` int(1) unsigned NOT NULL default '1',
  `typ_such` int(1) unsigned NOT NULL default '1',
  `anz_geb` int(7) unsigned NOT NULL default '0',
  `anz_such` int(7) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `sucher` (`sucher`),
  KEY `bieter` (`bieter`),
  KEY `zeit` (`zeit`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

#
# Dumping data for table genesis_handel
#


#
# Table structure for table genesis_history
#

CREATE TABLE `genesis_history` (
  `id` int(11) NOT NULL auto_increment,
  `alli1` char(100) NOT NULL default '',
  `alli2` char(100) NOT NULL default '',
  `typ` int(1) unsigned NOT NULL default '0',
  `zeit` int(11) unsigned NOT NULL default '0',
  `zusatz` int(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `alli1` (`alli1`),
  KEY `alli2` (`alli2`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

#
# Dumping data for table genesis_history
#


#
# Table structure for table genesis_infos
#

CREATE TABLE `genesis_infos` (
  `id` int(11) NOT NULL auto_increment,
  `typ` varchar(10) default NULL,
  `bezeichnung` varchar(30) default NULL,
  `beschreibung` longtext NOT NULL,
  `ress1` int(10) NOT NULL default '0',
  `ress2` int(10) NOT NULL default '0',
  `ress3` int(10) NOT NULL default '0',
  `ress4` int(10) NOT NULL default '0',
  `dauer` int(10) NOT NULL default '0',
  `wert1` int(10) NOT NULL default '0',
  `wert2` int(10) NOT NULL default '0',
  `wert3` int(10) NOT NULL default '0',
  `wert4` int(10) NOT NULL default '0',
  `wert5` int(10) NOT NULL default '0',
  `wert6` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `typ` (`typ`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

#
# Dumping data for table genesis_infos
#

INSERT INTO `genesis_infos` VALUES (1,'konst1','Retikulum','Das Retikulum ist ein reich verzweigtes System flächiger Hohlräume, die von Membranen umschlossen sind. \nHier werden die Nähr- und Baustoffe effektiv weiterverarbeitet. \nHöheren Stufen ermöglichen schnelleres Wachstum des Neogens.',800,500,0,0,18,0,10,0,0,0,0);
INSERT INTO `genesis_infos` VALUES (2,'konst2','Adenin-Extraktor','Aus dem umliegenden Medium wird der jeweilige Nähr- und Baustoff entzogen. \nHöhere Ausbaustufe erhöht die Effizienz.',450,200,0,0,8,50,5,0,0,0,0);
INSERT INTO `genesis_infos` VALUES (3,'konst3','Thymin-Extraktor','Aus dem umliegenden Medium wird der jeweilige Nähr- und Baustoff entzogen. \nHöhere Ausbaustufe erhöht die Effizienz.',440,150,0,0,8,45,6,0,0,0,0);
INSERT INTO `genesis_infos` VALUES (4,'konst4','Guanin-Extraktor','Aus dem umliegenden Medium wird der jeweilige Nähr- und Baustoff entzogen. \nHöhere Ausbaustufe erhöht die Effizienz.',460,270,0,0,9,40,7,0,0,0,0);
INSERT INTO `genesis_infos` VALUES (5,'konst5','Cytosin-Extraktor','Aus dem umliegenden Medium wird der jeweilige Nähr- und Baustoff entzogen. \nHöhere Ausbaustufe erhöht die Effizienz.',450,280,0,0,9,40,7,0,0,0,0);
INSERT INTO `genesis_infos` VALUES (6,'konst7','Knochenmark','Im Knochenmark werden die Exo-Zellen produziert, die das Neogen verlassen können. \nHöhere Ausbaustufen ermöglichen eine schnellere Produktion von Exo-Zellen.',1500,2100,1200,1100,37,0,50,0,0,0,0);
INSERT INTO `genesis_infos` VALUES (7,'konst8','Großhirn','Hier ist das Lernzentrum des Neogen. \nJe mehr Nährstoffe dem Großhirn zugeführt werden, desto schneller lernt es.',1200,1000,600,500,18,0,25,0,0,0,0);
INSERT INTO `genesis_infos` VALUES (8,'forsch1','Mutation','Mutation ist das A und O des biologischen Fortschritts. Sie wird für die Exo-Zellenproduktion benötigt. \nSie verleiht jeder Exo-Zelle, dem Immunsystem, sowie den Antikörpern ihre Angriffsstärke. \nJe intensiver erlernt, desto höher wird der Angriffswert aller Exo-Zellen.',4200,1600,500,500,112,0,60,0,0,0,0);
INSERT INTO `genesis_infos` VALUES (9,'forsch2','Lokomotion','Egal ob mit Flagellen oder Cilien irgendwie müssen sich die Exo-Zellen fortbewegen können. \nDurch intensive Beschäftigung mit der Lokomotion entwickelt das Neogen immer effektivere und damit schnellere Methoden der Fortbewegung für die Exo-Zellen.',3100,3000,0,0,135,0,60,0,0,0,0);
INSERT INTO `genesis_infos` VALUES (10,'forsch3','Karyogamie','Durch das Erlernen der hochkomplizierten Zellkernverschmelzung schaffst du es aus einem Haufen kleiner Exo-Zellen eine große und immens mächtige Exo-Zelle zu bilden, den Determinator.\r\nDiese Evolution verstärkt mit jeder Stufe den Angriffs- und Verteidigungswert dieser Zelle.',4000,4000,10000,10000,205,0,100,0,0,0,0);
INSERT INTO `genesis_infos` VALUES (11,'forsch4','Immunität','Immunität ist eine Basislehre, die für die Exo-Zellenproduktion benötigt wird. \nSie verleiht jeder Exo-Zelle ihre Verteidungsstäke. \nJe intensiver erlernt, desto höher wird der Verteidungswert aller Exo-Zellen. \nDesweiteren ermöglicht sie den Bau des Immunsystems, das die Grundverteidigung jedes Neogens gewährleistet.',1000,4400,1300,1000,152,0,40,0,0,0,0);
INSERT INTO `genesis_infos` VALUES (12,'forsch5','Sensorik','Durch das Erlernen von Sensorik wird es dem Neogen ermöglicht, sensorische Auswüchse auszubilden. \nMit diesen können sich nähernde Exo-Zellen entdeckt werden. \nJe intensiver die Lehre der Sensorik vertieft wird, desto empfindlicher werden die Auswüchse und Exo-Zellen können früher entdeckt werden.',4000,2500,1600,1600,193,0,30,0,0,0,0);
INSERT INTO `genesis_infos` VALUES (17,'konst9','Adenin-Speichervesikel','In den Speichervesikeln werden die Nährstoffe gelagert bis sie verbraucht werden. \nGleichzeitig bieten sie einen Plünderschutz und sichern damit eine Minimalversorgung. \nWeitere Vesikel erhöhen die Lagerkapazität und gleichzeitig auch die Plünderschutzgrenze.',1000,0,0,0,19,0,5,0,0,0,0);
INSERT INTO `genesis_infos` VALUES (18,'konst10','Thymin-Speichervesikel','In den Speichervesikeln werden die Nährstoffe gelagert bis sie verbraucht werden. \nGleichzeitig bieten sie einen Plünderschutz und sichern damit eine Minimalversorgung. \nWeitere Vesikel erhöhen die Lagerkapazität und gleichzeitig auch die Plünderschutzgrenze.',800,500,0,0,19,0,5,0,0,0,0);
INSERT INTO `genesis_infos` VALUES (19,'konst11','Guanin-Speichervesikel','In den Speichervesikeln werden die Nährstoffe gelagert bis sie verbraucht werden.\nGleichzeitig bieten sie einen Plünderschutz und sichern damit eine Minimalversorgung.\nWeitere Vesikel erhöhen die Lagerkapazität und gleichzeitig auch die Plünderschutzgrenze.',700,500,400,0,19,0,5,0,0,0,0);
INSERT INTO `genesis_infos` VALUES (20,'konst12','Cytosin-Speichervesikel','In den Speichervesikeln werden die Nährstoffe gelagert bis sie verbraucht werden.\nGleichzeitig bieten sie einen Plünderschutz und sichern damit eine Minimalversorgung.\nWeitere Vesikel erhöhen die Lagerkapazität und gleichzeitig auch die Plünderschutzgrenze.',700,500,0,400,19,0,5,0,0,0,0);
INSERT INTO `genesis_infos` VALUES (21,'konst15','Immunsystem','Das Immunsystem ist die natürliche Verteidigungsanlage des Neogens. \nEine höhere Ausbaustufe erhöht die Schutzwirkung und verringert die Entwicklungsdauer der Antikörper.',0,0,2000,1500,29,0,30,0,0,0,0);
INSERT INTO `genesis_infos` VALUES (27,'prod6','Spionage-Partikel','Keine Zelle im eigentlichen Sinn handelt es sich hierbei um ein winzig kleines, \naber dafür extrem schnelles Partikel, das in der Lage ist, feindliche Neogens zu infiltrieren. \nDort erstellt es einen Lagebericht, und bringt diesen zurück. Nicht immer erfolgreich... ',0,0,500,500,3,5,0,1,1,10,5000);
INSERT INTO `genesis_infos` VALUES (28,'prod4','T-Killerzelle','Ein wahrer Meister des Tötens. Geschickt heften sich die T-Killerzellen an ihren Gegner und \nverabreichen diesen eine ordentliche Ladung von Zellmembran zerfressenden Giften! \nAber damit nicht genug! Durch geschickte Manipulation bringt die T-Killerzelle ihrer Gegner \ndazu Selbstmord zu begehen! Erschreckend... ',30000,10000,3000,3000,130,115,46,4000,1500,360,170);
INSERT INTO `genesis_infos` VALUES (29,'prod3','Plasmazelle','Als B-Zellen patrouillieren sie im Neogen. Aber wehe sie treffen auf eine feindliche Zelle! Schnell wandeln sie sich zu Plasmazellen um und gehen unerbittlich auf die Jagd. \nSie sind in der Lage Antikörper zu produzieren, die blitzschnell den Gegner einhüllen und damit lahmlegen und abtöten. Einen flinkeren Killer sah man nie... \n',5000,12000,0,0,70,80,17,1000,2800,450,200);
INSERT INTO `genesis_infos` VALUES (30,'prod2','Sentinel','Die erste Exo-Zelle des Neogens, die primär der Verteidigung dient. \nDie Sentinels (Dentritic Cells) patroullieren im Körper und vernichten alle eindringenden Fremdzellen. \nDoch auch im Angriff sind sie nicht zu unterschätzen...',3000,4000,3000,2000,50,50,12,800,1000,700,140);
INSERT INTO `genesis_infos` VALUES (31,'prod1','Makrophage','Diese Exo-Zelle hat ständig Hunger. Prima, wenn man sie zum Gegner schickt!',4000,1500,0,0,40,30,6,400,600,500,117);
INSERT INTO `genesis_infos` VALUES (32,'prod5','Determinator','Durch weitreichende Entwicklung und Mutation des Neogens hat es einen völlig neuen Typ von Exo-Zellen erschaffen. \nDurch Verschmelzung anderer Typen von Exo-Zellen entsteht eine wahre Kampfzelle, die hocheffektiv ihre Gegner beseitigt.',40000,50000,20000,25000,200,160,135,6000,7000,240,155);
INSERT INTO `genesis_infos` VALUES (33,'prod7','TranZe','Die TRANsporterZElle ist nicht die stärkste und nicht besonders hübsch. Besonders schlau auch nicht... \nAber das macht nichts. Ihre Aufgabe ist es \n- einmal die Verteidigung feindlicher Neogens überwunden\n- durch Makropinocytose umliegende Nährstoffe aufzunehmen und wegzuschaffen.',3000,2000,2000,2000,45,20,5,10,150,5000,220);
INSERT INTO `genesis_infos` VALUES (34,'konst14','Kleinhirn','Im Kleinhirn werden die Informationen für die verschiedenen Missionen koordiniert. \nDurch höhere Stufen wird eine komplexere Verarbeitung und damit mehrere Missionen parallel möglich. \nVerteidigen ist ab Stufe 3 möglich, gemeinsames Angreifen erst ab Stufe 6.\nBeides ist nur alle 24h möglich.',2000,3000,1500,1500,40,0,20,0,0,0,0);
INSERT INTO `genesis_infos` VALUES (35,'konst16','Sensorneuronen','Das Neogen besitzt an seiner Peripherie abgewandelte Neuronen, \nderen lange berührungssensitive Fortsätze eine Wahrnehmung von Exo-Zellen ermöglichen, \ndie sich dem Neogen nähern. \nHöhere Stufen verlängern die Fortsätze und ermöglichen dadurch eine frühere Meldung. ',600,2100,0,0,34,0,15,0,0,0,0);
INSERT INTO `genesis_infos` VALUES (36,'forsch6','Vesikeltechnik','Durch die Entwicklung immer raffinierterer Methoden der Nährstoff-Speicherung sind die Exo-Zellen in der Lage mehr Nähr- und Baustoffe zu transportieren.',2900,2200,2500,2600,125,0,40,0,0,0,0);
INSERT INTO `genesis_infos` VALUES (37,'forsch7','Baustofflehre','Durch das intensive Beschäftigen mit den Nähr- und Baustoffen findet das Neogen einen Weg Zellen zu produzieren, die den Körper verlassen können. \r\nEbenso ermöglicht diese Lehre höhere Ausbaustufen.',1500,3200,2500,2000,147,0,35,0,0,0,0);
INSERT INTO `genesis_infos` VALUES (38,'vert1','Antikörper 1','Die Antikörper bilden die Grundlage deines Abwehrsystems. \r\nDer Ausbau des Immunsystems beschleunigt die Ausbildung neuer Antikörper. \r\nDurch Erlernung von Mutation und Immunität kannst du die Angriffs- und Verteidigungswerte dieser Zellen erhöhen.',3500,2000,900,900,33,0,7,300,750,0,0);
INSERT INTO `genesis_infos` VALUES (39,'forsch8','Nanobiologie','Mit der Nanobiologie verringerst du die Größe deiner Spionagepartikel. \r\nJe höher du die Nanobiologie erlernst, desto leichter werden sie es haben, \r\nden gegnerischen Partikelfilter zu durchdringen um einen Spionagebericht zu liefern.\r\nDesweiteren wird dadurch auch die eigene Spionageabwehr verstärkt.',2000,1000,2000,3000,164,0,0,0,0,0,0);
INSERT INTO `genesis_infos` VALUES (40,'konst17','Partikelfilter','Der Partikelfilter bildet ein feines Netz zur Filterung von Spionagepartikeln. \r\nJe höher du diesen ausbaust, desto besser werden feindliche Spionagepartikel erkannt und vernichtet.\r\nAußerdem wird auch mehr über die Struktur von Patrikelfiltern bekannt und damit die Erfolgsrate deiner Spionage-Partikel erhöht.',750,550,750,1000,13,0,0,0,0,0,0);
INSERT INTO `genesis_infos` VALUES (41,'konst6','Mitochondrium','Ein Mitochondrium ist das \"Kraftwerk\" der Zelle.\r\nDie Hauptfunktion des Mitochondriums ist es die universelle Energiewährung der Zelle, das ATP herzustellen.\r\nWelches für die Interaktion mit Exo-Zellen zwingend erforderlich ist.',850,850,850,850,11,60,0,0,0,0,0);
INSERT INTO `genesis_infos` VALUES (42,'konst13','ATP-Speichervesikel','In den Speichervesikeln werden die Nährstoffe gelagert bis sie verbraucht werden. \r\nGleichzeitig bieten sie einen Plünderschutz und sichern damit eine Minimalversorgung. \r\nWeitere Vesikel erhöhen die Lagerkapazität und gleichzeitig auch die Plünderschutzgrenze.',700,500,400,400,20,0,0,0,0,0,0);
INSERT INTO `genesis_infos` VALUES (44,'vert2','Antikörper 2','Die Antikörper bilden die Grundlage deines Abwehrsystems. \r\nDer Ausbau des Immunsystems beschleunigt die Ausbildung neuer Antikörper. \r\nDurch Erlernung von Mutation und Immunität kannst du die Angriffs- und Verteidigungswerte dieser Zellen erhöhen.',4000,7000,3000,3000,70,0,17,800,2400,0,0);
INSERT INTO `genesis_infos` VALUES (45,'vert3','Antikörper 3','Die Antikörper bilden die Grundlage deines Abwehrsystems. \r\nDer Ausbau des Immunsystems beschleunigt die Ausbildung neuer Antikörper. \r\nDurch Erlernung von Mutation und Immunität kannst du die Angriffs- und Verteidigungswerte dieser Zellen erhöhen.',15000,15000,20000,20000,120,0,70,3500,9000,0,0);
INSERT INTO `genesis_infos` VALUES (46,'prod8','Keimzelle','Spezialzelle\r\n\r\n- man kann ein neues Neogen \"besiedeln\"',150000,100000,300000,300000,2000,50000,950,0,0,50000,16);

#
# Table structure for table genesis_log
#

CREATE TABLE `genesis_log` (
  `id` int(11) NOT NULL auto_increment,
  `name` char(30) NOT NULL default '',
  `ip` char(15) NOT NULL default '',
  `zeit` int(11) unsigned NOT NULL default '0',
  `aktion` char(200) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

#
# Dumping data for table genesis_log
#


#
# Table structure for table genesis_news
#

CREATE TABLE `genesis_news` (
  `id` int(11) NOT NULL auto_increment,
  `von` mediumint(7) unsigned NOT NULL default '0',
  `an` mediumint(7) unsigned NOT NULL default '0',
  `zeit` int(11) unsigned NOT NULL default '0',
  `typ` enum('alli_news','ereignis','news','bewerb') default NULL,
  `betreff` varchar(100) default NULL,
  `news` text,
  `newsalt` tinyint(1) unsigned NOT NULL default '0',
  `meldung` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `von` (`von`),
  KEY `typ` (`typ`),
  KEY `analt` (`an`,`newsalt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

#
# Dumping data for table genesis_news
#


#
# Table structure for table genesis_politik
#

CREATE TABLE `genesis_politik` (
  `id` int(11) NOT NULL auto_increment,
  `alli1` int(11) unsigned NOT NULL default '0',
  `alli2` int(11) unsigned NOT NULL default '0',
  `typ` int(1) unsigned NOT NULL default '0',
  `von` int(11) unsigned NOT NULL default '0',
  `bis` int(11) unsigned NOT NULL default '0',
  `accept` int(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `alli1` (`alli1`),
  KEY `alli2` (`alli2`),
  KEY `typ` (`typ`),
  KEY `von` (`von`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

#
# Dumping data for table genesis_politik
#


#
# Table structure for table genesis_raenge
#

CREATE TABLE `genesis_raenge` (
  `id` int(11) NOT NULL auto_increment,
  `alli` int(11) unsigned NOT NULL default '0',
  `rang` int(11) unsigned NOT NULL default '0',
  `name` char(30) NOT NULL default 'Mitglied',
  `rechte` char(10) NOT NULL default '0000000000',
  PRIMARY KEY  (`id`),
  KEY `alli` (`alli`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

#
# Dumping data for table genesis_raenge
#

INSERT INTO `genesis_raenge` VALUES (32,12,0,'Gründer','1111111111');
INSERT INTO `genesis_raenge` VALUES (33,12,1,'Mitglied','0000000000');

#
# Table structure for table genesis_secure
#

CREATE TABLE `genesis_secure` (
  `ip` char(15) NOT NULL default '',
  `name` char(30) NOT NULL default '',
  `name2` char(30) default NULL,
  `zeit` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

#
# Dumping data for table genesis_secure
#


#
# Table structure for table genesis_sitter
#

CREATE TABLE `genesis_sitter` (
  `Id` int(11) NOT NULL auto_increment,
  `spieler1` int(11) unsigned NOT NULL default '0',
  `spieler2` int(11) unsigned NOT NULL default '0',
  `zeit` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

#
# Dumping data for table genesis_sitter
#


#
# Table structure for table genesis_spieler
#

CREATE TABLE `genesis_spieler` (
  `id` int(11) NOT NULL auto_increment,
  `login` varchar(30) NOT NULL default '',
  `name` varchar(30) NOT NULL default '',
  `passwort` varchar(32) NOT NULL default '',
  `email` varchar(50) default NULL,
  `email2` varchar(50) default NULL,
  `sessid` varchar(35) default '0',
  `log` int(11) unsigned NOT NULL default '0',
  `profil` text,
  `notiz` text,
  `avatar` varchar(100) default '',
  `style` varchar(200) default '',
  `basis1` varchar(8) NOT NULL default '',
  `basis2` varchar(8) default NULL,
  `alli` int(11) unsigned NOT NULL default '0',
  `alli_rang` int(11) unsigned NOT NULL default '0',
  `punkte` int(10) NOT NULL default '0',
  `punktek` int(10) NOT NULL default '0',
  `punktef` int(10) NOT NULL default '0',
  `punktem` int(10) NOT NULL default '0',
  `punktea` int(10) NOT NULL default '0',
  `punktema` int(10) NOT NULL default '0',
  `forsch1` int(3) unsigned NOT NULL default '0',
  `forsch2` int(3) unsigned NOT NULL default '0',
  `forsch3` int(3) unsigned NOT NULL default '0',
  `forsch4` int(3) unsigned NOT NULL default '0',
  `forsch5` int(3) unsigned NOT NULL default '0',
  `forsch6` int(3) unsigned NOT NULL default '0',
  `forsch7` int(3) unsigned NOT NULL default '0',
  `forsch8` int(3) unsigned NOT NULL default '0',
  `kampfpkt` int(6) unsigned NOT NULL default '0',
  `bonus` int(11) NOT NULL default '0',
  `attzeit` int(11) unsigned NOT NULL default '0',
  `deffzeit` int(11) unsigned NOT NULL default '0',
  `urlaub` int(11) unsigned NOT NULL default '0',
  `inaktivmail` int(11) unsigned NOT NULL default '0',
  `gesperrt` int(11) unsigned NOT NULL default '0',
  `lastnews` int(11) unsigned NOT NULL default '0',
  `lastpost` int(11) unsigned NOT NULL default '0',
  `poll` int(1) unsigned NOT NULL default '0',
  `loesch` int(11) unsigned NOT NULL default '0',
  `special` tinyint(1) unsigned NOT NULL default '1',
  `layout` int(1) unsigned NOT NULL default '0',
  `endmsg` tinyint(1) unsigned NOT NULL default '1',
  `missmsg` tinyint(1) unsigned NOT NULL default '1',
  `shownew` tinyint(1) unsigned NOT NULL default '1',
  `showava` tinyint(1) unsigned NOT NULL default '2',
  `spios` int(4) unsigned NOT NULL default '1',
  `keimzelle` tinyint(1) unsigned NOT NULL default '0',
  `attcount` int(10) NOT NULL default '0',
  `atttime` int(10) unsigned NOT NULL default '0',
  `lastvote` varchar(15) default NULL,
  `nachrichten` smallint(3) unsigned NOT NULL default '0',
  `ereignisse` smallint(3) unsigned NOT NULL default '0',
  `angriffe` int(2) unsigned NOT NULL default '0',
  `deffs` int(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `login` (`login`),
  KEY `name` (`name`),
  KEY `basis1` (`basis1`),
  KEY `alli` (`alli`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

#
# Dumping data for table genesis_spieler
#


#
# Table structure for table genesis_stats
#

CREATE TABLE `genesis_stats` (
  `id` int(11) NOT NULL auto_increment,
  `name` char(30) default NULL,
  `zeit` datetime default NULL,
  `punkte` int(11) unsigned NOT NULL default '0',
  `punktek` int(11) unsigned NOT NULL default '0',
  `punktef` int(11) unsigned NOT NULL default '0',
  `punktem` int(11) unsigned NOT NULL default '0',
  `kampfpkt` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

#
# Dumping data for table genesis_stats
#


#
# Table structure for table genesis_wio
#

CREATE TABLE `genesis_wio` (
  `ip` char(15) NOT NULL default '',
  `zeit` int(11) unsigned NOT NULL default '0',
  `seite` char(100) NOT NULL default '',
  PRIMARY KEY  (`ip`),
  KEY `zeit` (`zeit`),
  KEY `seite` (`seite`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

#
# Dumping data for table genesis_wio
#

#
# Table structure for table mycounter
#

CREATE TABLE `mycounter` (
  `id` int(10) NOT NULL default '0',
  `zeit` int(12) NOT NULL default '0',
  `stunde` int(10) NOT NULL default '0',
  `heute` int(10) NOT NULL default '0',
  `insg` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

#
# Dumping data for table mycounter
#

INSERT INTO `mycounter` VALUES (1,1230510674,1,3,3098);

#
# Table structure for table smilies
#

CREATE TABLE `smilies` (
  `smilies_id` smallint(5) unsigned NOT NULL auto_increment,
  `code` char(50) default NULL,
  `smile_url` char(100) default NULL,
  `emoticon` char(75) default NULL,
  PRIMARY KEY  (`smilies_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

#
# Dumping data for table smilies
#

INSERT INTO `smilies` VALUES (1,':D','icon_biggrin.gif','Very Happy');
INSERT INTO `smilies` VALUES (2,':-D','icon_biggrin.gif','Very Happy');
INSERT INTO `smilies` VALUES (3,':grin:','icon_biggrin.gif','Very Happy');
INSERT INTO `smilies` VALUES (4,':)','icon_smile.gif','Smile');
INSERT INTO `smilies` VALUES (5,':-)','icon_smile.gif','Smile');
INSERT INTO `smilies` VALUES (6,':smile:','icon_smile.gif','Smile');
INSERT INTO `smilies` VALUES (7,':(','icon_sad.gif','Sad');
INSERT INTO `smilies` VALUES (8,':-(','icon_sad.gif','Sad');
INSERT INTO `smilies` VALUES (9,':sad:','icon_sad.gif','Sad');
INSERT INTO `smilies` VALUES (10,':o','icon_surprised.gif','Surprised');
INSERT INTO `smilies` VALUES (11,':-o','icon_surprised.gif','Surprised');
INSERT INTO `smilies` VALUES (12,':eek:','icon_surprised.gif','Surprised');
INSERT INTO `smilies` VALUES (13,':shock:','icon_eek.gif','Shocked');
INSERT INTO `smilies` VALUES (14,':?','icon_confused.gif','Confused');
INSERT INTO `smilies` VALUES (15,':-?','icon_confused.gif','Confused');
INSERT INTO `smilies` VALUES (16,':???:','icon_confused.gif','Confused');
INSERT INTO `smilies` VALUES (17,'8)','icon_cool.gif','Cool');
INSERT INTO `smilies` VALUES (18,'8-)','icon_cool.gif','Cool');
INSERT INTO `smilies` VALUES (19,':cool:','icon_cool.gif','Cool');
INSERT INTO `smilies` VALUES (20,':lol:','icon_lol.gif','Laughing');
INSERT INTO `smilies` VALUES (21,':x','icon_mad.gif','Mad');
INSERT INTO `smilies` VALUES (22,':-x','icon_mad.gif','Mad');
INSERT INTO `smilies` VALUES (23,':mad:','icon_mad.gif','Mad');
INSERT INTO `smilies` VALUES (24,':P','icon_razz.gif','Razz');
INSERT INTO `smilies` VALUES (25,':-P','icon_razz.gif','Razz');
INSERT INTO `smilies` VALUES (26,':razz:','icon_razz.gif','Razz');
INSERT INTO `smilies` VALUES (27,':oops:','icon_redface.gif','Embarassed');
INSERT INTO `smilies` VALUES (28,':cry:','icon_cry.gif','Crying or Very sad');
INSERT INTO `smilies` VALUES (29,':evil:','icon_evil.gif','Evil or Very Mad');
INSERT INTO `smilies` VALUES (30,':twisted:','icon_twisted.gif','Twisted Evil');
INSERT INTO `smilies` VALUES (31,':roll:','icon_rolleyes.gif','Rolling Eyes');
INSERT INTO `smilies` VALUES (32,':wink:','icon_wink.gif','Wink');
INSERT INTO `smilies` VALUES (33,';)','icon_wink.gif','Wink');
INSERT INTO `smilies` VALUES (34,';-)','icon_wink.gif','Wink');
INSERT INTO `smilies` VALUES (35,':!:','icon_exclaim.gif','Exclamation');
INSERT INTO `smilies` VALUES (36,':?:','icon_question.gif','Question');
INSERT INTO `smilies` VALUES (37,':idea:','icon_idea.gif','Idea');
INSERT INTO `smilies` VALUES (38,':arrow:','icon_arrow.gif','Arrow');
INSERT INTO `smilies` VALUES (39,':|','icon_neutral.gif','Neutral');
INSERT INTO `smilies` VALUES (40,':-|','icon_neutral.gif','Neutral');
INSERT INTO `smilies` VALUES (41,':neutral:','icon_neutral.gif','Neutral');
INSERT INTO `smilies` VALUES (42,':mrgreen:','icon_mrgreen.gif','Mr. Green');
INSERT INTO `smilies` VALUES (202,':O','icon_surprised.gif','Surprised');

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
