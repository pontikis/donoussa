<?php
/**
 * *****************************************************************************
 * SESSION
 * *****************************************************************************
 */
if($conf['use_sessions']) {
	if($conf['session_save_path']) {
		ini_set('session.save_path', $conf['session_save_path']);
	}
	session_start();
}

/**
 * *****************************************************************************
 * CONSTANTS
 * *****************************************************************************
 */
/* uri - path mapping */
define('C_PROJECT_URL', $conf['project_url']);
define('C_PROJECT_PATH', $conf['project_path']);

/* project host */
$protocol = 'http';
$port = '';
if(isset ($_SERVER['HTTPS'])) {
	if(strtoupper($_SERVER['HTTPS']) == 'ON') {
		$protocol = 'https';
	}
}
if($_SERVER['SERVER_PORT'] != 80) {
	$port = ':' . $_SERVER['SERVER_PORT'];
}
define('C_PROJECT_HOST', $protocol . '://' . $_SERVER['SERVER_NAME'] . $port);

/* datetime constants */
define('C_SERVER_TIMEZONE', $conf['dt']['server_timezone']);
define('C_SERVER_DATEFORMAT', $conf['dt']['server_dateformat']);

/* LIB URLs */
define('C_LIB_FRONT_END_URL', $conf['project_url'] . '/lib/front_end');
define('C_LIB_PHP_PATH', $conf['project_path'] . '/lib/php');

/* CLASS PATH */
define('C_CLASS_DONOUSSA_PATH', C_PROJECT_PATH . $conf['class']['class_donoussa_path']);
define('C_CLASS_DATASOURCE_PATH', C_PROJECT_PATH . $conf['class']['class_datasource_path']);
define('C_CLASS_AUTH_PATH', C_PROJECT_PATH . $conf['class']['class_auth_path']);

define('C_PHPASS_PATH', C_LIB_PHP_PATH . $conf['class']['class_phpass_path']);
define('C_SWIFT_MAILER_PATH', C_LIB_PHP_PATH . $conf['class']['class_swift_mailer_path']);

define('C_JUI_FILTER_RULES_PHP_PATH', C_LIB_PHP_PATH . $conf['class']['class_jui_filter_rules_php_path']);
define('C_JUI_DATAGRID_PHP_PATH', C_LIB_PHP_PATH . $conf['class']['class_jui_datagrid_php_path']);

/* AUTH */
define('C_USER_PENDING', $conf['auth']['user_status']['pending']);
define('C_USER_ACTIVE', $conf['auth']['user_status']['active']);

/**
 * *****************************************************************************
 * SET SERVER TIMEZONE
 * *****************************************************************************
 */
date_default_timezone_set(C_SERVER_TIMEZONE);

/**
 * *****************************************************************************
 * ERROR HANDLING
 * *****************************************************************************
 */

error_reporting($conf['err_rpt']);

/**
 * Error handler function. Replaces PHP's error handler
 *
 * E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING are always handled by PHP.
 * E_WARNING, E_NOTICE, E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE are handled by this function.
 *
 * @param $err_no
 * @param $err_str
 * @param $err_file
 * @param $err_line
 *
 * @throws ErrorException
 * @return bool
 */
function error_handler($err_no, $err_str, $err_file, $err_line) {

	// if error_reporting is set to 0, exit. This is also the case when using @
	if(ini_get('error_reporting') == '0') {
		return true;
	}

	/*	if(!(error_reporting() & $err_no)) {
			// This error code is not included in error_reporting
			return;
		}*/

	// handle error
	switch($err_no) {
		case E_WARNING:
			$a_msg = array('[ErrNo=' . $err_no . ' (WARNING), File=' . $err_file . ', Line=' . $err_line . '] ', $err_str);
			log_error($a_msg, false, true);
			throw new ErrorException($err_str, $err_no, 0, $err_file, $err_line);
			break;
		case E_NOTICE:
			$a_msg = array('[ErrNo=' . $err_no . ' (NOTICE), File=' . $err_file . ', Line=' . $err_line . '] ', $err_str);
			log_error($a_msg, false, true);
			throw new ErrorException($err_str, $err_no, 0, $err_file, $err_line);
			break;
		case E_USER_ERROR:
			$a_msg = array('[ErrNo=' . $err_no . ' (USER ERROR), File=' . $err_file . ', Line=' . $err_line . '] ', $err_str);
			log_error($a_msg);
			exit;
			break;
		case E_USER_WARNING:
			$a_msg = array('[ErrNo=' . $err_no . ' (USER WARNING), File=' . $err_file . ', Line=' . $err_line . '] ', $err_str);
			log_error($a_msg);
			break;
		case E_USER_NOTICE:
			$a_msg = array('[ErrNo=' . $err_no . ' (USER NOTICE), File=' . $err_file . ', Line=' . $err_line . '] ', $err_str);
			log_error($a_msg, false, true);
			break;
		case 2048: // E_STRICT in PHP5
			// ignore
			break;
		default:
			// unknown error. Log in file (only) and continue execution
			$a_msg = array('[ErrNo=' . $err_no . ' (UNKNOWN ERROR), File=' . $err_file . ', Line=' . $err_line . '] ', $err_str);
			log_error($a_msg, false);
			break;
	}

	/* Don't execute PHP internal error handler */
	return true;
}


