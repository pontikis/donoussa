SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `page_dependencies`;
CREATE TABLE `page_dependencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` varchar(200) NOT NULL,
  `modernizr_js` varchar(200) DEFAULT NULL,
  `jquery_js` varchar(200) DEFAULT NULL,
  `jquery_ui_js` varchar(200) DEFAULT NULL,
  `jquery_ui_css` varchar(200) DEFAULT NULL,
  `bootstrap_css` varchar(200) DEFAULT NULL,
  `bootstrap_js` varchar(200) DEFAULT NULL,
  `font_awesome_css` varchar(200) DEFAULT NULL,
  `touch_punch_js` varchar(200) DEFAULT NULL,
  `bowser_js` varchar(200) DEFAULT NULL,
  `momentjs_js` varchar(200) DEFAULT NULL,
  `momentjs_i18n_js` varchar(200) DEFAULT NULL,
  `jquery_ui_autocomplete_html_js` varchar(200) DEFAULT NULL,
  `datepicker_i18n_js` varchar(200) DEFAULT NULL,
  `timepicker_css` varchar(200) DEFAULT NULL,
  `timepicker_js` varchar(200) DEFAULT NULL,
  `timepicker_i18n_js` varchar(200) DEFAULT NULL,
  `google_maps_api_js` varchar(200) DEFAULT NULL,
  `php_bs_grid_css` varchar(200) DEFAULT NULL,
  `php_bs_grid_js` varchar(200) DEFAULT NULL,
  `html5shiv_js` varchar(200) DEFAULT NULL,
  `respond_js` varchar(200) DEFAULT NULL,
  `common_css` varchar(200) DEFAULT NULL,
  `page_css` varchar(200) DEFAULT NULL,
  `common_js` varchar(200) DEFAULT NULL,
  `page_js` varchar(200) DEFAULT NULL,
  `webfont_medical_icons` varchar(200) DEFAULT NULL,
  `imageloaded_js` varchar(200) DEFAULT NULL,
  `chartjs_js` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_dependencies_page_id_key` (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `page_properties`;
CREATE TABLE `page_properties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` varchar(200) NOT NULL,
  `real_url` varchar(200) NOT NULL,
  `unique_url` smallint(6) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `description` varchar(160) DEFAULT NULL,
  `tag` varchar(200) DEFAULT NULL,
  `package` varchar(200) DEFAULT NULL,
  `auth_required` smallint(6) NOT NULL,
  `roles` varchar(50) NOT NULL,
  `model_filename` varchar(200) DEFAULT NULL,
  `view_filename` varchar(200) DEFAULT NULL,
  `header` varchar(200) NOT NULL,
  `footer` varchar(200) NOT NULL,
  `modal_dialog` smallint(6) DEFAULT NULL,
  `modal_confirm` smallint(6) DEFAULT NULL,
  `is_alias_of` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_properties_page_id_key` (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `page_url`;
CREATE TABLE `page_url` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` varchar(200) NOT NULL,
  `url` varchar(200) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `title_param` varchar(200) DEFAULT NULL,
  `description` varchar(160) DEFAULT NULL,
  `request_type` smallint(6) NOT NULL,
  `security_check` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_url_url_key` (`url`),
  KEY `page_url_page_id_idx` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;