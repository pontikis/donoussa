<?php
// uri - path mapping ----------------------------------------------------------
$conf['project_url'] = '/url/to/project';
$conf['project_path'] = '/path/to/project';

// sessions --------------------------------------------------------------------
$conf['use_sessions'] = true;
$conf['session_save_path'] = '/var/lib/php5/myapp';

// log settings ----------------------------------------------------------------
$conf['log_errors'] = false;
$conf['error_log'] = '/path/to/error.log';
$conf['err_rpt'] = E_ALL ^ E_NOTICE;

// database connection settings ------------------------------------------------
$conf['db'] = array(
	'rdbms' => 'MYSQLi', // required one of MYSQLi, POSTGRES
	'db_server' => 'SERVER_NAME_OR_IP', // required
	'db_user' => 'DBUSER', // required
	'db_passwd' => 'DBPASS', // required
	'db_name' => 'DBNAME', // required
	'db_schema' => 'SCHEMA', // required for POSTGRES
	'db_port' => '3306', // required IF different from 3306 (MYSQLi) or 5432 (POSTGRES)
	'charset' => 'utf8',
	'use_prepared_statements' => false,
	'pst_placeholder' => 'question_mark', // one of 'question_mark' (?), 'numbered' ($1, $2, ...)
);

// mode ------------------------------------------------------------------------
$conf['demo_mode'] = false;
$conf['maintenance_mode'] = false;

// memcached settings ----------------------------------------------------------
$conf['mc'] = array(
	'mc_pool' => array(
		array(
			'mc_server' => '127.0.0.1',
			'mc_port' => '11211',
			//'mc_weight' => 0
		)
	),
	'use_memcached' => true
);
$conf['memcached_keys_prefix'] = 'myapp';

// class path ------------------------------------------------------------------
$conf['class'] = array(

	'class_dacapo_path' => '/dacapo/dacapo.class.php',

	'class_donoussa_path' => '/donoussa/donoussa.class.php',
	// https://github.com/tedivm/JShrink
	'JShrink' => '/JShrink_v1.1.0/Minifier.php',
	// http://code.google.com/p/cssmin/
	'cssmin' => '/cssmin_v3.0.1/cssmin.php',

	'class_ithaca_path' => '/ithaca/ithaca.class.php',
	// http://www.openwall.com/phpass/
	'class_phpass_path' => '/phpass-0.3/PasswordHash.php',

);

