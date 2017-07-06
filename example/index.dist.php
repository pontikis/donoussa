<?php
/**
 * Front controller (single point of access) - Donoussa, a micro PHP framework
 */
require_once 'conf/settings.php';
require_once 'conf/init.php';

require_once C_CLASS_DACAPO_PATH;
require_once C_CLASS_DONOUSSA_PATH;
require_once C_JSHRINK_PATH;
require_once C_CSSMIN_PATH;

$ds = new dacapo($conf['db'], $conf['mc']);
$ds->set_option('use_pst', true);
$ds->set_option('error_level', E_USER_NOTICE);

$dependencies = $conf['dependencies'];

$options = array(
	'memcached_keys_prefix' => 'myapp',
	'app_locale' => $app_locale,
);

$app = new donoussa($ds, $dependencies, $options);

if($app->front_controller()) {

	if($app->getRedirect()) {
		header('Location: ' . $app->getRedirect());
		exit;
	} else {
		$section_urls = $app->getSectionUrls();
		switch($app->getRequestType()) {
			case 'regular':
				if($app->getModel())
					include_once $app->getModel();

				if($app->getHeader())
					include_once $app->getHeader();

				include_once $app->getView();

				if($app->getFooter())
					include_once $app->getFooter();

				break;
			case 'ajax':
				include_once $app->getAjaxRequest();
				break;
		}
	}
} else {
	trigger_error($app->getLastError(), E_USER_ERROR);
}