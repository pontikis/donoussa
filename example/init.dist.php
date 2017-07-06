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
define('C_CLASS_DACAPO_PATH', C_LIB_PHP_PATH . $conf['class']['class_dacapo_path']);
define('C_CLASS_DONOUSSA_PATH', C_LIB_PHP_PATH . $conf['class']['class_donoussa_path']);
define('C_CLASS_ITHACA_PATH', C_LIB_PHP_PATH . $conf['class']['class_ithaca_path']);

define('C_PHPASS_PATH', C_LIB_PHP_PATH . $conf['class']['class_phpass_path']);

define('C_JSHRINK_PATH', C_LIB_PHP_PATH . $conf['class']['JShrink']);
define('C_CSSMIN_PATH', C_LIB_PHP_PATH . $conf['class']['cssmin']);

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