/**
 * Log error
 *
 * @param array $a_msg
 * @param bool $show_onscreen
 * @param bool $write_log
 */
function log_error($a_msg, $show_onscreen = true, $write_log = true) {

	global $conf;

	// put in screen
	if($show_onscreen) {
		print '<div style="color: red; margin-bottom: 20px;">' . $a_msg[0] . '</div>';
		print '<div>' . $a_msg[1] . '</div>';
		print '<hr>';
	}

	// put in file
	if($write_log) {
		@error_log(date('Y-m-d H:i:s') . ': ' . $a_msg[0] . ' ' . $a_msg[1] . PHP_EOL, 3, $conf['error_log_file']);
	}

}

set_error_handler('error_handler', E_ALL);


/**
 * @param $e
 */
function exception_handler($e) {
	$a_msg = array('[Uncaught exception: ' . 'File: ' . $e->getFile() . ' Line: ' . $e->getLine() . ']', $e->getMessage());
	log_error($a_msg);
}

set_exception_handler('exception_handler');

/**
 * @param $e
 * @param bool $screen
 * @param bool $write
 * @param bool $exit
 */
function exception_catch(Exception $e, $screen = true, $write = true, $exit = true) {

	$a_msg = array('[Exception: ' . 'File: ' . $e->getFile() . ' Line: ' . $e->getLine() . ']', $e->getMessage());

	log_error($a_msg, $screen, $write);
	if($exit) {
		exit;
	}
}


/**
 * *****************************************************************************
 * i18n
 * *****************************************************************************
 */
// app locale
$app_locale = $conf['default_locale_code'];
// detect locale
if($conf['multilingual']) {
	// check SESSION
	if(isset($_SESSION['locale'])) {
		$app_locale = $_SESSION['locale'];
	} else {
		// check COOKIE
		if($conf['cookie_app_language']) {
			if(isset($_COOKIE[$conf['cookie_app_language']])) {
				$app_locale = $_COOKIE[$conf['cookie_app_language']];
			} else {
				// check BROWSER
				if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && strlen($_SERVER['HTTP_ACCEPT_LANGUAGE']) > 1) {
					$lang_sort = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
					foreach($conf['locales'] as $locale => $lang) {
						if(substr($locale, 0, 2) == $lang_sort) {
							$app_locale = $locale;
						}
					}
				}
			}
		}
	}
}
define('C_HTML_LANG', substr($app_locale, 0, 2));

// gettext
if(function_exists('gettext')) {
	$locale = $app_locale . '.UTF-8';
	putenv("LC_ALL=$locale");
	setlocale(LC_ALL, $locale);
	bindtextdomain($conf['gettext_domain'], $conf['project_path'] . $conf['i18n_path']);
	textdomain($conf['gettext_domain']);
} else {
	require_once $conf['project_path'] . '/donoussa/gettext_missing.php';
}

/**
 * *****************************************************************************
 * LOOKUP DATA
 * *****************************************************************************
 */
