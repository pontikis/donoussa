-- MySQL dump 10.14  Distrib 5.5.30-MariaDB, for Linux (x86_64)
--
-- Host: localhost
-- ------------------------------------------------------
-- Server version	5.5.30-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `page_properties`
--

DROP TABLE IF EXISTS `page_properties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_properties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` varchar(200) NOT NULL,
  `real_url` varchar(200) NOT NULL,
  `unique_url` tinyint(4) NOT NULL,
  `tag` varchar(200) DEFAULT NULL,
  `package` varchar(200) DEFAULT NULL,
  `auth_required` tinyint(4) NOT NULL,
  `roles` varchar(50) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `description` varchar(160) DEFAULT NULL,
  `header` varchar(200) NOT NULL,
  `footer` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_id` (`page_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `page_dependencies`
--

DROP TABLE IF EXISTS `page_dependencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_dependencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` varchar(200) NOT NULL,
  `jquery_js` varchar(200) DEFAULT NULL,
  `jquery_ui_js` varchar(200) DEFAULT NULL,
  `jquery_ui_css` varchar(200) DEFAULT NULL,
  `bootstrap_css` varchar(200) DEFAULT NULL,
  `bootstrap_js` varchar(200) DEFAULT NULL,
  `font_awesome_css` varchar(200) DEFAULT NULL,
  `touch_punch_js` varchar(200) DEFAULT NULL,
  `bowser_js` varchar(200) DEFAULT NULL,
  `momentjs_js` varchar(200) DEFAULT NULL,
  `datepicker_i18n_js` varchar(200) DEFAULT NULL,
  `timepicker_css` varchar(200) DEFAULT NULL,
  `timepicker_js` varchar(200) DEFAULT NULL,
  `timepicker_i18n_js` varchar(200) DEFAULT NULL,
  `ui_dialog_reposition_js` varchar(200) DEFAULT NULL,
  `google_maps_api_js` varchar(200) DEFAULT NULL,
  `jui_alert_css` varchar(200) DEFAULT NULL,
  `jui_alert_js` varchar(200) DEFAULT NULL,
  `jui_alert_i18n_js` varchar(200) DEFAULT NULL,
  `jui_dropdown_css` varchar(200) DEFAULT NULL,
  `jui_dropdown_js` varchar(200) DEFAULT NULL,
  `jui_filter_rules_css` varchar(200) DEFAULT NULL,
  `jui_filter_rules_js` varchar(200) DEFAULT NULL,
  `jui_filter_rules_i18n_js` varchar(200) DEFAULT NULL,
  `jui_pagination_css` varchar(200) DEFAULT NULL,
  `jui_pagination_js` varchar(200) DEFAULT NULL,
  `jui_pagination_i18n_js` varchar(200) DEFAULT NULL,
  `jui_datagrid_css` varchar(200) DEFAULT NULL,
  `jui_datagrid_js` varchar(200) DEFAULT NULL,
  `jui_datagrid_i18n_js` varchar(200) DEFAULT NULL,
  `html5shiv_js` varchar(200) DEFAULT NULL,
  `respond_js` varchar(200) DEFAULT NULL,
  `common_css` varchar(200) DEFAULT NULL,
  `page_css` varchar(200) DEFAULT NULL,
  `common_js` varchar(200) DEFAULT NULL,
  `page_js` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_id` (`page_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `page_url`
--

DROP TABLE IF EXISTS `page_url`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_url` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` varchar(200) NOT NULL,
  `url` varchar(200) NOT NULL,
  `request_type` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx2_url_unique` (`url`),
  KEY `idx1_page_id` (`page_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-04-15 21:54:00
