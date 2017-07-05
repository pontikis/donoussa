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

// common page_id  -------------------------------------------------------------
// TODO
$conf['common_page_id'] = array(
	'terms' => 'terms',
	'login' => 'login',
	'logoff' => 'logoff',
	'maintenance' => 'maintenance'
);

$conf['404'] = '/app/404'; // TODO

// class path ------------------------------------------------------------------
$conf['class'] = array(

	'class_donoussa_path' => '/donoussa/donoussa.class.php',
	'class_datasource_path' => '/donoussa/donoussa_db.class.php',
	'class_auth_path' => '//donoussa/donoussa_auth.class.php',

	'class_phpass_path' => '/phpass-0.3/PasswordHash.php',
	'class_swift_mailer_path' => '/Swift-5.0.3/lib/swift_required.php',

	'class_jui_filter_rules_php_path' => '/jui_filter_rules_v1.0.3/jui_filter_rules.php',
	'class_jui_datagrid_php_path' => '/jui_datagrid_v0.9.1/jui_datagrid.php'

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


// authentication defaults -----------------------------------------------------
$conf['auth'] = array(

	// user status
	'user_status' => array(
		'pending' => 1,
		'active' => 2,
		'disapproved' => 3,
		'paused' => 4,
		'removed' => 5,
		'cancelled' => 6
	),
	'default_user_status' => 1,

	// user roles
	'user_roles' => array(
		'user' => 1,
		'admin' => 2,
	),
	'default_user_role' => 1, // CONFIGURE

	'login' => '/login',
	'logoff' => '/logoff',
	'change_password' => '/change-password',
	'email_verify' => '/email-verify',

	'user_photos' => '/files/uploads/user_photos',

	/**
	| never.......: DO NOT create verification code, DO NOT ask for verification
	|
	| optional....: create verification code, ask for verification but
	|               do not restrict access if user has not verified his/her email
	|
	| registration: create verification code, ask for verification and
	|               restrict access after login if user has not verified his/her registration email
	|               (this is the DEFAULT option)
	|
	| always......: same with 'registration', but for any email (not only for registration email)
	|               (so, if user changes his/her email, this must be verified, otherwise use cannot login)
	 */
	'email_verification' => 'registration',

	// username ----------------------------------------------------------------
	'username_min_chars' => 6,
	'username_max_chars' => 40,

	'username_chars' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
	'username_special_chars' => '.-_',

	'usernames_reserved' => array(
		'root', 'admin', 'sys', 'administrator', 'sysadmin', 'postmaster'
	),

	// password ----------------------------------------------------------------
	'password_min_chars' => 8,
	'password_max_chars' => 150,

	'password_chars' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
	'password_special_chars' => '!"#$%&\'()*+,-./:;<=>?@[\]^_`{|}~', // CAUTION: escape (\) quotes
	'password_allow_space' => true,

	'password_suggest_length' => 12,

	'password_min_strength' => 60,

	'remember_me' => false,

	'user_photo' => array(
		'max_file_size' => 524288,  // in bytes (1Mb = 1,048,576 = 1,024 x 1,024)
		'extensions' => array(
			'jpg',
			'jpeg',
			'png'
		),
		'types' => array(
			IMAGETYPE_JPEG => 'jpg',
			IMAGETYPE_PNG => 'png'
		),
		'male' => '/app/usr/img/user_male.jpg',
		'female' => '/app/usr/img/user_female.jpg',
		'no_gender' => '/app/usr/img/user.jpg',
	)
);

// email settings --------------------------------------------------------------
$conf['email'] = array(
	'send_mail' => false, // CONFIGURE
	'from_email' => 'EMAIL HERE', // CONFIGURE
	'from_name' => 'EMAIL NAME HERE', // CONFIGURE
	'admin_to' => array(),
	'admin_cc' => array(),
	'admin_bcc' => array()
);

// language settings -----------------------------------------------------------
$conf['multilingual'] = true;
$conf['force_select_language'] = false;
$conf['cookie_app_language'] = 'foo';
$conf['default_locale_code'] = 'en_US';
$conf['i18n_path'] = '/app/i18n';
$conf['gettext_domain'] = 'myapp';
$conf['locales'] = array(
	'en_US' => 'English (United States)',
	'el_GR' => 'Ελληνικά',
	'ru_RU' => 'русский',
);

// datetime settings -----------------------------------------------------------
$conf['dt'] = array(
	'user_timezone' => 'UTC', // default user timezone
	'user_dateformat' => 'EU_LZ_SLASH_24H_LZ', // default user dateformat
	'user_calendar' => 1, // default user calendar (GREGORIAN)
	'server_timezone' => 'UTC',
	'server_dateformat' => 'YmdHis',

	'calendars' => array(
		'gregorian' => 1,
		'traditional' => 0
	),

	/**
	 * available date formats
	 *
	 * php DateTime class date format
	 * http://www.php.net/manual/en/datetime.createfromformat.php
	 * 	php intl date format (ICU - International Components for Unicode)
	 * http://userguide.icu-project.org/formatparse/datetime
	 * jquery datepicker date format
	 * http://api.jqueryui.com/datepicker/
	 * jquery datetimepicker plugin time format
	 * http://trentrichardson.com/examples/timepicker/
	 *
	 * 'EU_LZ_SLASH_24H_LZ' EU format (day/month/year), slash as delimiter with leading zeros. 24h clock with leading zeros.
	 * 'US_LZ_SLASH_24H_LZ' US format (month/day/year), slash as delimiter with leading zeros. 24h clock with leading zeros.
	 * 'EU_LZ_DASH_24H_LZ' EU format (day-month-year), dash as delimiter with leading zeros. 24h clock with leading zeros.
	 * 'US_LZ_DASH_24H_LZ' US format (month-day-year), dash as delimiter with leading zeros. 24h clock with leading zeros.
	 * 'ISO_LZ_DASH_24H_LZ' ISO format (year-month-day), dash as delimiter with leading zeros. 24h clock with leading zeros.
	 *
	 * affected from locale:
	 * 'EU_INTL_24H_LZ' EU format (day/month/year), month name, day without leading zeros. 24h clock with leading zeros.
	 * 'EU_INTL_SHORT_24H_LZ' EU format (day/month/year), month name short, day without leading zeros. 24h clock with leading zeros.
	 * 'US_INTL_24H_LZ' US format (month/day/year), month name, day without leading zeros. 24h clock with leading zeros.
	 * 'EU_INTL_DAYNAME_24H_LZ' EU format (day/month/year), day name, month name, day without leading zeros. 24h clock with leading zeros.
	 * 'EU_INTL_SHORT_DAYNAME_24H_LZ' EU format (day/month/year), day name short, month name short, day without leading zeros. 24h clock with leading zeros.
	 *
	 * About dates
	 * All dates are stored in varchar(14) columns
	 * Y-m-d| will set the year, month and day to the information found in the string to parse, and sets the hour, minute and second to 0.
	 * Otherwise, using DateTime::createFromFormat the missing time digits filled with zero, but if all times digits are missing current time is returned.
	 *
	 */
	'dateformat' => array(
		'EU_LZ_SLASH_24H_LZ' => array(
			'php_datetime' => 'd/m/Y H:i:s',
			'php_datetime_short' => 'd/m/Y H:i',
			'php_date' => 'd/m/Y',
			'jq_date' => 'dd/mm/yy',
			'jq_time' => 'hh:mm:ss',
			'jq_time_short' => 'hh:mm'
		),
		'US_LZ_SLASH_24H_LZ' => array(
			'php_datetime' => 'm/d/Y H:i:s',
			'php_datetime_short' => 'm/d/Y H:i',
			'php_date' => 'm/d/Y',
			'jq_date' => 'mm/dd/yy',
			'jq_time' => 'hh:mm:ss',
			'jq_time_short' => 'hh:mm'
		),
		'EU_LZ_DASH_24H_LZ' => array(
			'php_datetime' => 'd-m-Y H:i:s',
			'php_datetime_short' => 'd-m-Y H:i',
			'php_date' => 'd-m-Y',
			'jq_date' => 'dd-mm-yy',
			'jq_time' => 'hh:mm:ss',
			'jq_time_short' => 'hh:mm'
		),
		'US_LZ_DASH_24H_LZ' => array(
			'php_datetime' => 'm-d-Y H:i:s',
			'php_datetime_short' => 'm-d-Y H:i',
			'php_date' => 'm-d-Y',
			'jq_date' => 'mm-dd-yy',
			'jq_time' => 'hh:mm:ss',
			'jq_time_short' => 'hh:mm'
		),
		'ISO_LZ_DASH_24H_LZ' => array(
			'php_datetime' => 'Y-m-d H:i:s',
			'php_datetime_short' => 'Y-m-d H:i',
			'php_date' => 'Y-m-d',
			'jq_date' => 'yy-mm-dd',
			'jq_time' => 'hh:mm:ss',
			'jq_time_short' => 'hh:mm'
		),
		// affected from locale
		'EU_INTL_24H_LZ' => array(
			'php_datetime' => 'd F Y H:i:s',
			'php_datetime_short' => 'd F Y H:i',
			'php_date' => 'd F Y',
			'php_datetime_intl' => 'd MMMM yyyy HH:mm:ss',
			'php_datetime_short_intl' => 'd MMMM yyyy HH:mm',
			'php_date_intl' => 'd MMMM yyyy',
			'jq_date' => 'd MM yy',
			'jq_time' => 'hh:mm:ss',
			'jq_time_short' => 'hh:mm'
		),
		'EU_INTL_SHORT_24H_LZ' => array(
			'php_datetime' => 'd M Y H:i:s',
			'php_datetime_short' => 'd M Y H:i',
			'php_date' => 'd M Y',
			'php_datetime_intl' => 'd MMM yyyy HH:mm:ss',
			'php_datetime_short_intl' => 'd MMM yyyy HH:mm',
			'php_date_intl' => 'd MMM yyyy',
			'jq_date' => 'd M yy',
			'jq_time' => 'hh:mm:ss',
			'jq_time_short' => 'hh:mm'
		),
		'US_INTL_24H_LZ' => array(
			'php_datetime' => 'F d, Y H:i:s',
			'php_datetime_short' => 'F d, Y H:i',
			'php_date' => 'F d, Y',
			'php_datetime_intl' => 'MMMM d, yyyy HH:mm:ss',
			'php_datetime_short_intl' => 'MMMM d, yyyy HH:mm',
			'php_date_intl' => 'MMMM d, yyyy',
			'jq_date' => 'MM d, yy',
			'jq_time' => 'hh:mm:ss',
			'jq_time_short' => 'hh:mm'
		),
		'EU_INTL_DAYNAME_24H_LZ' => array(
			'php_datetime' => 'l, d F Y H:i:s',
			'php_datetime_short' => 'l, d F Y H:i',
			'php_date' => 'l, d F Y',
			'php_datetime_intl' => 'EEEE, d MMMM yyyy HH:mm:ss',
			'php_datetime_short_intl' => 'EEEE, d MMMM yyyy HH:mm',
			'php_date_intl' => 'EEEE, d MMMM yyyy',
			'jq_date' => 'DD, d MM yy',
			'jq_time' => 'hh:mm:ss',
			'jq_time_short' => 'hh:mm'
		),
		'EU_INTL_SHORT_DAYNAME_24H_LZ' => array(
			'php_datetime' => 'D, d M Y H:i:s',
			'php_datetime_short' => 'D, d M Y H:i',
			'php_date' => 'D, d M Y',
			'php_datetime_intl' => 'EEEEEE, d MMM yyyy HH:mm:ss',
			'php_datetime_short_intl' => 'EEEEEE, d MMM yyyy HH:mm',
			'php_date_intl' => 'EEDEEE, d MMM yyyy',
			'jq_date' => 'D, d M yy',
			'jq_time' => 'hh:mm:ss',
			'jq_time_short' => 'hh:mm'
		),
	)
);

// themes ----------------------------------------------------------------------
$conf['bootstrap_themes'] = array(
	'Bootswatch cerulean',
	'Bootswatch flatly',
	'Bootswatch slate',
	'Bootswatch united',
	'Twitter bootstrap'
);

$conf['jquery_ui_themes'] = array(
	'ui-lightness',
	'jquery-ui-bootstrap',
	'aristo'
);

// default themes
$conf['bootstrap_theme'] = 'Bootswatch cerulean';
$conf['jquery_ui_theme'] = 'ui-lightness';

// lookup ----------------------------------------------------------------------
$conf['lk'] = array(
	'genders' => array(
		'male' => 1,
		'female' => 2,
	)
);
?>