$page_i18n = array(
	'home' => array(
		'title' => gettext('Home page'),
		'description' => gettext('Home page'),
	),

	// info
	'about' => array(
		'title' => gettext('About'),
		'description' => gettext('About'),
	),
	'contact' => array(
		'title' => gettext('Contact'),
		'description' => gettext('Contact'),
	),
	'privacy' => array(
		'title' => gettext('Privacy policy'),
		'description' => gettext('Privacy policy'),
	),
	'terms' => array(
		'title' => gettext('Terms and Conditions'),
		'description' => gettext('Terms and Conditions'),
	),

	// usr
	'login' => array(
		'title' => gettext('Login'),
		'description' => gettext('Login'),
	),
	'logoff' => array(
		'title' => gettext('Logoff'),
		'description' => gettext('Logoff'),
	),
	'create_account' => array(
		'title' => gettext('Create account'),
		'description' => gettext('Create account'),
	),
	'email_verify' => array(
		'title' => gettext('Email verify'),
		'description' => gettext('Email verify'),
	),
	'select_language' => array(
		'title' => gettext('Select language'),
		'description' => gettext('Select language'),
	),
	'change_password' => array(
		'title' => gettext('Change password'),
		'description' => gettext('Change password'),
	),
	'recover_password' => array(
		'title' => gettext('Recover password'),
		'description' => gettext('Recover password'),
	),
	'cancel_account' => array(
		'title' => gettext('Cancel account'),
		'description' => gettext('Cancel account'),
	),
	'user_profile' => array(
		'title' => gettext('User profile'),
		'description' => gettext('User profile'),
	),
	'change_username' => array(
		'title' => gettext('Change username'),
		'description' => gettext('Change username'),
	),
	'change_email' => array(
		'title' => gettext('Change email'),
		'description' => gettext('Change email'),
	),
	'user_preferences' => array(
		'title' => gettext('User preferences'),
		'description' => gettext('User preferences'),
	),
	'user_photo' => array(
		'title' => gettext('User photo'),
		'description' => gettext('User photo'),
	),

	//
	'403_access_denied' => array(
		'title' => gettext('Access denied'),
		'description' => gettext('Access denied'),
	),
	'404_page_not_found' => array(
		'title' => gettext('Page not found'),
		'description' => gettext('Page not found'),
	),
	'maintenance' => array(
		'title' => gettext('Maintenance'),
		'description' => gettext('Maintenance'),
	),
);

$genders = array(
	$conf['lk']['genders']['male'] => gettext('male'),
	$conf['lk']['genders']['female'] => gettext('female'),
);

$user_status = array(
	$conf['auth']['user_status']['pending'] => gettext('Pending approval'),
	$conf['auth']['user_status']['active'] => gettext('Active user'),
	$conf['auth']['user_status']['disapproved'] => gettext('User disapproved'),
	$conf['auth']['user_status']['paused'] => gettext('User paused'),
	$conf['auth']['user_status']['removed'] => gettext('User removed'),
	$conf['auth']['user_status']['cancelled'] => gettext('User cancelled'),
);

$user_roles = array(
	$conf['auth']['user_roles']['user'] => gettext('Common user'),
	$conf['auth']['user_roles']['admin'] => gettext('Administrator'),
);

$calendars = array(
	$conf['dt']['calendars']['gregorian'] => gettext('Gregorian calendar'),
	$conf['dt']['calendars']['traditional'] => gettext('Traditional calendar'),
);


/**
 * *****************************************************************************
 * COMMON FUNCTIONS
 * *****************************************************************************
 */

/**
 * Check if expression is positive integer
 *
 * @param $str
 * @return bool
 */
function is_positive_integer($str) {
	return (is_numeric($str) && $str > 0 && $str == round($str));
}


/**
 * Check if expression is valid email address
 *
 * @param $email
 * @return mixed
 */
function is_valid_email($email) {
	return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}


/**
 * Get current time for given timezone, dateformat and (optionally) locale
 *
 * DateTime requires PHP >= 5.2
 * IntlDateFormatter requires PHP 5 >= 5.3.0, PECL intl >= 1.0.0
 * Optional: php intl extension http://php.net/intl
 *
 * 'dateformat' documented: http://www.php.net/manual/en/datetime.createfromformat.php
 * 'intl pattern' documented: http://userguide.icu-project.org/formatparse/datetime
 *
 * intl options example
 * $intl = array(
 *     'locale' => 'en_US',
 *     'datetype' => 0, // FULL
 *     'timetype' => 0, // FULL
 *     'timezone' => null,
 *     'calendar' => 1, // GREGORIAN
 *     'pattern' => 'EEEE, d MMMM yyyy HH:mm:ss'
 * )
 *
 * @param string $tz timezone
 * @param string $df date format
 * @param array $intl international options
 * @param int $error_level
 * @return bool|string
 */
