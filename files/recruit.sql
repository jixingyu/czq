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

/*Table structure for table `app_config` */

DROP TABLE IF EXISTS `app_config`;

CREATE TABLE `app_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(64) NOT NULL DEFAULT '',
  `cf_key` varchar(64) NOT NULL DEFAULT '',
  `cf_value` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_config` (`module`,`cf_key`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `app_config` */

insert  into `app_config`(`id`,`module`,`cf_key`,`cf_value`) values (1,'about','introduction','  握手APP是一款牛逼的APP!\r\n  快来加入!'),(2,'about','phone','12345578'),(3,'about','qq','12345678');

/*Table structure for table `app_token` */

DROP TABLE IF EXISTS `app_token`;

CREATE TABLE `app_token` (
  `user_id` int(11) unsigned NOT NULL,
  `token` varchar(40) NOT NULL,
  `create_time` int(11) unsigned NOT NULL,
  `expires` int(11) unsigned NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `uniq_token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `app_token` */

insert  into `app_token`(`user_id`,`token`,`create_time`,`expires`) values (4,'7f6bec84d4250ea81846a0b752f744dc',1436234669,1438913069),(1,'f0d635b08e0df52ab0d35dc9c195ec0a',1436770635,1439449035);

/*Table structure for table `apply` */

DROP TABLE IF EXISTS `apply`;

CREATE TABLE `apply` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `job_id` int(11) unsigned NOT NULL DEFAULT '0',
  `resume_id` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned DEFAULT '0' COMMENT '0:申请中，1:发出面试，2:面试结束',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `update_time` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_apply` (`user_id`,`job_id`,`resume_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `apply` */

insert  into `apply`(`id`,`user_id`,`job_id`,`resume_id`,`status`,`create_time`,`update_time`) values (3,1,1,1,1,1436515396,1436516061);

/*Table structure for table `company` */

DROP TABLE IF EXISTS `company`;

CREATE TABLE `company` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '公司名',
  `description` text COMMENT '公司介绍',
  `address` varchar(255) DEFAULT '' COMMENT '地址',
  `industry` varchar(255) DEFAULT '' COMMENT '所属行业',
  `number` int(11) unsigned DEFAULT '0' COMMENT '公司人数',
  `create_time` int(11) unsigned DEFAULT '0',
  `update_time` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `company` */

insert  into `company`(`id`,`name`,`description`,`address`,`industry`,`number`,`create_time`,`update_time`) values (1,'公司1','呵呵，我是公司一号','苏州','IT',100,1435569364,1436433619),(2,'公司2','我是公司二号','苏州','家电',200,1436431352,1436433647);

/*Table structure for table `favorite` */

DROP TABLE IF EXISTS `favorite`;

CREATE TABLE `favorite` (
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `job_id` int(11) unsigned NOT NULL DEFAULT '0',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`job_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `favorite` */

insert  into `favorite`(`user_id`,`job_id`,`create_time`) values (1,1,0);

/*Table structure for table `interview` */

DROP TABLE IF EXISTS `interview`;

CREATE TABLE `interview` (
  `apply_id` int(11) unsigned NOT NULL,
  `address` varchar(255) DEFAULT '',
  `interview_time` int(11) unsigned DEFAULT '0',
  `create_time` int(11) unsigned DEFAULT '0',
  `update_time` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`apply_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `interview` */

insert  into `interview`(`apply_id`,`address`,`interview_time`,`create_time`,`update_time`) values (3,'苏州工业园区11',1437172200,1436516061,1436516061);

/*Table structure for table `job` */

DROP TABLE IF EXISTS `job`;

CREATE TABLE `job` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '职位名',
  `degree` varchar(32) DEFAULT '' COMMENT '学历',
  `salary` varchar(16) DEFAULT '' COMMENT '月薪',
  `district` varchar(16) DEFAULT '' COMMENT '地区',
  `company_id` int(11) unsigned DEFAULT '0' COMMENT '公司ID',
  `working_years` varchar(8) DEFAULT '' COMMENT '工作年限',
  `recruit_number` smallint(11) unsigned DEFAULT '0' COMMENT '招聘人数',
  `job_type` varchar(8) DEFAULT '' COMMENT '职位类型',
  `benefit` text COMMENT '福利',
  `requirement` text COMMENT '任职要求',
  `is_deleted` tinyint(1) DEFAULT '0',
  `create_time` int(11) unsigned DEFAULT '0',
  `update_time` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `job` */

insert  into `job`(`id`,`name`,`degree`,`salary`,`district`,`company_id`,`working_years`,`recruit_number`,`job_type`,`benefit`,`requirement`,`is_deleted`,`create_time`,`update_time`) values (1,'职位1','中专','0-2000','平江区',1,'1-2年',2,'全职','[\"\\u7ee9\\u6548\\u5956\\u91d1\",\"\\u9910\\u8865\"]','test',0,1435570742,1436431338),(2,'职位2','本科','5000-10000','全城',2,'1-3年',3,'全职','[\"\\u4e94\\u9669\\u4e00\\u91d1\",\"\\u7ee9\\u6548\\u5956\\u91d1\",\"\\u9910\\u8865\",\"\\u5e26\\u85aa\\u5e74\\u5047\",\"\\u5458\\u5de5\\u65c5\\u6e38\",\"\\u8282\\u65e5\\u798f\\u5229\"]','无',0,1436431383,1436431498),(3,'php','大专','0-2000','全城',1,'不限',1,'全职','[\"\\u4e94\\u9669\\u4e00\\u91d1\",\"\\u7ee9\\u6548\\u5956\\u91d1\",\"\\u9910\\u8865\",\"\\u5e26\\u85aa\\u5e74\\u5047\",\"\\u5458\\u5de5\\u65c5\\u6e38\",\"\\u8282\\u65e5\\u798f\\u5229\"]','php 1年工作经验',0,1436628908,1436628908),(4,'ios','大专','0-2000','全城',1,'不限',2,'全职','','要求吃苦耐劳',0,1436628939,1436628939),(5,'android','大专','0-2000','全城',1,'不限',3,'全职','[\"\\u5458\\u5de5\\u65c5\\u6e38\",\"\\u8282\\u65e5\\u798f\\u5229\"]','加班！',0,1436628965,1436628965),(6,'服务员','不限','0-2000','平江区',1,'1-3年',4,'兼职','','吃苦耐劳\r\n五官端正\r\n测试测试测试测试测试测试\r\n测试测试测试测试测试测试1123\r\n测试测试测试测试测试测试1435354',0,1436750709,1436750709);

/*Table structure for table `member` */

DROP TABLE IF EXISTS `member`;

CREATE TABLE `member` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `real_name` varchar(255) NOT NULL DEFAULT '',
  `mobile` varchar(11) NOT NULL DEFAULT '',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `create_time` int(11) unsigned DEFAULT '0',
  `update_time` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `member` */

insert  into `member`(`user_id`,`email`,`password`,`real_name`,`mobile`,`is_active`,`create_time`,`update_time`) values (1,'ymx_4@163.com','cc1251c21d4bf83d944e3e1b1b58d3fa','小明','13451234123',1,1436504441,1436504441),(7,'445346494@qq.com','5f2cd3608f804586f736df6519dcdcfa','arsene','13341234123',1,1436505835,1436506235);

/*Table structure for table `resume` */

DROP TABLE IF EXISTS `resume`;

CREATE TABLE `resume` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `resume_name` varchar(255) DEFAULT '' COMMENT '简历名称',
  `real_name` varchar(255) NOT NULL DEFAULT '' COMMENT '姓名',
  `gender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别 0:女，1:男',
  `birthday` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '生日',
  `native_place` varchar(16) NOT NULL DEFAULT '' COMMENT '籍贯',
  `political_status` varchar(16) NOT NULL DEFAULT '' COMMENT '政治面貌',
  `working_years` tinyint(1) NOT NULL DEFAULT '0' COMMENT '工作年限',
  `mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '手机',
  `email` varchar(255) NOT NULL DEFAULT '' COMMENT '邮箱',
  `school` varchar(32) NOT NULL DEFAULT '' COMMENT '毕业院校',
  `major` varchar(32) NOT NULL DEFAULT '' COMMENT '专业',
  `evaluation` text COMMENT '自我评价',
  `personal_info_completed` tinyint(1) DEFAULT '0',
  `evaluation_completed` tinyint(1) DEFAULT '0',
  `experience_completed` tinyint(1) DEFAULT '0',
  `create_time` int(11) unsigned DEFAULT '0',
  `update_time` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `resume` */

insert  into `resume`(`id`,`user_id`,`resume_name`,`real_name`,`gender`,`birthday`,`native_place`,`political_status`,`working_years`,`mobile`,`email`,`school`,`major`,`evaluation`,`personal_info_completed`,`evaluation_completed`,`experience_completed`,`create_time`,`update_time`) values (1,1,'未命名简历','',0,0,'','',0,'','','','',NULL,0,0,0,1436234729,1436234729);

/*Table structure for table `work_experience` */

DROP TABLE IF EXISTS `work_experience`;

CREATE TABLE `work_experience` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `resume_id` int(11) unsigned NOT NULL,
  `company` varchar(255) NOT NULL DEFAULT '',
  `start_time` int(11) unsigned DEFAULT '0',
  `end_time` int(11) unsigned DEFAULT '0',
  `description` text,
  `create_time` int(11) unsigned DEFAULT '0',
  `update_time` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `work_experience` */

insert  into `work_experience`(`id`,`resume_id`,`company`,`start_time`,`end_time`,`description`,`create_time`,`update_time`) values (1,1,'公司名',0,0,NULL,0,0);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
