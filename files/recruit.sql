/*
SQLyog v10.2 
MySQL - 5.6.17 : Database - recruit
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`recruit` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `recruit`;

/*Table structure for table `admin_member` */

DROP TABLE IF EXISTS `admin_member`;

CREATE TABLE `admin_member` (
  `uid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `u_username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `admin_member` */

insert  into `admin_member`(`uid`,`username`,`password`) values (1,'admin','2cd20a9777fa019d63dc8e918fa8ee33799992f9');

/*Table structure for table `company` */

DROP TABLE IF EXISTS `company`;

CREATE TABLE `company` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `address` varchar(255) DEFAULT '',
  `industry` varchar(255) DEFAULT '',
  `number` int(11) unsigned DEFAULT '0',
  `create_time` int(11) unsigned DEFAULT '0',
  `update_time` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `company` */

insert  into `company`(`id`,`name`,`description`,`address`,`industry`,`number`,`create_time`,`update_time`) values (1,'公司一号',NULL,'','',0,1435569364,1435569364);

/*Table structure for table `interview` */

DROP TABLE IF EXISTS `interview`;

CREATE TABLE `interview` (
  `user_id` int(11) unsigned NOT NULL,
  `job_id` int(11) unsigned NOT NULL,
  `address` varchar(255) DEFAULT '',
  `interview_time` int(11) unsigned DEFAULT '0',
  `create_time` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`user_id`,`job_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `interview` */

/*Table structure for table `job` */

DROP TABLE IF EXISTS `job`;

CREATE TABLE `job` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `degree` varchar(32) DEFAULT '',
  `salary` varchar(16) DEFAULT '',
  `district` varchar(16) DEFAULT '',
  `company_id` int(11) unsigned DEFAULT '0',
  `experience` varchar(8) DEFAULT '',
  `recuit_number` smallint(11) unsigned DEFAULT '0',
  `type` varchar(8) DEFAULT '',
  `benefit` varchar(100) DEFAULT '',
  `requirement` text,
  `create_time` int(11) unsigned DEFAULT '0',
  `update_time` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `job` */

insert  into `job`(`id`,`name`,`description`,`degree`,`salary`,`district`,`company_id`,`experience`,`recuit_number`,`type`,`benefit`,`requirement`,`create_time`,`update_time`) values (1,'test',NULL,'','','',0,'',0,'','',NULL,1435570742,1435570742);

/*Table structure for table `member` */

DROP TABLE IF EXISTS `member`;

CREATE TABLE `member` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `real_name` varchar(255) NOT NULL DEFAULT '',
  `mobile` varchar(11) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `member` */

/*Table structure for table `resume` */

DROP TABLE IF EXISTS `resume`;

CREATE TABLE `resume` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `real_name` varchar(255) NOT NULL DEFAULT '',
  `gender` tinyint(1) NOT NULL DEFAULT '0',
  `birthday` int(11) unsigned NOT NULL DEFAULT '0',
  `native_place` varchar(16) NOT NULL DEFAULT '',
  `political_status` varchar(16) NOT NULL DEFAULT '',
  `working_years` tinyint(1) NOT NULL DEFAULT '0',
  `mobile` varchar(11) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `school` varchar(32) NOT NULL DEFAULT '',
  `major` varchar(32) NOT NULL DEFAULT '',
  `create_time` int(11) unsigned DEFAULT '0',
  `update_time` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `resume` */

/*Table structure for table `work_experience` */

DROP TABLE IF EXISTS `work_experience`;

CREATE TABLE `work_experience` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `resume_id` int(11) unsigned NOT NULL,
  `company` varchar(255) NOT NULL DEFAULT '',
  `start_time` int(11) unsigned DEFAULT '0',
  `end_time` int(11) unsigned DEFAULT '0',
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `work_experience` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