function now($tz, $df, $intl = array(), $error_level = E_USER_ERROR) {
	// check timezone
	if(!in_array($tz, timezone_identifiers_list())) {
		trigger_error(__FUNCTION__ . ': Invalid source timezone ' . $tz, $error_level);
		return false;
	}
	// create datetime object
	$d = new DateTime('now', new DateTimeZone($tz));
	// convert dateformat
	if(extension_loaded('intl') && $intl) {
		try {
			$formatter = new IntlDateFormatter($intl['locale'], $intl['datetype'], $intl['timetype'], $intl['timezone'], $intl['calendar'], $intl['pattern']);
			return $formatter->format($d);
		} catch(Exception $e) {
			trigger_error(__FUNCTION__ . ': Invalid intl options ' . $e->getMessage(), $error_level);
			return false;
		}
	} else {
		return $d->format($df);
	}
}

/**
 * now() simple wrappers
 */
function now_on_server() {
	return now(C_SERVER_TIMEZONE, C_SERVER_DATEFORMAT);
}


function now_on_user($intl = array()) {
	return now($_SESSION['user_timezone'], $_SESSION['user_dateformat'], $intl);
}

/**
 * Convert a date(time) string or timestamp to another format or timezone or locale
 *
 * DateTime::createFromFormat requires PHP >= 5.3
 * IntlDateFormatter requires PHP 5 >= 5.3.0, PECL intl >= 1.0.0
 * Optional: php intl extension http://php.net/intl
 *
 * 'dateformat' documented: http://www.php.net/manual/en/datetime.createfromformat.php
 * 'intl pattern' documented: http://userguide.icu-project.org/formatparse/datetime
 *
 * intl options example
 * $intl = array(
 *     'locale' => 'en_US',
 *     'datetype' => 0, // FULL
 *     'timetype' => 0, // FULL
 *     'timezone' => null,
 *     'calendar' => 1, // GREGORIAN
 *     'pattern' => 'EEEE, d MMMM yyyy HH:mm:ss'
 * )
 *
 * @param string|int $dt datetime string or timestamp integer
 * @param string $tz1 source timezone
 * @param string $df1 source dateformat
 * @param string $tz2 destination timezone
 * @param string $df2 destination dateformat
 * @param array $intl international options
 * @param int $error_level
 * @return string
 */
function date_convert($dt, $tz1, $df1, $tz2, $df2, $intl = array(), $error_level = E_USER_ERROR) {

	$is_timestamp = (gettype($dt) == 'integer');

	if(!$is_timestamp && !$dt) {
		return '';
	}
	if(!in_array($tz1, timezone_identifiers_list())) { // check source timezone
		trigger_error(__FUNCTION__ . ': Invalid source timezone ' . $tz1, $error_level);
		return false;
	}
	if(!in_array($tz2, timezone_identifiers_list())) { // check destination timezone
		trigger_error(__FUNCTION__ . ': Invalid destination timezone ' . $tz2, $error_level);
		return false;
	}

	// create DateTime object
	if($is_timestamp) { // timestamp given
		$d = new DateTime('now', new DateTimeZone($tz1));
		$d->setTimestamp($dt);
	} else {
		$d = DateTime::createFromFormat($df1, $dt, new DateTimeZone($tz1));
	}
	// check source datetime
	$a_err = DateTime::getLastErrors(); // compatibility with php 5.3
	if($d && $a_err['warning_count'] == 0 && $a_err['error_count'] == 0) {
		if($tz2 && $tz1 !== $tz2) {
			// convert timezone
			$d->setTimeZone(new DateTimeZone($tz2));
		}
		// convert dateformat
		if(extension_loaded('intl') && $intl) {
			try {
				$formatter = new IntlDateFormatter($intl['locale'], $intl['datetype'], $intl['timetype'], $intl['timezone'], $intl['calendar'], $intl['pattern']);
				$res = $formatter->format($d);
			} catch(Exception $e) {
				trigger_error(__FUNCTION__ . ': Invalid intl options ' . $e->getMessage(), $error_level);
				return false;
			}
		} else {
			$res = $d->format($df2);
		}

	} else {
		trigger_error(__FUNCTION__ . ': Invalid source datetime ' . $dt . ', ' . $df1 . ' ' . print_r($a_err, true), $error_level);
		return false;
	}
	return $res;
}


