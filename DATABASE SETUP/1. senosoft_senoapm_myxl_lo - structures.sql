/*
SQLyog Ultimate v11.33 (64 bit)
MySQL - 10.4.22-MariaDB : Database - senosoft_senoapm_myxl_lo
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`senosoft_senoapm_myxl_lo` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;

USE `senosoft_senoapm_myxl_lo`;

/*Table structure for table `tb_alias` */

DROP TABLE IF EXISTS `tb_alias`;

CREATE TABLE `tb_alias` (
  `controller` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `id` int(11) NOT NULL,
  `alias` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`controller`,`id`),
  KEY `alias` (`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_apm_client` */

DROP TABLE IF EXISTS `tb_apm_client`;

CREATE TABLE `tb_apm_client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `application` varchar(100) COLLATE utf8_unicode_ci DEFAULT '',
  `location_id` int(11) DEFAULT 0,
  `operator_id` int(11) DEFAULT 0,
  `published` tinyint(2) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_apm_client_device` */

DROP TABLE IF EXISTS `tb_apm_client_device`;

CREATE TABLE `tb_apm_client_device` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `apm_client_id` int(11) DEFAULT 0,
  `device_id` int(11) DEFAULT 0,
  `appium_port` int(11) DEFAULT 0,
  `published` tinyint(2) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `apm_client_id` (`apm_client_id`),
  KEY `device_id` (`device_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_device` */

DROP TABLE IF EXISTS `tb_device`;

CREATE TABLE `tb_device` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone_number` varchar(20) COLLATE utf8_unicode_ci DEFAULT '',
  `device_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT '',
  `udid` varchar(100) COLLATE utf8_unicode_ci DEFAULT '',
  `platform_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `platform_version` varchar(10) COLLATE utf8_unicode_ci DEFAULT '',
  `application` varchar(10) COLLATE utf8_unicode_ci DEFAULT '',
  `remarks` varchar(500) COLLATE utf8_unicode_ci DEFAULT '',
  `location_id` int(11) DEFAULT 0,
  `operator_id` int(11) DEFAULT 0,
  `published` tinyint(2) DEFAULT 0,
  `status` tinyint(2) DEFAULT 0,
  `status_final` tinyint(2) DEFAULT 0,
  `status_time` datetime DEFAULT NULL,
  `app_version` varchar(10) COLLATE utf8_unicode_ci DEFAULT '',
  `params` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `status_run` tinyint(2) DEFAULT 0,
  `status_run_final` tinyint(2) DEFAULT 0,
  `status_run_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `location_id` (`location_id`),
  KEY `operator_id` (`operator_id`),
  KEY `id` (`id`),
  KEY `phone_number` (`phone_number`),
  KEY `application` (`application`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_device_log` */

DROP TABLE IF EXISTS `tb_device_log`;

CREATE TABLE `tb_device_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `device_id` int(11) DEFAULT NULL,
  `log_date` date DEFAULT NULL,
  `status` tinyint(2) DEFAULT 0,
  `status_start` datetime DEFAULT NULL,
  `status_end` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `device_id` (`device_id`),
  KEY `log_date` (`log_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_device_notif` */

DROP TABLE IF EXISTS `tb_device_notif`;

CREATE TABLE `tb_device_notif` (
  `device_id` int(11) NOT NULL,
  `status` tinyint(2) DEFAULT 0,
  `status_time` datetime DEFAULT NULL,
  `notified` tinyint(2) DEFAULT 0,
  PRIMARY KEY (`device_id`),
  KEY `notified` (`notified`),
  KEY `status` (`status`),
  KEY `status_time` (`status_time`),
  KEY `device_id` (`device_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_downtime` */

DROP TABLE IF EXISTS `tb_downtime`;

CREATE TABLE `tb_downtime` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `type` tinyint(2) DEFAULT 0,
  `start_datetime` datetime DEFAULT NULL,
  `end_datetime` datetime DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `remarks` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `published` tinyint(2) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `datetime` (`end_datetime`,`start_datetime`),
  KEY `published` (`published`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_error` */

DROP TABLE IF EXISTS `tb_error`;

CREATE TABLE `tb_error` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `device_id` int(11) DEFAULT 0,
  `type` tinyint(2) DEFAULT 0,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `description` varchar(500) COLLATE utf8_unicode_ci DEFAULT '',
  `level` tinyint(2) DEFAULT 0,
  `monitor_journey_detail_id` bigint(20) DEFAULT 0,
  `journey_detail_id` int(11) DEFAULT 0,
  `repeat_no` int(11) DEFAULT 0,
  `has_screenshot` tinyint(2) DEFAULT 0,
  `scheduled` tinyint(2) DEFAULT 0,
  `status` tinyint(2) DEFAULT 0,
  `status_email` tinyint(2) DEFAULT 0,
  `status_wa` tinyint(2) DEFAULT 0,
  `error_date` date DEFAULT NULL,
  `error_datetime` datetime DEFAULT NULL,
  `recover_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `monitor_journey_detail_id` (`monitor_journey_detail_id`),
  KEY `status_email` (`status_email`),
  KEY `status_wa` (`status_wa`),
  KEY `error_date` (`error_date`),
  KEY `device_id` (`device_id`,`journey_detail_id`,`repeat_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_journey` */

DROP TABLE IF EXISTS `tb_journey`;

CREATE TABLE `tb_journey` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `activity_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `start_timer` varchar(100) COLLATE utf8_unicode_ci DEFAULT '',
  `application` varchar(10) COLLATE utf8_unicode_ci DEFAULT '',
  `type` varchar(20) COLLATE utf8_unicode_ci DEFAULT '',
  `platform` varchar(20) COLLATE utf8_unicode_ci DEFAULT '',
  `condition` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `published` tinyint(2) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_journey_detail` */

DROP TABLE IF EXISTS `tb_journey_detail`;

CREATE TABLE `tb_journey_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `journey_id` int(11) DEFAULT 0,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `platform` varchar(20) COLLATE utf8_unicode_ci DEFAULT '',
  `condition` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `published` tinyint(2) DEFAULT 1,
  `ordering` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `journey_id` (`journey_id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_journey_detail_cat` */

DROP TABLE IF EXISTS `tb_journey_detail_cat`;

CREATE TABLE `tb_journey_detail_cat` (
  `journey_detail_id` int(11) NOT NULL,
  `cat_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`journey_detail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_journey_detail_percentile` */

DROP TABLE IF EXISTS `tb_journey_detail_percentile`;

CREATE TABLE `tb_journey_detail_percentile` (
  `journey_detail_id` int(11) NOT NULL,
  `nineth_percentile` double DEFAULT NULL,
  PRIMARY KEY (`journey_detail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_journey_detail_task` */

DROP TABLE IF EXISTS `tb_journey_detail_task`;

CREATE TABLE `tb_journey_detail_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `journey_detail_id` int(11) DEFAULT 0,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `type` varchar(20) COLLATE utf8_unicode_ci DEFAULT '',
  `find_by` varchar(20) COLLATE utf8_unicode_ci DEFAULT '',
  `element_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT '',
  `content` varchar(1000) COLLATE utf8_unicode_ci DEFAULT '',
  `content_ios` varchar(1000) COLLATE utf8_unicode_ci DEFAULT '',
  `handler` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `timeout` varchar(10) COLLATE utf8_unicode_ci DEFAULT '',
  `action` varchar(20) COLLATE utf8_unicode_ci DEFAULT '',
  `wait` varchar(10) COLLATE utf8_unicode_ci DEFAULT '',
  `input` varchar(100) COLLATE utf8_unicode_ci DEFAULT '',
  `start_timer` varchar(100) COLLATE utf8_unicode_ci DEFAULT '',
  `start_timer_when` varchar(20) COLLATE utf8_unicode_ci DEFAULT '',
  `end_timer` varchar(100) COLLATE utf8_unicode_ci DEFAULT '',
  `end_timer_when` varchar(20) COLLATE utf8_unicode_ci DEFAULT '',
  `record_param` tinyint(2) DEFAULT 0,
  `record_param_when` varchar(20) COLLATE utf8_unicode_ci DEFAULT '',
  `upload` tinyint(2) DEFAULT 0,
  `upload_data` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `upload_when` varchar(20) COLLATE utf8_unicode_ci DEFAULT '',
  `platform` varchar(20) COLLATE utf8_unicode_ci DEFAULT '',
  `condition` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `published` tinyint(2) DEFAULT 1,
  `ordering` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `journey_id` (`journey_detail_id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=427 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_list_cat` */

DROP TABLE IF EXISTS `tb_list_cat`;

CREATE TABLE `tb_list_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `tag` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `type` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `tag` (`tag`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_list_item` */

DROP TABLE IF EXISTS `tb_list_item`;

CREATE TABLE `tb_list_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `list_cat_id` int(11) DEFAULT NULL,
  `text` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `short` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `val` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `val_min` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `val_max` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `icon` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `class` varchar(10) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `list_cat_id` (`list_cat_id`,`val`),
  KEY `id` (`id`),
  KEY `val_min_max` (`list_cat_id`,`val_min`,`val_max`)
) ENGINE=InnoDB AUTO_INCREMENT=157 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_local_setting` */

DROP TABLE IF EXISTS `tb_local_setting`;

CREATE TABLE `tb_local_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `value` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `unit` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_location` */

DROP TABLE IF EXISTS `tb_location`;

CREATE TABLE `tb_location` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `published` tinyint(2) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_log_update` */

DROP TABLE IF EXISTS `tb_log_update`;

CREATE TABLE `tb_log_update` (
  `id` bigint(20) NOT NULL,
  `device_id` int(11) DEFAULT 0,
  `table_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT '',
  `record_id` bigint(20) DEFAULT 0,
  `action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `log_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `device_id` (`device_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_main_config` */

DROP TABLE IF EXISTS `tb_main_config`;

CREATE TABLE `tb_main_config` (
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_menu` */

DROP TABLE IF EXISTS `tb_menu`;

CREATE TABLE `tb_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `alias` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `usertype` int(11) DEFAULT 3,
  `published` tinyint(2) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_menu_item` */

DROP TABLE IF EXISTS `tb_menu_item`;

CREATE TABLE `tb_menu_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `alias` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `icon` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `link` varchar(500) COLLATE utf8_unicode_ci DEFAULT '',
  `base_controller` varchar(500) COLLATE utf8_unicode_ci DEFAULT '',
  `ordering` int(11) DEFAULT 0,
  `usertype` int(11) DEFAULT 3,
  `published` tinyint(2) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_monitor_journey` */

DROP TABLE IF EXISTS `tb_monitor_journey`;

CREATE TABLE `tb_monitor_journey` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `device_id` int(11) DEFAULT 0,
  `journey_id` int(11) DEFAULT 0,
  `location_lat` double DEFAULT 0,
  `location_lng` double DEFAULT 0,
  `monitor_date` date DEFAULT NULL,
  `monitor_datetime` datetime DEFAULT NULL,
  `upload_datetime` datetime DEFAULT NULL,
  `upload_status` tinyint(2) DEFAULT 0,
  `upload_id` bigint(20) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `device_id` (`device_id`),
  KEY `monitor_date` (`monitor_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_monitor_journey_detail` */

DROP TABLE IF EXISTS `tb_monitor_journey_detail`;

CREATE TABLE `tb_monitor_journey_detail` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `device_id` int(11) DEFAULT 0,
  `monitor_journey_id` bigint(20) DEFAULT 0,
  `journey_detail_id` int(11) DEFAULT 0,
  `network_type` varchar(10) COLLATE utf8_unicode_ci DEFAULT '',
  `cellid` int(11) DEFAULT 0,
  `signal_level` int(11) DEFAULT 0,
  `signal_quality` int(11) DEFAULT 0,
  `ber` int(11) DEFAULT 0,
  `response_time` double DEFAULT NULL COMMENT 'seconds',
  `latency` double DEFAULT NULL,
  `packet_loss` int(11) DEFAULT NULL,
  `status` tinyint(2) DEFAULT 1,
  `message` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `has_screenshot` tinyint(2) DEFAULT 0,
  `monitor_date` date DEFAULT NULL,
  `monitor_datetime` datetime DEFAULT NULL,
  `repeat_no` int(11) DEFAULT 0,
  `scheduled` tinyint(2) DEFAULT 0,
  `upload_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `journey_detail_id` (`journey_detail_id`),
  KEY `monitor_journey_id` (`monitor_journey_id`),
  KEY `monitor_date` (`monitor_date`),
  KEY `device_id` (`device_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_monitor_journey_detail_failed` */

DROP TABLE IF EXISTS `tb_monitor_journey_detail_failed`;

CREATE TABLE `tb_monitor_journey_detail_failed` (
  `id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_monitor_journey_detail_new` */

DROP TABLE IF EXISTS `tb_monitor_journey_detail_new`;

CREATE TABLE `tb_monitor_journey_detail_new` (
  `id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_monitor_journey_nvt` */

DROP TABLE IF EXISTS `tb_monitor_journey_nvt`;

CREATE TABLE `tb_monitor_journey_nvt` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `device_id` int(11) DEFAULT 0,
  `monitor_journey_id` bigint(20) DEFAULT 0,
  `network_type` varchar(10) COLLATE utf8_unicode_ci DEFAULT '',
  `cellid` int(11) DEFAULT 0,
  `signal_level` int(11) DEFAULT 0,
  `signal_quality` int(11) DEFAULT 0,
  `ber` int(11) DEFAULT 0,
  `response_time` double DEFAULT NULL COMMENT 'seconds',
  `latency` double DEFAULT NULL,
  `packet_loss` int(11) DEFAULT NULL,
  `status` tinyint(2) DEFAULT 1,
  `message` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `monitor_date` date DEFAULT NULL,
  `monitor_datetime` datetime DEFAULT NULL,
  `repeat_no` int(11) DEFAULT 0,
  `upload_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `monitor_journey_id` (`monitor_journey_id`),
  KEY `device_id` (`device_id`),
  KEY `monitor_date` (`monitor_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_notif` */

DROP TABLE IF EXISTS `tb_notif`;

CREATE TABLE `tb_notif` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sekolah_id` int(11) DEFAULT 0,
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  `subtitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  `body` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  `target` tinyint(2) DEFAULT 0 COMMENT '0 = single, 1 = multi',
  `user_id` int(11) DEFAULT 0,
  `scope` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  `need_action` tinyint(2) DEFAULT 0,
  `followed_up` tinyint(2) DEFAULT 0,
  `notif_date` datetime DEFAULT NULL,
  `mode` tinyint(2) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

/*Table structure for table `tb_notif_pool` */

DROP TABLE IF EXISTS `tb_notif_pool`;

CREATE TABLE `tb_notif_pool` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `send_as` varchar(10) CHARACTER SET utf8 DEFAULT '',
  `recipient` varchar(100) CHARACTER SET utf8 DEFAULT '',
  `subject` varchar(255) CHARACTER SET utf8 DEFAULT '',
  `body` text CHARACTER SET utf8 DEFAULT NULL,
  `status` tinyint(2) DEFAULT 0,
  `date_sent` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `recipient` (`recipient`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

/*Table structure for table `tb_notif_user` */

DROP TABLE IF EXISTS `tb_notif_user`;

CREATE TABLE `tb_notif_user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `notif_id` int(11) DEFAULT 0,
  `user_id` int(11) DEFAULT 0,
  `scope` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '' COMMENT 'back = Back End, front = Front End',
  `need_action` tinyint(2) DEFAULT 0,
  `followed_up` tinyint(2) DEFAULT 0,
  `notif_date` datetime DEFAULT NULL,
  `mode` tinyint(2) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

/*Table structure for table `tb_operator` */

DROP TABLE IF EXISTS `tb_operator`;

CREATE TABLE `tb_operator` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `published` tinyint(2) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_report_daily` */

DROP TABLE IF EXISTS `tb_report_daily`;

CREATE TABLE `tb_report_daily` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `device_id` int(11) DEFAULT 0,
  `report_date` date DEFAULT NULL,
  `create_datetime` datetime DEFAULT NULL,
  `status` tinyint(2) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_report_daily_detail` */

DROP TABLE IF EXISTS `tb_report_daily_detail`;

CREATE TABLE `tb_report_daily_detail` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `report_daily_id` bigint(20) DEFAULT 0,
  `journey_id` int(11) DEFAULT 0,
  `filename` varchar(500) COLLATE utf8_unicode_ci DEFAULT '',
  `create_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_report_date` */

DROP TABLE IF EXISTS `tb_report_date`;

CREATE TABLE `tb_report_date` (
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_report_error` */

DROP TABLE IF EXISTS `tb_report_error`;

CREATE TABLE `tb_report_error` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `device_id` int(11) DEFAULT 0,
  `type` tinyint(2) DEFAULT 0,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `description` varchar(500) COLLATE utf8_unicode_ci DEFAULT '',
  `level` tinyint(2) DEFAULT 0,
  `monitor_journey_detail_id` bigint(20) DEFAULT 0,
  `journey_detail_id` int(11) DEFAULT 0,
  `repeat_no` int(11) DEFAULT 0,
  `has_screenshot` tinyint(2) DEFAULT 0,
  `scheduled` tinyint(2) DEFAULT 0,
  `status` tinyint(2) DEFAULT 0,
  `status_email` tinyint(2) DEFAULT 0,
  `status_wa` tinyint(2) DEFAULT 0,
  `error_date` date DEFAULT NULL,
  `error_datetime` datetime DEFAULT NULL,
  `recover_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `monitor_journey_detail_id` (`monitor_journey_detail_id`),
  KEY `status_email` (`status_email`),
  KEY `status_wa` (`status_wa`),
  KEY `error_date` (`error_date`),
  KEY `device_id` (`device_id`,`journey_detail_id`,`repeat_no`),
  KEY `journey_detail_id` (`journey_detail_id`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_report_monitor_journey` */

DROP TABLE IF EXISTS `tb_report_monitor_journey`;

CREATE TABLE `tb_report_monitor_journey` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `device_id` int(11) DEFAULT 0,
  `journey_id` int(11) DEFAULT 0,
  `location_lat` double DEFAULT 0,
  `location_lng` double DEFAULT 0,
  `monitor_date` date DEFAULT NULL,
  `monitor_datetime` datetime DEFAULT NULL,
  `upload_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `device_id` (`device_id`),
  KEY `monitor_date` (`monitor_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_report_monitor_journey_detail` */

DROP TABLE IF EXISTS `tb_report_monitor_journey_detail`;

CREATE TABLE `tb_report_monitor_journey_detail` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `device_id` int(11) DEFAULT 0,
  `monitor_journey_id` bigint(20) DEFAULT 0,
  `journey_detail_id` int(11) DEFAULT 0,
  `network_type` varchar(10) COLLATE utf8_unicode_ci DEFAULT '',
  `cellid` int(11) DEFAULT 0,
  `signal_level` int(11) DEFAULT 0,
  `signal_quality` int(11) DEFAULT 0,
  `ber` int(11) DEFAULT 0,
  `response_time` double DEFAULT NULL COMMENT 'seconds',
  `latency` double DEFAULT NULL,
  `packet_loss` int(11) DEFAULT NULL,
  `status` tinyint(2) DEFAULT 1,
  `message` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `has_screenshot` tinyint(2) DEFAULT 0,
  `monitor_date` date DEFAULT NULL,
  `monitor_datetime` datetime DEFAULT NULL,
  `repeat_no` int(11) DEFAULT 0,
  `scheduled` tinyint(2) DEFAULT 0,
  `upload_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `journey_detail_id` (`journey_detail_id`),
  KEY `monitor_journey_id` (`monitor_journey_id`),
  KEY `monitor_date` (`monitor_date`),
  KEY `device_id` (`device_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_report_monitor_journey_nvt` */

DROP TABLE IF EXISTS `tb_report_monitor_journey_nvt`;

CREATE TABLE `tb_report_monitor_journey_nvt` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `device_id` int(11) DEFAULT 0,
  `monitor_journey_id` bigint(20) DEFAULT 0,
  `network_type` varchar(10) COLLATE utf8_unicode_ci DEFAULT '',
  `cellid` int(11) DEFAULT 0,
  `signal_level` int(11) DEFAULT 0,
  `signal_quality` int(11) DEFAULT 0,
  `ber` int(11) DEFAULT 0,
  `response_time` double DEFAULT NULL COMMENT 'seconds',
  `latency` double DEFAULT NULL,
  `packet_loss` int(11) DEFAULT NULL,
  `status` tinyint(2) DEFAULT 1,
  `message` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `monitor_date` date DEFAULT NULL,
  `monitor_datetime` datetime DEFAULT NULL,
  `repeat_no` int(11) DEFAULT 0,
  `upload_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `monitor_journey_id` (`monitor_journey_id`),
  KEY `monitor_date` (`monitor_date`),
  KEY `device_id` (`device_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_sessions` */

DROP TABLE IF EXISTS `tb_sessions`;

CREATE TABLE `tb_sessions` (
  `session_id` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `ip_address` varchar(45) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `user_agent` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT 0,
  `user_data` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_setting` */

DROP TABLE IF EXISTS `tb_setting`;

CREATE TABLE `tb_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `value` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `unit` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_test` */

DROP TABLE IF EXISTS `tb_test`;

CREATE TABLE `tb_test` (
  `last_dt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_tpl_email` */

DROP TABLE IF EXISTS `tb_tpl_email`;

CREATE TABLE `tb_tpl_email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tag` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `body` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_tpl_message` */

DROP TABLE IF EXISTS `tb_tpl_message`;

CREATE TABLE `tb_tpl_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) CHARACTER SET utf8 NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `body` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

/*Table structure for table `tb_trigger` */

DROP TABLE IF EXISTS `tb_trigger`;

CREATE TABLE `tb_trigger` (
  `id` bigint(20) NOT NULL,
  `location_id` int(11) DEFAULT 0,
  `operator_id` int(11) DEFAULT 0,
  `cat_id` int(11) DEFAULT 0,
  `journey_id` int(11) DEFAULT 0,
  `status` tinyint(2) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_trigger_device` */

DROP TABLE IF EXISTS `tb_trigger_device`;

CREATE TABLE `tb_trigger_device` (
  `id` bigint(20) NOT NULL,
  `device_id` int(11) DEFAULT 0,
  `status` tinyint(2) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_trigger_journey` */

DROP TABLE IF EXISTS `tb_trigger_journey`;

CREATE TABLE `tb_trigger_journey` (
  `id` bigint(20) NOT NULL,
  `monitor_journey_detail_id` bigint(20) DEFAULT NULL,
  `journey_detail_id` int(11) DEFAULT 0,
  `location_id` int(11) DEFAULT 0,
  `operator_id` int(11) DEFAULT 0,
  `status` tinyint(2) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_trigger_journey_copy` */

DROP TABLE IF EXISTS `tb_trigger_journey_copy`;

CREATE TABLE `tb_trigger_journey_copy` (
  `id` bigint(20) NOT NULL,
  `monitor_journey_detail_id` bigint(20) DEFAULT NULL,
  `journey_detail_id` int(11) DEFAULT 0,
  `location_id` int(11) DEFAULT 0,
  `operator_id` int(11) DEFAULT 0,
  `status` tinyint(2) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_trigger_rawdata` */

DROP TABLE IF EXISTS `tb_trigger_rawdata`;

CREATE TABLE `tb_trigger_rawdata` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `device_id` int(11) DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `location_name` varchar(255) DEFAULT NULL,
  `operator_name` varchar(255) DEFAULT NULL,
  `journey_name` varchar(255) DEFAULT NULL,
  `journey_detail_name` varchar(255) DEFAULT NULL,
  `location_lat` double DEFAULT NULL,
  `location_lng` double DEFAULT NULL,
  `cellid` int(11) DEFAULT NULL,
  `network_type` varchar(10) DEFAULT NULL,
  `signal_level` int(11) DEFAULT NULL,
  `response_time` double DEFAULT NULL,
  `monitor_datetime` datetime DEFAULT NULL,
  `status` tinyint(2) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `scheduled_downtime` tinyint(2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `device_id` (`device_id`),
  KEY `location_name` (`location_name`),
  KEY `operator_name` (`operator_name`),
  KEY `journey_name` (`journey_name`),
  KEY `status` (`status`),
  KEY `monitor_datetime` (`monitor_datetime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `tb_user` */

DROP TABLE IF EXISTS `tb_user`;

CREATE TABLE `tb_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) COLLATE utf8_unicode_ci DEFAULT '',
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT '',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT '',
  `whatsapp` varchar(20) COLLATE utf8_unicode_ci DEFAULT '',
  `address` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `usertype` int(11) DEFAULT 0,
  `userrole` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `status` tinyint(2) DEFAULT 0,
  `picture` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fb_uid` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `g_uid` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `registerDate` datetime DEFAULT NULL,
  `lastvisitDate` datetime DEFAULT NULL,
  `scode` varchar(20) COLLATE utf8_unicode_ci DEFAULT '',
  `need_refresh` tinyint(2) DEFAULT 0,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `idx_name` (`name`),
  FULLTEXT KEY `email` (`email`),
  FULLTEXT KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_userrole` */

DROP TABLE IF EXISTS `tb_userrole`;

CREATE TABLE `tb_userrole` (
  `id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `tag` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `tb_usertype` */

DROP TABLE IF EXISTS `tb_usertype`;

CREATE TABLE `tb_usertype` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `template` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `menu` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `published` tinyint(2) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/* Trigger structure for table `tb_device_notif` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `tg_device_notif_insert_before` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'127.0.0.1' */ /*!50003 TRIGGER `tg_device_notif_insert_before` BEFORE INSERT ON `tb_device_notif` FOR EACH ROW BEGIn
	set new.status_time = now();
    END */$$


DELIMITER ;

/* Trigger structure for table `tb_device_notif` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `tg_device_notif_update_before` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'127.0.0.1' */ /*!50003 TRIGGER `tg_device_notif_update_before` BEFORE UPDATE ON `tb_device_notif` FOR EACH ROW BEGIn
	if not old.status and TIME_TO_SEC(TIMEDIFF(now(), old.status_time)) < (60*10) and NOT old.notified and new.status then
		set new.notified = 1;
	elseIF NOT old.status AND old.notified AND new.status THEN
		SET new.notified = 0;
	elseif old.status and not new.status then
		set new.status_time = now(), new.notified = 0;
	end if;
    END */$$


DELIMITER ;

/* Trigger structure for table `tb_error` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `tg_error_insert_before` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'127.0.0.1' */ /*!50003 TRIGGER `tg_error_insert_before` BEFORE INSERT ON `tb_error` FOR EACH ROW BEGIN
		set new.error_date = date(new.error_datetime);
    END */$$


DELIMITER ;

/* Trigger structure for table `tb_monitor_journey` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `tg_monitor_journey_insert` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'127.0.0.1' */ /*!50003 TRIGGER `tg_monitor_journey_insert` AFTER INSERT ON `tb_monitor_journey` FOR EACH ROW BEGIN
		
		insert into `tb_log_update`
			values (fn_new_log_update_id(), new.device_id, 'tb_monitor_journey', new.id, 'insert', now());
    		
		
		UPDATE 	`tb_device`
		SET 	STATUS = 1
		WHERE 	id = new.device_id;
		
		UPDATE tb_user SET `need_refresh` = '1';
    END */$$


DELIMITER ;

/* Trigger structure for table `tb_monitor_journey` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `tg_monitor_journey_delete` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'127.0.0.1' */ /*!50003 TRIGGER `tg_monitor_journey_delete` AFTER DELETE ON `tb_monitor_journey` FOR EACH ROW BEGIN
		delete from `tb_monitor_journey_detail` where `monitor_journey_id` = old.id;
		DELETE FROM `tb_monitor_journey_nvt` WHERE `monitor_journey_id` = old.id;
    END */$$


DELIMITER ;

/* Trigger structure for table `tb_monitor_journey_detail` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `tg_monitor_journey_detail_insert` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'127.0.0.1' */ /*!50003 TRIGGER `tg_monitor_journey_detail_insert` AFTER INSERT ON `tb_monitor_journey_detail` FOR EACH ROW BEGIN
		
		DECLARE var_device_id INT DEFAULT 0;
		DECLARE var_location_id INT;
		DECLARE var_operator_id INT;
		
		declare ada_error int default 0;
		declare var_id bigint default 0;
		DECLARE var_id_check BIGINT DEFAULT 0;
		
		DECLARE var_show_alert INT DEFAULT 0;
		
		if new.status = 0 then
			
			SELECT	a.`device_id`, b.`location_id`, b.`operator_id`
			INTO 	var_device_id, var_location_id, var_operator_id
			FROM 	`tb_monitor_journey` AS a
				INNER JOIN `tb_device` AS b ON a.`device_id` = b.`id`
			WHERE	a.`id` = new.monitor_journey_id;
			
			SELECT	count(*)
			INTO 	ada_error
			FROM 	tb_error AS a
			WHERE	NOT a.status AND a.device_id = var_device_id
				AND a.`journey_detail_id` = new.journey_detail_id;
				
			if ada_error > 0 then
				
				SELECT	a.id
				INTO 	var_id
				FROM 	tb_error AS a
				WHERE	NOT a.status AND a.device_id = var_device_id
					AND a.`journey_detail_id` = new.journey_detail_id
					AND a.repeat_no = 1
				ORDER 	BY a.id ASC
				LIMIT	0,1;
				
				set var_id = ifnull(var_id, 0);
				
				IF var_id > 0 THEN
				
					SELECT	a.id
					INTO 	var_id_check
					FROM 	tb_error AS a
					WHERE	NOT a.status AND a.device_id = var_device_id
						AND a.`journey_detail_id` = new.journey_detail_id
						AND a.repeat_no = 3
					ORDER 	BY a.id ASC
					LIMIT	0,1;
					
				END IF;
				
				UPDATE 	tb_error
				SET	status = 1, `recover_datetime` = new.`monitor_datetime`
				WHERE	NOT `status` AND device_id = var_device_id
						AND `journey_detail_id` = new.journey_detail_id;
					
				
					INSERT INTO `tb_trigger_journey`
					(id, monitor_journey_detail_id, journey_detail_id, `location_id`, operator_id, `status`)
					VALUES
					(fn_new_trigger_journey_id(), new.id, new.journey_detail_id, var_location_id, var_operator_id, 1);
				
				
				SET var_id_check = IFNULL(var_id_check, 0);
				
				if var_id > 0 and var_id_check > 0 THEN
					SET 	var_show_alert = DATEDIFF(new.monitor_datetime, NOW());
					if var_show_alert > -1 then
						UPDATE	tb_error SET status_email=status_email-1, status_wa=status_wa-1 WHERE id = var_id;
					end if;
				END IF;
			end if;
					
		end if;
		
		insert into `tb_monitor_journey_detail_new` values (new.id);
		
		
		INSERT INTO `tb_log_update`
			VALUES (fn_new_log_update_id(), new.device_id, 'tb_monitor_journey_detail', new.id, 'insert', NOW());
    END */$$


DELIMITER ;

/* Trigger structure for table `tb_monitor_journey_detail` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `tg_monitor_journey_detail_update` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'127.0.0.1' */ /*!50003 TRIGGER `tg_monitor_journey_detail_update` AFTER UPDATE ON `tb_monitor_journey_detail` FOR EACH ROW BEGIN
    
		DECLARE var_device_id INT DEFAULT 0;
		DECLARE var_location_id INT;
		DECLARE var_operator_id INT;
		
		DECLARE var_id BIGINT DEFAULT 0;
		declare count_id_check bigint;
		
		declare var_show_alert int default 0;
		
		if new.status > 0 then
		
			SELECT	a.`device_id`, b.`location_id`, b.`operator_id`
			into 	var_device_id, var_location_id, var_operator_id
			FROM 	`tb_monitor_journey` AS a
				INNER JOIN `tb_device` AS b ON a.`device_id` = b.`id`
			WHERE	a.`id` = new.monitor_journey_id;
			
			insert into tb_error
				(`device_id`, `type`, title, `description`, `level`, monitor_journey_detail_id, journey_detail_id, repeat_no, has_screenshot, status_email, status_wa, `error_datetime`)
				values
				(var_device_id, new.`status`, 
				IF(new.status=3, 'Wrong PIN', IF(new.status=2, 'Wrong Page', 'General Error')),
				new.`message`,
				IF(new.status=3, 2, 1),
				new.id, new.journey_detail_id,  new.repeat_no, new.has_screenshot, IF(new.repeat_no=3,1,1), if(new.repeat_no=3,1,1), new.`monitor_datetime`);
			
			
				INSERT INTO `tb_trigger_journey`
				(id, monitor_journey_detail_id, journey_detail_id, `location_id`, operator_id, `status`)
				VALUES
				(fn_new_trigger_journey_id(), new.id, new.journey_detail_id, var_location_id, var_operator_id, 0);
			
				
			if new.repeat_no=3 then
			
				SELECT	a.id
				INTO 	var_id
				FROM 	tb_error AS a
				WHERE	NOT a.status AND a.device_id = var_device_id
						AND a.`journey_detail_id` = new.journey_detail_id
						AND a.repeat_no = 1
				ORDER 	BY id asc
				LIMIT	0,1;
				
				IF var_id > 0 THEN
					
					
					
					SELECT	COUNT(a.id)
					INTO 	count_id_check
					FROM 	tb_error AS a
					WHERE	NOT a.status AND a.device_id = var_device_id
							AND a.`journey_detail_id` = new.journey_detail_id;
							
					SET 	var_show_alert = DATEDIFF(new.monitor_datetime, NOW());
					
					IF var_show_alert > -1 THEN
						IF count_id_check<=3 THEN
							UPDATE	tb_error SET status_wa=status_wa-1, status_email=status_email-1 WHERE id = var_id;
						ELSE
							UPDATE	tb_error SET status_wa=status_wa-1 WHERE id = var_id;
						END IF;
					END IF;
					
				END IF;
				
			END IF;
		
		END IF;
    
    END */$$


DELIMITER ;

/* Trigger structure for table `tb_monitor_journey_detail` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `tg_monitor_journey_detail_delete` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'127.0.0.1' */ /*!50003 TRIGGER `tg_monitor_journey_detail_delete` AFTER DELETE ON `tb_monitor_journey_detail` FOR EACH ROW BEGIN
		delete 	from `tb_error`
		where	`monitor_journey_detail_id` = old.id;
    END */$$


DELIMITER ;

/* Trigger structure for table `tb_monitor_journey_nvt` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `tg_monitor_journey_nvt_insert` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'127.0.0.1' */ /*!50003 TRIGGER `tg_monitor_journey_nvt_insert` AFTER INSERT ON `tb_monitor_journey_nvt` FOR EACH ROW BEGIN
	
	INSERT INTO `tb_log_update`
		VALUES (fn_new_log_update_id(), new.device_id, 'tb_monitor_journey_nvt', new.id, 'insert', NOW());
    END */$$


DELIMITER ;

/* Trigger structure for table `tb_notif` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `tg_notif_insert` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'127.0.0.1' */ /*!50003 TRIGGER `tg_notif_insert` AFTER INSERT ON `tb_notif` FOR EACH ROW BEGIN
	DECLARE done BOOLEAN DEFAULT FALSE;
	DECLARE var_user_id INT(11);
	DECLARE cur1 CURSOR FOR SELECT 	a.user_id
							FROM 	tb_sekolah_user AS a
									INNER JOIN `tb_user` AS b ON a.`user_id` = b.`id`
							WHERE 	a.sekolah_id = new.sekolah_id AND b.`usertype` = 3 AND a.`status`=1;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
	if new.mode = 0 then
		if new.target = 1 then
			OPEN cur1;
			read_loop: LOOP
				FETCH cur1 INTO var_user_id;
				IF done THEN
					LEAVE read_loop;
				END IF;
				INSERT INTO tb_notif_user (notif_id, user_id, scope, need_action, followed_up, notif_date, `mode`)
					VALUES (new.id, var_user_id, new.scope, new.need_action, new.followed_up, NOW(), 1);
			END LOOP;
			CLOSE cur1;
		else
			INSERT INTO tb_notif_user (notif_id, user_id, scope, need_action, followed_up, notif_date, `mode`)
					VALUES (new.id, new.user_id, new.scope, new.need_action, new.followed_up, NOW(), 1);
		end if;
	end if;
    END */$$


DELIMITER ;

/* Trigger structure for table `tb_notif_user` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `tg_notif_user_update` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'127.0.0.1' */ /*!50003 TRIGGER `tg_notif_user_update` AFTER UPDATE ON `tb_notif_user` FOR EACH ROW BEGIN
		IF new.mode = 0 THEN
			if new.need_action=1 and old.followed_up = 0 and new.followed_up = 1 then
				update tb_notif set followed_up = 1, `mode` = 1 where id = new.notif_id;
			end if;
		end if;
    END */$$


DELIMITER ;

/* Trigger structure for table `tb_report_monitor_journey` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `tg_report_monitor_journey_delete` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'127.0.0.1' */ /*!50003 TRIGGER `tg_report_monitor_journey_delete` AFTER DELETE ON `tb_report_monitor_journey` FOR EACH ROW BEGIN
	delete from `tb_report_monitor_journey_detail` where `monitor_journey_id` = old.id;
	DELETE FROM `tb_report_monitor_journey_nvt` WHERE `monitor_journey_id` = old.id;
    END */$$


DELIMITER ;

/* Trigger structure for table `tb_report_monitor_journey_detail` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `tg_report_monitor_journey_detail_delete` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'127.0.0.1' */ /*!50003 TRIGGER `tg_report_monitor_journey_detail_delete` AFTER DELETE ON `tb_report_monitor_journey_detail` FOR EACH ROW BEGIN
	DELETE FROM `tb_report_error` WHERE `monitor_journey_detail_id` = old.id;
    END */$$


DELIMITER ;

/* Function  structure for function  `fc_category_level` */

/*!50003 DROP FUNCTION IF EXISTS `fc_category_level` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fc_category_level`(category_id INT(11)) RETURNS int(11)
BEGIN
	DECLARE var_return INT(11) DEFAULT 1;
	DECLARE var_parent_id INT(11);
	loop1:LOOP
		SET var_parent_id = (SELECT parent_id FROM tb_category WHERE id = category_id);
		IF var_parent_id <> 0 THEN
			SET var_return = var_return + 1;
			SET category_id = var_parent_id;
			ITERATE loop1;
		ELSE
			LEAVE loop1;
		END IF;
	END LOOP loop1;
	RETURN var_return;
    END */$$
DELIMITER ;

/* Function  structure for function  `fc_category_ordering` */

/*!50003 DROP FUNCTION IF EXISTS `fc_category_ordering` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fc_category_ordering`(category_id INT(11)) RETURNS char(255) CHARSET utf8
BEGIN
	DECLARE var_return CHAR(255) DEFAULT '';
	DECLARE var_parent_id INT(11);
	DECLARE length_ordering INT(11) DEFAULT 1;
	SET length_ordering = (SELECT LENGTH(CAST(MAX(ordering) AS CHAR(11))) FROM tb_category);
	loop1:LOOP
		SET var_return = (SELECT CONCAT(LPAD(CAST(ordering AS CHAR(11)),length_ordering,'0'),var_return) FROM tb_category WHERE id = category_id);
		SET var_parent_id = (SELECT parent_id FROM tb_category WHERE id = category_id);
		IF var_parent_id <> 0 THEN
			SET category_id = var_parent_id;
			ITERATE loop1;
		ELSE
			LEAVE loop1;
		END IF;
	END LOOP loop1;
	RETURN var_return;
    END */$$
DELIMITER ;

/* Function  structure for function  `fc_count_apm_client_device` */

/*!50003 DROP FUNCTION IF EXISTS `fc_count_apm_client_device` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fc_count_apm_client_device`(var_apm_client_id INT(11)) RETURNS int(11)
BEGIN
	DECLARE var_return INT(11) DEFAULT 0;
	SET var_return = (SELECT COUNT(*) FROM tb_apm_client_device WHERE apm_client_id = var_apm_client_id);
	RETURN var_return;
    END */$$
DELIMITER ;

/* Function  structure for function  `fc_count_apm_client_log_update` */

/*!50003 DROP FUNCTION IF EXISTS `fc_count_apm_client_log_update` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fc_count_apm_client_log_update`(var_apm_client_id INT(11)) RETURNS int(11)
BEGIN
	DECLARE var_return INT(11) DEFAULT 0;
	SET var_return = (SELECT COUNT(*) FROM `tb_log_update` WHERE apm_client_id = var_apm_client_id);
	RETURN var_return;
    END */$$
DELIMITER ;

/* Function  structure for function  `fc_count_article_image` */

/*!50003 DROP FUNCTION IF EXISTS `fc_count_article_image` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fc_count_article_image`(var_article_id INT(11)) RETURNS int(11)
BEGIN
	DECLARE var_return INT(11) DEFAULT 0;
	SET var_return = (SELECT COUNT(*) FROM tb_article_image WHERE article_id = var_article_id);
	RETURN var_return;
END */$$
DELIMITER ;

/* Function  structure for function  `fc_count_article_widget` */

/*!50003 DROP FUNCTION IF EXISTS `fc_count_article_widget` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fc_count_article_widget`(var_article_id INT(11)) RETURNS int(11)
BEGIN
	DECLARE var_return INT(11) DEFAULT 0;
	SET var_return = (SELECT COUNT(*) FROM tb_article_widget WHERE article_id = var_article_id);
	RETURN var_return;
END */$$
DELIMITER ;

/* Function  structure for function  `fc_count_category_child` */

/*!50003 DROP FUNCTION IF EXISTS `fc_count_category_child` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fc_count_category_child`(var_category_id INT(11)) RETURNS int(11)
BEGIN
	DECLARE var_return INT(11) DEFAULT 0;
	SET var_return = (select count(*) from tb_category where parent_id = var_category_id and published='1');
	RETURN var_return;
END */$$
DELIMITER ;

/* Function  structure for function  `fc_count_device` */

/*!50003 DROP FUNCTION IF EXISTS `fc_count_device` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fc_count_device`(var_location_id INT(11)) RETURNS int(11)
BEGIN
	DECLARE var_return INT(11) DEFAULT 0;
	SET var_return = (select count(*) from tb_device where location_id = var_location_id);
	RETURN var_return;
    END */$$
DELIMITER ;

/* Function  structure for function  `fc_count_journey_detail` */

/*!50003 DROP FUNCTION IF EXISTS `fc_count_journey_detail` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fc_count_journey_detail`(var_journey_id INT(11)) RETURNS int(11)
BEGIN
	DECLARE var_return INT(11) DEFAULT 0;
	SET var_return = (select count(*) from `tb_journey_detail` where journey_id = var_journey_id);
	RETURN var_return;
    END */$$
DELIMITER ;

/* Function  structure for function  `fc_count_journey_detail_task` */

/*!50003 DROP FUNCTION IF EXISTS `fc_count_journey_detail_task` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fc_count_journey_detail_task`(var_parent_id INT(11)) RETURNS int(11)
BEGIN
	DECLARE var_return INT(11) DEFAULT 0;
	SET var_return = (select count(*) from `tb_journey_detail_task` where journey_detail_id = var_parent_id);
	RETURN var_return;
    END */$$
DELIMITER ;

/* Function  structure for function  `fc_count_list_item` */

/*!50003 DROP FUNCTION IF EXISTS `fc_count_list_item` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fc_count_list_item`(var_list_cat_id INT(11)) RETURNS int(11)
BEGIN
	DECLARE var_return INT(11) DEFAULT 0;
	SET var_return = (select count(*) from tb_list_item where list_cat_id = var_list_cat_id);
	RETURN var_return;
    END */$$
DELIMITER ;

/* Function  structure for function  `fc_count_menu_item` */

/*!50003 DROP FUNCTION IF EXISTS `fc_count_menu_item` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fc_count_menu_item`(var_menu_id INT(11)) RETURNS int(11)
BEGIN
	DECLARE var_return INT(11) DEFAULT 0;
	SET var_return = (select count(*) from tb_menu_item where var_menu_id = menu_id);
	RETURN var_return;
    END */$$
DELIMITER ;

/* Function  structure for function  `fc_count_menu_item_child` */

/*!50003 DROP FUNCTION IF EXISTS `fc_count_menu_item_child` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fc_count_menu_item_child`(var_menu_item_id INT(11)) RETURNS int(11)
BEGIN
	DECLARE var_return INT(11) DEFAULT 0;
	SET var_return = (select count(*) from tb_menu_item where parent_id = var_menu_item_id and published='1');
	RETURN var_return;
    END */$$
DELIMITER ;

/* Function  structure for function  `fc_count_properti_harga` */

/*!50003 DROP FUNCTION IF EXISTS `fc_count_properti_harga` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fc_count_properti_harga`(var_properti_id INT(11)) RETURNS int(11)
BEGIN
	DECLARE var_return INT(11) DEFAULT 0;
	SET var_return = (SELECT COUNT(*) FROM tb_properti_harga WHERE properti_id = var_properti_id);
	RETURN var_return;
END */$$
DELIMITER ;

/* Function  structure for function  `fc_count_properti_image` */

/*!50003 DROP FUNCTION IF EXISTS `fc_count_properti_image` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fc_count_properti_image`(var_properti_id INT(11)) RETURNS int(11)
BEGIN
	DECLARE var_return INT(11) DEFAULT 0;
	SET var_return = (SELECT COUNT(*) FROM tb_properti_image WHERE properti_id = var_properti_id);
	RETURN var_return;
END */$$
DELIMITER ;

/* Function  structure for function  `fc_count_properti_tenor` */

/*!50003 DROP FUNCTION IF EXISTS `fc_count_properti_tenor` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fc_count_properti_tenor`(var_properti_id INT(11)) RETURNS int(11)
BEGIN
	DECLARE var_return INT(11) DEFAULT 0;
	SET var_return = (SELECT COUNT(*) FROM tb_properti_tenor WHERE properti_id = var_properti_id);
	RETURN var_return;
END */$$
DELIMITER ;

/* Function  structure for function  `fc_get_after` */

/*!50003 DROP FUNCTION IF EXISTS `fc_get_after` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fc_get_after`(var_str TEXT, var_del TEXT, var_index INT(4)) RETURNS text CHARSET utf8
BEGIN
	DECLARE var_return TEXT DEFAULT '';
	DECLARE var_temp TEXT DEFAULT '';
	DECLARE var_pos INT(4);
	SET var_temp = var_str;
	loop1:LOOP
		SET var_pos = LOCATE(var_del, var_temp);
		IF var_pos > 0 THEN
			SET var_temp = RIGHT(var_temp,LENGTH(var_temp)-(var_pos+LENGTH(var_del)-1));
			SET var_index = var_index-1;
			IF var_index = 0 THEN
				SET var_return = var_temp;
				LEAVE loop1;
			ELSE
				ITERATE loop1;
			END IF;
		ELSE
			SET var_return = '';
			LEAVE loop1;
		END IF;
	END LOOP loop1;
	RETURN var_return;
    END */$$
DELIMITER ;

/* Function  structure for function  `fc_get_before` */

/*!50003 DROP FUNCTION IF EXISTS `fc_get_before` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fc_get_before`(var_str TEXT, var_del TEXT) RETURNS text CHARSET utf8
BEGIN
	DECLARE var_return TEXT DEFAULT '';
	DECLARE var_pos INT(4);
	SET var_pos = LOCATE(var_del,var_str);
	IF var_pos > 0 THEN
		SET var_return = LEFT(var_str,var_pos-1);
	ELSE
		SET var_return = var_str;
	END IF;
	RETURN var_return;
END */$$
DELIMITER ;

/* Function  structure for function  `fc_journey_detail_task_upload` */

/*!50003 DROP FUNCTION IF EXISTS `fc_journey_detail_task_upload` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fc_journey_detail_task_upload`(var_parent_id INT(11)) RETURNS int(11)
BEGIN
	DECLARE var_return INT(11) DEFAULT 0;
	SET var_return = (select if(count(*),1,0) from `tb_journey_detail_task` where journey_detail_id = var_parent_id and upload);
	RETURN var_return;
    END */$$
DELIMITER ;

/* Function  structure for function  `fc_menu_item_level` */

/*!50003 DROP FUNCTION IF EXISTS `fc_menu_item_level` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fc_menu_item_level`(menu_item_id INT(11)) RETURNS int(50)
BEGIN
	DECLARE var_return INT(50) DEFAULT 1;
	DECLARE var_parent_id INT(11);
	loop1:LOOP
		SET var_parent_id = (SELECT parent_id FROM tb_menu_item WHERE id = menu_item_id);
		IF var_parent_id <> 0 THEN
			SET var_return = var_return + 1;
			SET menu_item_id = var_parent_id;
			ITERATE loop1;
		ELSE
			LEAVE loop1;
		END IF;
	END LOOP loop1;
	RETURN var_return;
    END */$$
DELIMITER ;

/* Function  structure for function  `fc_menu_item_ordering` */

/*!50003 DROP FUNCTION IF EXISTS `fc_menu_item_ordering` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fc_menu_item_ordering`(menu_item_id INT(11)) RETURNS char(255) CHARSET utf8
BEGIN
	DECLARE var_return CHAR(255) DEFAULT '';
	DECLARE var_parent_id INT(11);
	DECLARE length_ordering INT(11) DEFAULT 1;
	SET length_ordering = (SELECT LENGTH(CAST(MAX(ordering) AS CHAR(11))) FROM tb_menu_item);
	loop1:LOOP
		SET var_return = (SELECT CONCAT(LPAD(CAST(ordering AS CHAR(11)),length_ordering,'0'),var_return) FROM tb_menu_item WHERE id = menu_item_id);
		SET var_parent_id = (SELECT parent_id FROM tb_menu_item WHERE id = menu_item_id);
		IF var_parent_id <> 0 THEN
			SET menu_item_id = var_parent_id;
			ITERATE loop1;
		ELSE
			LEAVE loop1;
		END IF;
	END LOOP loop1;
	RETURN var_return;
    END */$$
DELIMITER ;

/* Function  structure for function  `fc_test` */

/*!50003 DROP FUNCTION IF EXISTS `fc_test` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fc_test`(var_amount double) RETURNS double
BEGIN
	DECLARE var_return double DEFAULT 0;
	DECLARE var_code float DEFAULT 0;
	SET var_code = FLOOR(RAND()*999)+1;
	WHILE (SELECT COUNT(*) FROM tb_campaign_donation WHERE status = '0' and RIGHT(amount,3) = LPAD(CAST(var_code AS CHAR(3)),3,'0')) > 0 DO
		SET var_code = FLOOR(RAND()*999)+1;
	END WHILE;
	set var_return = var_amount + var_code;
	
	RETURN var_return;
END */$$
DELIMITER ;

/* Function  structure for function  `fn_create_device_id` */

/*!50003 DROP FUNCTION IF EXISTS `fn_create_device_id` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_create_device_id`(var_id BIGINT(20), var_phone_number VARCHAR(20), var_location_id INT(11), var_operator_id INT(11)) RETURNS varchar(255) CHARSET utf8 COLLATE utf8_unicode_ci
BEGIN
	IF var_id = 0 THEN
		INSERT INTO `tb_device` (phone_number, location_id, `operator_id`) VALUES (var_phone_number, var_location_id, var_operator_id);
		SET var_id = (SELECT MAX(id) FROM tb_device);
	END IF;
	RETURN (SELECT 	CONCAT(a.id,'|',a.phone_number,'|',b.`id`,'|',b.name,'|',c.`id`,'|',c.name)
		FROM 	`tb_device` AS a
			INNER JOIN `tb_location` AS b ON a.`location_id` = b.`id`
			INNER JOIN `tb_operator` AS c ON a.`operator_id` = c.`id`
		WHERE	a.`id` = var_id);
END */$$
DELIMITER ;

/* Function  structure for function  `fn_get_device_application` */

/*!50003 DROP FUNCTION IF EXISTS `fn_get_device_application` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_get_device_application`(var_id INT(11)) RETURNS char(255) CHARSET utf8
BEGIN
	DECLARE var_return CHAR(255) DEFAULT '';
	SET var_return = (
						SELECT	application
						FROM 	`tb_device`
						WHERE	`id` = var_id
					);
	RETURN IFNULL(var_return,'');
    END */$$
DELIMITER ;

/* Function  structure for function  `fn_get_journey_detail_name` */

/*!50003 DROP FUNCTION IF EXISTS `fn_get_journey_detail_name` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_get_journey_detail_name`(var_id INT(11)) RETURNS char(255) CHARSET utf8
BEGIN
	DECLARE var_return CHAR(255) DEFAULT '';
	SET var_return = (SELECT name from `tb_journey_detail` where id = var_id limit 0,1);
	RETURN IFNULL(var_return,'');
    END */$$
DELIMITER ;

/* Function  structure for function  `fn_get_journey_name` */

/*!50003 DROP FUNCTION IF EXISTS `fn_get_journey_name` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_get_journey_name`(var_id INT(11)) RETURNS char(255) CHARSET utf8
BEGIN
	DECLARE var_return CHAR(255) DEFAULT '';
	SET var_return = (SELECT name from `tb_journey` where id = var_id limit 0,1);
	RETURN IFNULL(var_return,'');
    END */$$
DELIMITER ;

/* Function  structure for function  `fn_get_list_item_class` */

/*!50003 DROP FUNCTION IF EXISTS `fn_get_list_item_class` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_get_list_item_class`(var_tag CHAR(255), var_val CHAR(255)) RETURNS char(255) CHARSET utf8
BEGIN
	DECLARE var_return CHAR(255) DEFAULT '';
	SET var_return = (SELECT a.class
		FROM tb_list_item AS a INNER JOIN tb_list_cat AS b ON a.list_cat_id = b.id
		WHERE b.tag = var_tag COLLATE utf8_unicode_ci AND a.val = var_val LIMIT 0,1);
	RETURN IFNULL(var_return,'');
    END */$$
DELIMITER ;

/* Function  structure for function  `fn_get_list_item_icon` */

/*!50003 DROP FUNCTION IF EXISTS `fn_get_list_item_icon` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_get_list_item_icon`(var_tag CHAR(255), var_val CHAR(255)) RETURNS char(255) CHARSET utf8
BEGIN
	DECLARE var_return CHAR(255) DEFAULT '';
	SET var_return = (SELECT a.icon
		FROM tb_list_item AS a INNER JOIN tb_list_cat AS b ON a.list_cat_id = b.id
		WHERE b.tag = var_tag COLLATE utf8_unicode_ci AND a.val = var_val LIMIT 0,1);
	RETURN IFNULL(var_return,'');
    END */$$
DELIMITER ;

/* Function  structure for function  `fn_get_list_item_max` */

/*!50003 DROP FUNCTION IF EXISTS `fn_get_list_item_max` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_get_list_item_max`(var_tag CHAR(255), var_val CHAR(255)) RETURNS char(255) CHARSET utf8
BEGIN
	DECLARE var_return CHAR(255) DEFAULT '';
	SET var_return = (SELECT a.val_max
		FROM tb_list_item AS a INNER JOIN tb_list_cat AS b ON a.list_cat_id = b.id
		WHERE b.tag = var_tag COLLATE utf8_unicode_ci AND a.val = var_val LIMIT 0,1);
	RETURN IFNULL(var_return,'');
    END */$$
DELIMITER ;

/* Function  structure for function  `fn_get_list_item_min` */

/*!50003 DROP FUNCTION IF EXISTS `fn_get_list_item_min` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_get_list_item_min`(var_tag CHAR(255), var_val CHAR(255)) RETURNS char(255) CHARSET utf8
BEGIN
	DECLARE var_return CHAR(255) DEFAULT '';
	SET var_return = (SELECT a.val_min
		FROM tb_list_item AS a INNER JOIN tb_list_cat AS b ON a.list_cat_id = b.id
		WHERE b.tag = var_tag COLLATE utf8_unicode_ci AND a.val = var_val LIMIT 0,1);
	RETURN IFNULL(var_return,'');
    END */$$
DELIMITER ;

/* Function  structure for function  `fn_get_list_item_range_class` */

/*!50003 DROP FUNCTION IF EXISTS `fn_get_list_item_range_class` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_get_list_item_range_class`(var_tag CHAR(255), var_val CHAR(255)) RETURNS char(255) CHARSET utf8
BEGIN
	DECLARE var_return CHAR(255) DEFAULT '';
	SET var_return = (SELECT 	a.`class`
					FROM 	tb_list_item AS a INNER JOIN tb_list_cat AS b ON a.list_cat_id = b.id
					WHERE 	b.tag = var_tag COLLATE utf8_unicode_ci
							AND ((var_val + 0.0) BETWEEN (a.`val_min` + 0.0) AND (a.`val_max` + 0.0))
					LIMIT 	0,1);
	RETURN IFNULL(var_return,'');
    END */$$
DELIMITER ;

/* Function  structure for function  `fn_get_list_item_short` */

/*!50003 DROP FUNCTION IF EXISTS `fn_get_list_item_short` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_get_list_item_short`(var_tag CHAR(255), var_val CHAR(255)) RETURNS char(255) CHARSET utf8
BEGIN
	DECLARE var_return CHAR(255) DEFAULT '';
	SET var_return = (SELECT a.short
		FROM tb_list_item AS a INNER JOIN tb_list_cat AS b ON a.list_cat_id = b.id
		WHERE b.tag = var_tag COLLATE utf8_unicode_ci AND a.val = var_val LIMIT 0,1);
	RETURN IFNULL(var_return,'');
    END */$$
DELIMITER ;

/* Function  structure for function  `fn_get_list_item_text` */

/*!50003 DROP FUNCTION IF EXISTS `fn_get_list_item_text` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_get_list_item_text`(var_tag CHAR(255), var_val CHAR(255)) RETURNS char(255) CHARSET utf8
BEGIN
	DECLARE var_return CHAR(255) DEFAULT '';
	SET var_return = (SELECT a.text
		FROM tb_list_item AS a INNER JOIN tb_list_cat AS b ON a.list_cat_id = b.id
		WHERE b.tag = var_tag COLLATE utf8_unicode_ci AND a.val = var_val LIMIT 0,1);
	RETURN IFNULL(var_return,'');
    END */$$
DELIMITER ;

/* Function  structure for function  `fn_get_location_id_by_device_id` */

/*!50003 DROP FUNCTION IF EXISTS `fn_get_location_id_by_device_id` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_get_location_id_by_device_id`(var_id INT(11)) RETURNS int(11)
BEGIN
	DECLARE var_return int(11) DEFAULT 0;
	SET var_return = (
						SELECT	location_id
						FROM 	`tb_device`
						WHERE	`id` = var_id
					);
	RETURN IFNULL(var_return,0);
    END */$$
DELIMITER ;

/* Function  structure for function  `fn_get_location_name` */

/*!50003 DROP FUNCTION IF EXISTS `fn_get_location_name` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_get_location_name`(var_id INT(11)) RETURNS char(255) CHARSET utf8
BEGIN
	DECLARE var_return CHAR(255) DEFAULT '';
	SET var_return = (SELECT name from `tb_location` where id = var_id limit 0,1);
	RETURN IFNULL(var_return,'');
    END */$$
DELIMITER ;

/* Function  structure for function  `fn_get_location_name_by_device_id` */

/*!50003 DROP FUNCTION IF EXISTS `fn_get_location_name_by_device_id` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_get_location_name_by_device_id`(var_id INT(11)) RETURNS char(255) CHARSET utf8
BEGIN
	DECLARE var_return CHAR(255) DEFAULT '';
	SET var_return = (
						SELECT	b.`name`
						FROM 	`tb_device` AS a
								INNER JOIN `tb_location` AS b ON a.`location_id` = b.`id`
						WHERE	a.`id` = var_id
					);
	RETURN IFNULL(var_return,'');
    END */$$
DELIMITER ;

/* Function  structure for function  `fn_get_monitor_journey_last_detail` */

/*!50003 DROP FUNCTION IF EXISTS `fn_get_monitor_journey_last_detail` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_get_monitor_journey_last_detail`(var_monitor_journey_id BIGINT(20)) RETURNS int(11)
BEGIN
	DECLARE var_return INT(11);
	SET var_return = (SELECT `journey_detail_id` FROM `tb_monitor_journey_detail`
		WHERE id = (SELECT MAX(id) FROM `tb_monitor_journey_detail` WHERE `monitor_journey_id` = var_monitor_journey_id));
	RETURN var_return;
    END */$$
DELIMITER ;

/* Function  structure for function  `fn_get_monitor_journey_nvt_count` */

/*!50003 DROP FUNCTION IF EXISTS `fn_get_monitor_journey_nvt_count` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_get_monitor_journey_nvt_count`(var_monitor_journey_id bigINT(20)) RETURNS int(11)
BEGIN
	DECLARE var_return INT(11);
	SET var_return = (SELECT count(*) from `tb_monitor_journey_nvt` where monitor_journey_id = var_monitor_journey_id);
	RETURN var_return;
    END */$$
DELIMITER ;

/* Function  structure for function  `fn_get_monitor_journey_nvt_latency` */

/*!50003 DROP FUNCTION IF EXISTS `fn_get_monitor_journey_nvt_latency` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_get_monitor_journey_nvt_latency`(var_monitor_journey_id BIGINT(20)) RETURNS double
BEGIN
	DECLARE var_return DOUBLE;
	SET var_return = (SELECT AVG(`latency`) FROM `tb_monitor_journey_nvt` WHERE monitor_journey_id = var_monitor_journey_id);
	RETURN var_return;
    END */$$
DELIMITER ;

/* Function  structure for function  `fn_get_monitor_journey_nvt_packet_loss` */

/*!50003 DROP FUNCTION IF EXISTS `fn_get_monitor_journey_nvt_packet_loss` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_get_monitor_journey_nvt_packet_loss`(var_monitor_journey_id BIGINT(20)) RETURNS double
BEGIN
	DECLARE var_return DOUBLE;
	SET var_return = (SELECT AVG(`packet_loss`) FROM `tb_monitor_journey_nvt` WHERE monitor_journey_id = var_monitor_journey_id and packet_loss > -1);
	RETURN var_return;
    END */$$
DELIMITER ;

/* Function  structure for function  `fn_get_monitor_journey_nvt_response_time` */

/*!50003 DROP FUNCTION IF EXISTS `fn_get_monitor_journey_nvt_response_time` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_get_monitor_journey_nvt_response_time`(var_monitor_journey_id bigINT(20)) RETURNS double
BEGIN
	DECLARE var_return DOUBLE;
	SET var_return = (SELECT avg(`response_time`) from `tb_monitor_journey_nvt` where monitor_journey_id = var_monitor_journey_id);
	RETURN var_return;
    END */$$
DELIMITER ;

/* Function  structure for function  `fn_get_monitor_journey_nvt_signal_level` */

/*!50003 DROP FUNCTION IF EXISTS `fn_get_monitor_journey_nvt_signal_level` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_get_monitor_journey_nvt_signal_level`(var_monitor_journey_id bigINT(20)) RETURNS double
BEGIN
	DECLARE var_return DOUBLE;
	SET var_return = (SELECT avg(`signal_level`) from `tb_monitor_journey_nvt` where monitor_journey_id = var_monitor_journey_id);
	RETURN var_return;
    END */$$
DELIMITER ;

/* Function  structure for function  `fn_get_monitor_journey_nvt_signal_quality` */

/*!50003 DROP FUNCTION IF EXISTS `fn_get_monitor_journey_nvt_signal_quality` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_get_monitor_journey_nvt_signal_quality`(var_monitor_journey_id bigINT(20)) RETURNS double
BEGIN
	DECLARE var_return DOUBLE;
	SET var_return = (SELECT avg(`signal_quality`) from `tb_monitor_journey_nvt` where monitor_journey_id = var_monitor_journey_id);
	RETURN var_return;
    END */$$
DELIMITER ;

/* Function  structure for function  `fn_get_monitor_journey_nvt_success` */

/*!50003 DROP FUNCTION IF EXISTS `fn_get_monitor_journey_nvt_success` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_get_monitor_journey_nvt_success`(var_monitor_journey_id bigINT(20)) RETURNS int(11)
BEGIN
	DECLARE var_return INT(11);
	SET var_return = (SELECT count(*) from `tb_monitor_journey_nvt` where monitor_journey_id = var_monitor_journey_id and not status);
	RETURN var_return;
    END */$$
DELIMITER ;

/* Function  structure for function  `fn_get_monjo_lodev_id` */

/*!50003 DROP FUNCTION IF EXISTS `fn_get_monjo_lodev_id` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_get_monjo_lodev_id`(var_id bigint(20)) RETURNS int(11)
BEGIN
	RETURN (SELECT	device_id
			FROM 	`tb_monitor_journey`
			WHERE	id = var_id);
END */$$
DELIMITER ;

/* Function  structure for function  `fn_get_operator_id_by_device_id` */

/*!50003 DROP FUNCTION IF EXISTS `fn_get_operator_id_by_device_id` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_get_operator_id_by_device_id`(var_id INT(11)) RETURNS int(11)
BEGIN
	DECLARE var_return int(11) DEFAULT 0;
	SET var_return = (
						SELECT	operator_id
						FROM 	`tb_device`
						WHERE	`id` = var_id
					);
	RETURN IFNULL(var_return,0);
    END */$$
DELIMITER ;

/* Function  structure for function  `fn_get_operator_name` */

/*!50003 DROP FUNCTION IF EXISTS `fn_get_operator_name` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_get_operator_name`(var_id INT(11)) RETURNS char(255) CHARSET utf8
BEGIN
	DECLARE var_return CHAR(255) DEFAULT '';
	SET var_return = (SELECT name from `tb_operator` where id = var_id limit 0,1);
	RETURN IFNULL(var_return,'');
    END */$$
DELIMITER ;

/* Function  structure for function  `fn_get_operator_name_by_device_id` */

/*!50003 DROP FUNCTION IF EXISTS `fn_get_operator_name_by_device_id` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_get_operator_name_by_device_id`(var_id INT(11)) RETURNS char(255) CHARSET utf8
BEGIN
	DECLARE var_return CHAR(255) DEFAULT '';
	SET var_return = (
						SELECT	b.`name`
						FROM 	`tb_device` AS a
								INNER JOIN `tb_operator` AS b ON a.`operator_id` = b.`id`
						WHERE	a.`id` = var_id
					);
	RETURN IFNULL(var_return,'');
    END */$$
DELIMITER ;

/* Function  structure for function  `fn_get_report_monitor_journey_nvt_count` */

/*!50003 DROP FUNCTION IF EXISTS `fn_get_report_monitor_journey_nvt_count` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_get_report_monitor_journey_nvt_count`(var_monitor_journey_id bigINT(20)) RETURNS int(11)
BEGIN
	DECLARE var_return INT(11);
	SET var_return = (SELECT count(*) from `tb_report_monitor_journey_nvt` where monitor_journey_id = var_monitor_journey_id);
	RETURN var_return;
    END */$$
DELIMITER ;

/* Function  structure for function  `fn_get_report_monitor_journey_nvt_latency` */

/*!50003 DROP FUNCTION IF EXISTS `fn_get_report_monitor_journey_nvt_latency` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_get_report_monitor_journey_nvt_latency`(var_monitor_journey_id BIGINT(20)) RETURNS double
BEGIN
	DECLARE var_return DOUBLE;
	SET var_return = (SELECT AVG(`latency`) FROM `tb_report_monitor_journey_nvt` WHERE monitor_journey_id = var_monitor_journey_id and latency > -1);
	RETURN var_return;
    END */$$
DELIMITER ;

/* Function  structure for function  `fn_get_report_monitor_journey_nvt_packet_loss` */

/*!50003 DROP FUNCTION IF EXISTS `fn_get_report_monitor_journey_nvt_packet_loss` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_get_report_monitor_journey_nvt_packet_loss`(var_monitor_journey_id BIGINT(20)) RETURNS double
BEGIN
	DECLARE var_return DOUBLE;
	SET var_return = (SELECT AVG(`packet_loss`) FROM `tb_report_monitor_journey_nvt` WHERE monitor_journey_id = var_monitor_journey_id);
	RETURN var_return;
    END */$$
DELIMITER ;

/* Function  structure for function  `fn_get_report_monitor_journey_nvt_response_time` */

/*!50003 DROP FUNCTION IF EXISTS `fn_get_report_monitor_journey_nvt_response_time` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_get_report_monitor_journey_nvt_response_time`(var_monitor_journey_id bigINT(20)) RETURNS double
BEGIN
	DECLARE var_return DOUBLE;
	SET var_return = (SELECT avg(`response_time`) from `tb_report_monitor_journey_nvt` where monitor_journey_id = var_monitor_journey_id and response_time > -1);
	RETURN var_return;
    END */$$
DELIMITER ;

/* Function  structure for function  `fn_get_report_monitor_journey_nvt_signal_level` */

/*!50003 DROP FUNCTION IF EXISTS `fn_get_report_monitor_journey_nvt_signal_level` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_get_report_monitor_journey_nvt_signal_level`(var_monitor_journey_id bigINT(20)) RETURNS double
BEGIN
	DECLARE var_return DOUBLE;
	SET var_return = (SELECT avg(`signal_level`) from `tb_report_monitor_journey_nvt` where monitor_journey_id = var_monitor_journey_id and signal_level <> 99);
	RETURN var_return;
    END */$$
DELIMITER ;

/* Function  structure for function  `fn_get_report_monitor_journey_nvt_signal_quality` */

/*!50003 DROP FUNCTION IF EXISTS `fn_get_report_monitor_journey_nvt_signal_quality` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_get_report_monitor_journey_nvt_signal_quality`(var_monitor_journey_id bigINT(20)) RETURNS double
BEGIN
	DECLARE var_return DOUBLE;
	SET var_return = (SELECT avg(`signal_quality`) from `tb_report_monitor_journey_nvt` where monitor_journey_id = var_monitor_journey_id and signal_quality <> 99);
	RETURN var_return;
    END */$$
DELIMITER ;

/* Function  structure for function  `fn_get_report_monitor_journey_nvt_success` */

/*!50003 DROP FUNCTION IF EXISTS `fn_get_report_monitor_journey_nvt_success` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_get_report_monitor_journey_nvt_success`(var_monitor_journey_id bigINT(20)) RETURNS int(11)
BEGIN
	DECLARE var_return INT(11);
	SET var_return = (SELECT count(*) from `tb_report_monitor_journey_nvt` where monitor_journey_id = var_monitor_journey_id and not status);
	RETURN var_return;
    END */$$
DELIMITER ;

/* Function  structure for function  `fn_insert_monitor_journey` */

/*!50003 DROP FUNCTION IF EXISTS `fn_insert_monitor_journey` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_insert_monitor_journey`(var_device_id BIGINT(20), var_journey_id INT(11),
		var_location_lat DOUBLE, var_location_lng DOUBLE) RETURNS bigint(20)
BEGIN
	INSERT INTO `tb_monitor_journey` (`device_id`, `journey_id`, `location_lat`, `location_lng`, `monitor_date`, `monitor_datetime`)
		VALUES (var_device_id, var_journey_id, var_location_lat, var_location_lng, DATE(now()), NOW());
	RETURN (SELECT MAX(id) FROM tb_monitor_journey);
END */$$
DELIMITER ;

/* Function  structure for function  `fn_insert_monitor_journey_detail` */

/*!50003 DROP FUNCTION IF EXISTS `fn_insert_monitor_journey_detail` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_insert_monitor_journey_detail`(var_device_id int(11), var_monitor_journey_id BIGINT(20), var_journey_detail_id INT(11),
		var_network_type VARCHAR(10), var_cellid INT(11), var_signal_level INT(11),
		var_signal_quality int(11), var_ber int(11),
		var_response_time DOUBLE, var_latency double,
		var_packet_loss int(11), var_status INT(2), var_message VARCHAR(255), var_has_screenshot tinyint(2), var_repeat_no INT(11)) RETURNS bigint(20)
BEGIN
	DECLARE var_scheduled TINYINT(2);
	
	SELECT 	SUM(IF(NOW() BETWEEN start_datetime AND end_datetime,1,0))
			INTO 	var_scheduled
			FROM 	tb_downtime
			WHERE	published;
				
	set var_scheduled = ifnull(var_scheduled,0);
	
	INSERT INTO `tb_monitor_journey_detail` (device_id, monitor_journey_id, journey_detail_id, network_type, cellid, signal_level, signal_quality, ber, response_time,
			latency, `packet_loss`, `status`, message, `has_screenshot`, `monitor_date`, `monitor_datetime`, repeat_no, scheduled)
		VALUES (var_device_id, var_monitor_journey_id, var_journey_detail_id, var_network_type, var_cellid, var_signal_level, var_signal_quality, var_ber, var_response_time,
			var_latency, var_packet_loss, var_status, var_message, var_has_screenshot, date(now()), now(), var_repeat_no, var_scheduled);
	RETURN (SELECT MAX(id) FROM tb_monitor_journey_detail);
END */$$
DELIMITER ;

/* Function  structure for function  `fn_insert_monitor_journey_nvt` */

/*!50003 DROP FUNCTION IF EXISTS `fn_insert_monitor_journey_nvt` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_insert_monitor_journey_nvt`(var_device_id INT(11), var_monitor_journey_id BIGINT(20), var_network_type VARCHAR(10),
		var_cellid INT(11), var_signal_level INT(11),
		var_signal_quality INT(11), var_ber INT(11),
		var_response_time DOUBLE, var_latency double, var_packet_loss int(11),
		var_status INT(2), var_message VARCHAR(255), var_repeat_no int(11)) RETURNS bigint(20)
BEGIN
	INSERT INTO `tb_monitor_journey_nvt` (device_id, monitor_journey_id, network_type, cellid, signal_level, signal_quality, ber, response_time, latency, packet_loss,
			`status`, message, monitor_date, monitor_datetime, repeat_no)
		VALUES (var_device_id, var_monitor_journey_id, var_network_type, var_cellid, var_signal_level, var_signal_quality, var_ber, var_response_time, var_latency,
			var_packet_loss, var_status, var_message, date(now()), now(), var_repeat_no);
	RETURN (SELECT MAX(id) FROM tb_monitor_journey_nvt);
END */$$
DELIMITER ;

/* Function  structure for function  `fn_insert_report_daily` */

/*!50003 DROP FUNCTION IF EXISTS `fn_insert_report_daily` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_insert_report_daily`(var_device_id INT(11), var_report_date DATE) RETURNS bigint(20)
BEGIN
	INSERT INTO `tb_report_daily` (`device_id`, `report_date`, `create_datetime`)
		VALUES (var_device_id, var_report_date, NOW());
	RETURN (SELECT MAX(id) FROM tb_report_daily);
END */$$
DELIMITER ;

/* Function  structure for function  `fn_need_refresh` */

/*!50003 DROP FUNCTION IF EXISTS `fn_need_refresh` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_need_refresh`(var_id int(11)) RETURNS tinyint(2)
BEGIN
	declare ret_val tinyint(2);
	set ret_val = (select `need_refresh` from tb_user where id = var_id);
	update tb_user set `need_refresh` = '0' where id = var_id;
	RETURN ret_val;
END */$$
DELIMITER ;

/* Function  structure for function  `fn_new_device_notif_id` */

/*!50003 DROP FUNCTION IF EXISTS `fn_new_device_notif_id` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_new_device_notif_id`() RETURNS bigint(20)
BEGIN
	DECLARE var_max_id BIGINT;
	SET var_max_id = (SELECT IFNULL(MAX(id),0) FROM tb_device_notif);
	IF var_max_id > 0 THEN
		RETURN var_max_id + 1;
	ELSE
		RETURN 1;
	END IF;
    END */$$
DELIMITER ;

/* Function  structure for function  `fn_new_log_update_id` */

/*!50003 DROP FUNCTION IF EXISTS `fn_new_log_update_id` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_new_log_update_id`() RETURNS bigint(20)
BEGIN
	DECLARE var_max_id BIGINT;
	SET var_max_id = (SELECT IFNULL(MAX(id),0) FROM `tb_log_update`);
	IF var_max_id > 0 THEN
		RETURN var_max_id + 1;
	ELSE
		RETURN 1;
	END IF;
    END */$$
DELIMITER ;

/* Function  structure for function  `fn_new_trigger_device_id` */

/*!50003 DROP FUNCTION IF EXISTS `fn_new_trigger_device_id` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_new_trigger_device_id`() RETURNS bigint(20)
BEGIN
	DECLARE var_max_id BIGINT;
	SET var_max_id = (SELECT IFNULL(MAX(id),0) FROM tb_trigger_device);
	IF var_max_id > 0 THEN
		RETURN var_max_id + 1;
	ELSE
		RETURN 1;
	END IF;
    END */$$
DELIMITER ;

/* Function  structure for function  `fn_new_trigger_id` */

/*!50003 DROP FUNCTION IF EXISTS `fn_new_trigger_id` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_new_trigger_id`() RETURNS bigint(20)
BEGIN
	DECLARE var_max_id BIGINT;
	SET var_max_id = (SELECT IFNULL(MAX(id),0) FROM tb_trigger);
	IF var_max_id > 0 THEN
		RETURN var_max_id + 1;
	ELSE
		RETURN 1;
	END IF;
    END */$$
DELIMITER ;

/* Function  structure for function  `fn_new_trigger_journey_id` */

/*!50003 DROP FUNCTION IF EXISTS `fn_new_trigger_journey_id` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `fn_new_trigger_journey_id`() RETURNS bigint(20)
BEGIN
	DECLARE var_max_id BIGINT;
	SET var_max_id = (SELECT IFNULL(MAX(id),0) FROM tb_trigger_journey);
	IF var_max_id > 0 THEN
		RETURN var_max_id + 1;
	ELSE
		RETURN 1;
	END IF;
    END */$$
DELIMITER ;

/* Function  structure for function  `ROUNDUP` */

/*!50003 DROP FUNCTION IF EXISTS `ROUNDUP` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` FUNCTION `ROUNDUP`(var_number double, var_digit int) RETURNS double
BEGIN
		return IF(var_number-ROUND(var_number,var_digit)>0,ROUND(var_number,var_digit)+POW(10,-var_digit),ROUND(var_number,var_digit));
    END */$$
DELIMITER ;

/* Procedure structure for procedure `insert_device_notif` */

/*!50003 DROP PROCEDURE IF EXISTS  `insert_device_notif` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` PROCEDURE `insert_device_notif`(in var_device_id int(11))
BEGIN
	if (select count(*) from `tb_device_notif` where device_id = var_device_id) = 0 then
		insert into `tb_device_notif`
		(device_id)
		values
		(var_device_id);
	else
		update 	`tb_device_notif`
		set 	status = 0
		where 	device_id = var_device_id;
	end if;
END */$$
DELIMITER ;

/* Procedure structure for procedure `prepare_report` */

/*!50003 DROP PROCEDURE IF EXISTS  `prepare_report` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` PROCEDURE `prepare_report`(in var_year_month varchar(7))
BEGIN
	declare var_date_start date;
	DECLARE var_date_end DATE;
	
	set var_date_start = concat(var_year_month,'-01');
	SET var_date_end = LAST_DAY(var_date_start);
	
	truncate table `tb_report_error`;
	truncate table `tb_report_monitor_journey`;
	truncate table `tb_report_monitor_journey_detail`;
	truncate table `tb_report_monitor_journey_nvt`;
	
	update `tb_monthly_report_date` set `date_start` = var_date_start, `date_end` = var_date_end;
	
	insert into `tb_report_error`
		select * from tb_error where date(error_datetime) between var_date_start and var_date_end;
	
	INSERT INTO `tb_report_monitor_journey`
		SELECT * FROM `tb_monitor_journey` WHERE `monitor_date` BETWEEN var_date_start AND var_date_end;
		
	INSERT INTO `tb_report_monitor_journey_detail`
		SELECT * FROM `tb_monitor_journey_detail` WHERE date(`monitor_datetime`) BETWEEN var_date_start AND var_date_end;
		
	INSERT INTO `tb_report_monitor_journey_nvt`
		SELECT * FROM `tb_monitor_journey_nvt` WHERE DATE(`monitor_datetime`) BETWEEN var_date_start AND var_date_end;
		
	select 'Proses Finished';
    END */$$
DELIMITER ;

/* Procedure structure for procedure `prepare_report_custom` */

/*!50003 DROP PROCEDURE IF EXISTS  `prepare_report_custom` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` PROCEDURE `prepare_report_custom`(IN var_date_start date, in var_date_end date)
BEGIN
	TRUNCATE TABLE `tb_report_error`;
	TRUNCATE TABLE `tb_report_monitor_journey`;
	TRUNCATE TABLE `tb_report_monitor_journey_detail`;
	TRUNCATE TABLE `tb_report_monitor_journey_nvt`;
	
	UPDATE `tb_report_date` SET `date_start` = var_date_start, `date_end` = var_date_end;
	
	INSERT INTO `tb_report_error`
		SELECT * FROM tb_error WHERE DATE(error_datetime) BETWEEN var_date_start AND var_date_end;
	
	INSERT INTO `tb_report_monitor_journey`
		SELECT * FROM `tb_monitor_journey` WHERE `monitor_date` BETWEEN var_date_start AND var_date_end;
		
	INSERT INTO `tb_report_monitor_journey_detail`
		SELECT * FROM `tb_monitor_journey_detail` WHERE DATE(`monitor_datetime`) BETWEEN var_date_start AND var_date_end;
		
	INSERT INTO `tb_report_monitor_journey_nvt`
		SELECT * FROM `tb_monitor_journey_nvt` WHERE DATE(`monitor_datetime`) BETWEEN var_date_start AND var_date_end;
		
	SELECT 'Proses Finished';
    END */$$
DELIMITER ;

/* Procedure structure for procedure `prepare_report_monthly` */

/*!50003 DROP PROCEDURE IF EXISTS  `prepare_report_monthly` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` PROCEDURE `prepare_report_monthly`(IN var_year_month VARCHAR(7))
BEGIN
	DECLARE var_date_start DATE;
	DECLARE var_date_end DATE;
	
	SET var_date_start = CONCAT(var_year_month,'-01');
	SET var_date_end = LAST_DAY(var_date_start);
	
	TRUNCATE TABLE `tb_report_error`;
	TRUNCATE TABLE `tb_report_monitor_journey`;
	TRUNCATE TABLE `tb_report_monitor_journey_detail`;
	TRUNCATE TABLE `tb_report_monitor_journey_nvt`;
	
	UPDATE `tb_report_date` SET `date_start` = var_date_start, `date_end` = var_date_end;
	
	INSERT INTO `tb_report_error`
		SELECT * FROM tb_error WHERE DATE(error_datetime) BETWEEN var_date_start AND var_date_end;
	
	INSERT INTO `tb_report_monitor_journey`
		SELECT * FROM `tb_monitor_journey` WHERE `monitor_date` BETWEEN var_date_start AND var_date_end;
		
	INSERT INTO `tb_report_monitor_journey_detail`
		SELECT * FROM `tb_monitor_journey_detail` WHERE DATE(`monitor_datetime`) BETWEEN var_date_start AND var_date_end;
		
	INSERT INTO `tb_report_monitor_journey_nvt`
		SELECT * FROM `tb_monitor_journey_nvt` WHERE DATE(`monitor_datetime`) BETWEEN var_date_start AND var_date_end;
		
	SELECT 'Proses Finished';
    END */$$
DELIMITER ;

/* Procedure structure for procedure `sp_device_check` */

/*!50003 DROP PROCEDURE IF EXISTS  `sp_device_check` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` PROCEDURE `sp_device_check`()
BEGIN
    
	DECLARE done BOOLEAN DEFAULT FALSE;
	DECLARE var_id INT(11);
	DECLARE var_status INT(11);
	
	DECLARE cur1 CURSOR FOR SELECT id FROM `tb_device` WHERE published;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
	OPEN cur1;
	read_loop: LOOP
		FETCH cur1 INTO var_id;
		IF done THEN
			LEAVE read_loop;
		END IF;
		
		SET var_status = (SELECT `status` FROM `tb_device` WHERE id = var_id);
		IF NOT var_status THEN	#if status remains 0 after being checked twice, it means device is offline
			UPDATE 	`tb_device`
			SET 	status_final = 0,status_time = NOW()
			WHERE	id = var_id and status_final;
			# insert into tb_trigger_device (for dashboard)
			insert into `tb_trigger_device`
			values
			(fn_new_trigger_device_id(), var_id, 0);
			
		END IF;
		
	END LOOP;
	CLOSE cur1;
	UPDATE tb_device SET `status` = 0 WHERE published and `status`;
    
END */$$
DELIMITER ;

/* Procedure structure for procedure `sp_device_check_run` */

/*!50003 DROP PROCEDURE IF EXISTS  `sp_device_check_run` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_device_check_run`()
BEGIN

    

	DECLARE done BOOLEAN DEFAULT FALSE;

	DECLARE var_id INT(11);

	DECLARE var_status_run INT(11);

	

	DECLARE cur_run CURSOR FOR SELECT id FROM `tb_device` WHERE published;

	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

	OPEN cur_run;

	read_loop_run: LOOP

		FETCH cur_run INTO var_id;

		IF done THEN

			LEAVE read_loop_run;

		END IF;

		

		SET var_status_run = (SELECT `status_run` FROM `tb_device` WHERE id = var_id);

		IF NOT var_status_run THEN	
			UPDATE 	`tb_device`

			SET 	status_run_final = 0,status_run_time = NOW()

			WHERE	id = var_id AND status_run_final;

			

		END IF;

		

	END LOOP;

	CLOSE cur_run;

	UPDATE tb_device SET `status_run` = 0 WHERE published AND `status_run`;

    

END */$$
DELIMITER ;

/* Procedure structure for procedure `sp_reset_db` */

/*!50003 DROP PROCEDURE IF EXISTS  `sp_reset_db` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` PROCEDURE `sp_reset_db`()
BEGIN
	TRUNCATE TABLE tb_apm_client;
	TRUNCATE TABLE tb_apm_client_device;
	TRUNCATE TABLE tb_device;
	TRUNCATE TABLE tb_downtime;
	TRUNCATE TABLE tb_journey;
	TRUNCATE TABLE tb_journey_detail;
	TRUNCATE TABLE tb_journey_detail_task;
	TRUNCATE TABLE tb_list_cat;
	TRUNCATE TABLE tb_list_item;
	TRUNCATE TABLE tb_location;
	TRUNCATE TABLE tb_menu;
	TRUNCATE TABLE tb_menu_item;
	TRUNCATE TABLE tb_operator;
	TRUNCATE TABLE tb_setting;
	TRUNCATE TABLE tb_tpl_email;
	TRUNCATE TABLE tb_tpl_message;
	TRUNCATE TABLE tb_user;
	TRUNCATE TABLE tb_userrole;
	TRUNCATE TABLE tb_usertype;
	TRUNCATE TABLE tb_error;
	TRUNCATE TABLE `tb_log_update`;
	TRUNCATE TABLE `tb_monitor_journey`;
	TRUNCATE TABLE `tb_monitor_journey_detail`;
	TRUNCATE TABLE `tb_monitor_journey_detail_new`;
	TRUNCATE TABLE `tb_monitor_journey_nvt`;
	TRUNCATE TABLE `tb_report_error`;
	TRUNCATE TABLE `tb_report_monitor_journey`;
	TRUNCATE TABLE `tb_report_monitor_journey_detail`;
	TRUNCATE TABLE `tb_report_monitor_journey_nvt`;
	TRUNCATE TABLE `tb_local_setting`;
	
	insert into tb_local_setting (`name`, `value`)
	values
	('screenshot_path', '/var/www/html/senoapmxllo/userfiles/screenshot/monitor_journey_detail');
	
	INSERT INTO tb_local_setting (`name`, `value`)
	VALUES
	('apm_client_id', '0');	
	
    END */$$
DELIMITER ;

/* Procedure structure for procedure `sp_test` */

/*!50003 DROP PROCEDURE IF EXISTS  `sp_test` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` PROCEDURE `sp_test`(IN var_year_month VARCHAR(7))
BEGIN
	DECLARE var_date_start DATE;
	DECLARE var_date_end DATE;
	
	SET var_date_start = CONCAT(var_year_month,'-01');
	SET var_date_end = LAST_DAY(var_date_start);
	
	select var_date_start, var_date_end;
    END */$$
DELIMITER ;

/* Procedure structure for procedure `sp_user` */

/*!50003 DROP PROCEDURE IF EXISTS  `sp_user` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` PROCEDURE `sp_user`()
BEGIN
	drop TEMPORARY table IF EXISTS tb_user_copy;
	create TEMPORARY table tb_user_copy
	select * from tb_user;
	select * from tb_user_copy;
    END */$$
DELIMITER ;

/* Procedure structure for procedure `update_device_notif` */

/*!50003 DROP PROCEDURE IF EXISTS  `update_device_notif` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`senosoft`@`%` PROCEDURE `update_device_notif`(in var_device_id int(11), in var_status tinyint(2))
BEGIN
	update 	`tb_device_notif`
	set 	status = var_status
	where 	device_id = var_device_id;
END */$$
DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