// dependencies
$conf['dependencies'] = array(
	'bootstrap_css' => array(
		'type' => 'css',
		'default' => '/bootstrap_v3.1.1/bootstrap_cerulean.css',
		'element_id' => 'bootstrap_css',
		'session' => array(
			'variable' => 'user_bootstrap_theme',
			'values' => array(
				'Bootswatch cerulean' => '/bootstrap_v3.1.1/bootstrap_cerulean.css',
				'Bootswatch flatly' => '/bootstrap_v3.1.1/bootstrap_flatly.css',
				'Bootswatch slate' => '/bootstrap_v3.1.1/bootstrap_slate.css',
				'Bootswatch united' => '/bootstrap_v3.1.1/bootstrap_united.css',
				'Twitter bootstrap' => '/bootstrap_v3.1.1/bootstrap.css',
			)
		),
	),
	'font_awesome_css' => array(
		'type' => 'css',
		'default' => '/font-awesome_v3.2.1/css/font-awesome.css',
	),
	'jquery_ui_css' => array(
		'type' => 'css',
		'default' => '/jquery-ui_v1.9.2/css/ui-lightness/jquery-ui-1.9.2.custom.css',
		'element_id' => 'jquery_ui_css',
		'session' => array(
			'variable' => 'user_jquery_ui_theme',
			'values' => array(
				'ui-lightness' => '/jquery-ui_v1.9.2/css/ui-lightness/jquery-ui-1.9.2.custom.css',
				'jquery-ui-bootstrap' => '/jquery-ui-bootstrap_gh_pages/jquery-ui-1.10.3.custom.css',
				'aristo' => '/aristo/Aristo.css',
			)
		),
	),
	'timepicker_css' => array(
		'type' => 'css',
		'default' => '/jQuery-Timepicker-Addon_v1.4.0/jquery-ui-timepicker-addon.css',
	),
	'jui_alert_css' => array(
		'type' => 'css',
		'default' => '/jui_alert_v2.0.0/jquery.jui_alert.css',
	),
	'jui_dropdown_css' => array(
		'type' => 'css',
		'default' => '/jui_dropdown_v1.0.4/jquery.jui_dropdown.css',
	),
	'jui_filter_rules_css' => array(
		'type' => 'css',
		'default' => '/jui_filter_rules_v1.0.3/jquery.jui_filter_rules.css',
	),
	'jui_pagination_css' => array(
		'type' => 'css',
		'default' => '/jui_pagination_v2.0.0/jquery.jui_pagination.css',
	),
	'jui_datagrid_css' => array(
		'type' => 'css',
		'default' => '/jui_datagrid_v0.9.1/jquery.jui_datagrid.css',
	),
	'common_css' => array(
		'type' => 'css',
		'default' => '/app/common/css/index.css',
	),
	'page_css' => array(
		'type' => 'css',
		'default' => 'index.css',
	),

	'jquery_js' => array(
		'type' => 'js',
		'default' => '/jquery_v1.10.2/jquery-1.10.2.min.js',
	),
	'bootstrap_js' => array(
		'type' => 'js',
		'default' => '/bootstrap_v3.1.1/bootstrap.js',
	),
	'jquery_ui_js' => array(
		'type' => 'js',
		'default' => '/jquery-ui_v1.9.2/js/jquery-ui-1.9.2.custom.min.js',
	),
	'datepicker_i18n_js' => array(
		'type' => 'js',
		'default' => '',
		'locale' => array(
			'el_GR' => '/jquery-ui-i18n/datepicker/jquery.ui.datepicker-el.js',
			'ru_RU' => '/jquery-ui-i18n/datepicker/jquery.ui.datepicker-ru.js',
		),
		'url' => 'https://github.com/jquery/jquery-ui/tree/master/ui/i18n'
	),
	'touch_punch_js' => array(
		'type' => 'js',
		'default' => '/Touch_Punch_v0.2.2/jquery.ui.touch-punch.min.js',
	),
	'bowser_js' => array(
		'type' => 'js',
		'default' => '/bowser_v0.3.1/bowser.min.js',
	),
	'momentjs_js' => array(
		'type' => 'js',
		'default' => '/momentjs_v2.2.1/moment+langs.min.js',
	),
	'timepicker_js' => array(
		'type' => 'js',
		'default' => '/jQuery-Timepicker-Addon_v1.4.0/jquery-ui-timepicker-addon.js',
	),
	'timepicker_i18n_js' => array(
		'type' => 'js',
		'default' => '',
		'locale' => array(
			'el_GR' => '/jquery-ui-i18n/timepicker/jquery-ui-timepicker-el.js',
			'ru_RU' => '/jquery-ui-i18n/timepicker/jquery-ui-timepicker-ru.js',
		)
	),
	'ui_dialog_reposition_js' => array(
		'type' => 'js',
		'default' => '/jquery-ui-extensions/ui-dialog/ui-dialog-repostion.js',
	),
	'google_maps_api_js' => array(
		'type' => 'js',
		'default' => 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places',
	),
	'jui_alert_js' => array(
		'type' => 'js',
		'default' => '/jui_alert_v2.0.0/jquery.jui_alert.js',
	),
	'jui_alert_i18n_js' => array(
		'type' => 'js',
		'default' => '/jui_alert_v2.0.0/localization/en.js',
		'locale' => array(
			'en_US' => '/jui_alert_v2.0.0/localization/en.js',
			'el_GR' => '/jui_alert_v2.0.0/localization/el.js',
		)
	),
	'jui_dropdown_js' => array(
		'type' => 'js',
		'default' => '/jui_dropdown_v1.0.4/jquery.jui_dropdown.min.js',
	),
	'jui_filter_rules_js' => array(
		'type' => 'js',
		'default' => '/jui_filter_rules_v1.0.3/jquery.jui_filter_rules.min.js',
	),
	'jui_filter_rules_i18n_js' => array(
		'type' => 'js',
		'default' => '/jui_filter_rules_v1.0.3/localization/en.js',
		'locale' => array(
			'en_US' => '/jui_filter_rules_v1.0.3/localization/en.js',
			'el_GR' => '/jui_filter_rules_v1.0.3/localization/el.js',
		)
	),
	'jui_pagination_js' => array(
		'type' => 'js',
		'default' => '/jui_pagination_v2.0.0/jquery.jui_pagination.min.js',
	),
	'jui_pagination_i18n_js' => array(
		'type' => 'js',
		'default' => '/jui_pagination_v2.0.0/localization/en.js',
		'locale' => array(
			'en_US' => '/jui_pagination_v2.0.0/localization/en.js',
			'el_GR' => '/jui_pagination_v2.0.0/localization/el.js',
		)
	),
	'jui_datagrid_js' => array(
		'type' => 'js',
		'default' => '/jui_datagrid_v0.9.1/jquery.jui_datagrid.min.js',
	),
	'jui_datagrid_i18n_js' => array(
		'type' => 'js',
		'default' => '/jui_datagrid_v0.9.1/localization/en.js',
		'locale' => array(
			'en_US' => '/jui_datagrid_v0.9.1/localization/en.js',
			'el_GR' => '/jui_datagrid_v0.9.1/localization/el.js',
		)
	),
	'common_js' => array(
		'type' => 'js',
		'default' => '',
	),
	'page_js' => array(
		'type' => 'js',
		'default' => 'index.js',
	),
	'html5shiv_js' => array(
		'type' => 'js',
		'default' => '/html5shiv_v3.7.0/html5shiv.js',
		'condition' => array(
			'start' => '<!--[if lt IE 9]>',
			'end' => '<![endif]-->',
		),
		'description' => 'IE8 support of HTML5 elements and media queries'
	),
	'respond_js' => array(
		'type' => 'js',
		'default' => '/Respond.js_v1.4.2/respond.min.js',
		'condition' => array(
			'start' => '<!--[if lt IE 9]>',
			'end' => '<![endif]-->',
		),
		'description' => 'IE8 support of HTML5 elements and media queries'
	)
);