/**
 * date_convert simple wrappers ************************************************
 */

/**
 * Convert stored date to user timezone, dateformat and (optionally) locale
 *
 * @param string $d date string
 * @return string
 */
function decode_usr_date($d) {
	global $conf;
	$tz1 = C_SERVER_TIMEZONE;
	$df1 = C_SERVER_DATEFORMAT;
	$tz2 = $_SESSION['user_timezone'];
	$df2 = $conf['dt']['dateformat'][$_SESSION['user_dateformat']]['php_date'];

	$intl = array();
	if(extension_loaded('intl') && array_key_exists('php_date_intl', $conf['dt']['dateformat'][$_SESSION['user_dateformat']])) {
		$intl = array(
			'locale' => $_SESSION['locale'],
			'datetype' => IntlDateFormatter::FULL,
			'timetype' => IntlDateFormatter::FULL,
			'timezone' => $_SESSION['user_timezone'],
			'calendar' => $_SESSION['user_calendar'],
			'pattern' => $conf['dt']['dateformat'][$_SESSION['user_dateformat']]['php_date_intl']
		);
	}
	return date_convert($d, $tz1, $df1, $tz2, $df2, $intl);
}

/**
 * Convert user date (from application interface) to server timezone and format
 *
 * @param string|int $d date string or integer timestamp
 * @return string
 *
 * All dates are stored in varchar(14) columns
 * Y-m-d| will set the year, month and day to the information found in the string to parse, and sets the hour, minute and second to 0.
 * http://www.php.net/manual/en/datetime.createfromformat.php
 * Otherwise, using DateTime::createFromFormat the missing time digits filled with zero, BUT if all times digits are missing current time is returned.
 *
 */
function encode_usr_date($d) {
	global $conf;
	$tz1 = $_SESSION['user_timezone'];
	$df1 = $conf['dt']['dateformat'][$_SESSION['user_dateformat']]['php_date'] . '|';
	$tz2 = C_SERVER_TIMEZONE;
	$df2 = C_SERVER_DATEFORMAT;
	return date_convert($d, $tz1, $df1, $tz2, $df2);
}

/**
 * Convert stored datetime to user timezone, dateformat and (optionally) locale
 *
 * @param $dt
 * @return string
 */
function decode_usr_datetime($dt) {
	global $conf;
	$tz1 = C_SERVER_TIMEZONE;
	$df1 = C_SERVER_DATEFORMAT;
	$tz2 = $_SESSION['user_timezone'];
	$df2 = $conf['dt']['dateformat'][$_SESSION['user_dateformat']]['php_datetime'];

	$intl = array();
	if(extension_loaded('intl') && array_key_exists('php_datetime_intl', $conf['dt']['dateformat'][$_SESSION['user_dateformat']])) {
		$intl = array(
			'locale' => $_SESSION['locale'],
			'datetype' => IntlDateFormatter::FULL,
			'timetype' => IntlDateFormatter::FULL,
			'timezone' => $_SESSION['user_timezone'],
			'calendar' => $_SESSION['user_calendar'],
			'pattern' => $conf['dt']['dateformat'][$_SESSION['user_dateformat']]['php_datetime_intl']
		);
	}
	return date_convert($dt, $tz1, $df1, $tz2, $df2, $intl);
}


/**
 * Check if a string (of any locale) is a valid date(time)
 *
 * DateTime::createFromFormat requires PHP >= 5.3
 *
 * @param string $str_dt
 * @param string $str_dateformat
 * @param string $str_timezone (If timezone is invalid, php will throw an exception)
 * @param array $intl international options
 * @return bool|int
 */
function isValidDateTimeString($str_dt, $str_dateformat, $str_timezone = null, $intl = array()) {
	if(extension_loaded('intl') && $intl) {
		$formatter = new IntlDateFormatter($intl['locale'], $intl['datetype'], $intl['timetype'], $intl['timezone'], $intl['calendar'], $intl['pattern']);
		return $formatter->parse($str_dt);
	} else {
		if($str_timezone) {
			$date = DateTime::createFromFormat($str_dateformat, $str_dt, new DateTimeZone($str_timezone));
		} else {
			$date = DateTime::createFromFormat($str_dateformat, $str_dt);
		}
		$a_err = DateTime::getLastErrors(); // compatibility with php 5.3
		return $date && $a_err['warning_count'] == 0 && $a_err['error_count'] == 0;
	}
}

/**
 * Check if a string is a valid timezone
 *
 * timezone_identifiers_list() requires PHP >= 5.2
 *
 * @param string $timezone
 * @return bool
 */
function isValidTimezone($timezone) {
	return in_array($timezone, timezone_identifiers_list());
}

/**
 * Check if current dateformat requires localization
 *
 *
 * @param null $datefomat
 * @return bool
 */
function dateformat_i18n($datefomat = null) {
	global $conf;
	$datefomat = isset($datefomat) ? $datefomat : $_SESSION['user_dateformat'];
	return array_key_exists('php_datetime_intl', $conf['dt']['dateformat'][$datefomat]);
}

/**
 * @param $str
 * @return int
 */
function isValidMd5($str) {
	return preg_match('/^[a-f0-9]{32}$/', $str);
}

/**
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @param string $email The email address
 * @param int|string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
 * @param boolean $img True to return a complete IMG tag False for just the URL
 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
 * @return String containing either just a URL or a complete image tag
 * @link http://gravatar.com/site/implement/images/php/
 */
function get_gravatar($email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array()) {
	$url = 'http://www.gravatar.com/avatar/';
	$url .= md5(strtolower(trim($email)));
	$url .= "?s=$s&d=$d&r=$r";
	if($img) {
		$url = '<img src="' . $url . '"';
		foreach($atts as $key => $val)
			$url .= ' ' . $key . '="' . $val . '"';
		$url .= ' />';
	}
	return $url;
}

/**
 * @param $subject
 * @param $body
 * @param $mail_to
 * @param array $options
 * @return bool
 */
function send_mail($subject, $body, $mail_to, $options = array()) {

	global $conf;
	global $email_options;

	if(!$conf['email']['send_mail']) {
		return false;
	}

	try {

		// options -------------------------------------------------------------
		$from_email = $conf['email']['from_email'];
		$from_name = encodeMailStr($conf['email']['from_name']);
		$mail_cc = array_key_exists('mail_cc', $options) ? $options['mail_cc'] : null;
		$mail_bcc = array_key_exists('mail_bcc', $options) ? $options['mail_bcc'] : null;

		// Create the mail transport configuration
		$transport = Swift_MailTransport::newInstance();

		// Create the message
		$message = Swift_Message::newInstance();

		foreach($mail_to as $key => $val) {
			$mail_to[$key] = encodeMailStr($val);
		}
		$message->setTo($mail_to);

		if($mail_cc) {
			foreach($mail_cc as $key => $val) {
				$mail_cc[$key] = encodeMailStr($val);
			}
			$message->setCc($mail_cc);
		}

		if($mail_bcc) {
			foreach($mail_bcc as $key => $val) {
				$mail_bcc[$key] = encodeMailStr($val);
			}
			$message->setBcc($mail_bcc);
		}

		if(array_key_exists('subject_prefix', $email_options)) {
			$subject = $email_options['subject_prefix'] . $subject;
		}
		$message->setSubject($subject);

		$body .= array_key_exists('footer', $email_options) ? PHP_EOL . PHP_EOL . PHP_EOL . $email_options['footer'] : '';
		$message->setBody($body);

		$message->setFrom($from_email, $from_name);

		// Send the email
		$mailer = Swift_Mailer::newInstance($transport);
		$mailer->send($message);

	} catch(Exception $e) {
		trigger_error($e->getMessage(), E_USER_NOTICE);
		return false;
	}


	return true;

}

/**
 * @param $str
 * @return string
 *
 * http://stackoverflow.com/questions/14377509/base64-encode-from-name-for-email
 * http://en.wikipedia.org/wiki/Email#Content_encoding
 *
 */
function encodeMailStr($str) {
	return "=?UTF-8?B?" . base64_encode($str) . '?=';
}