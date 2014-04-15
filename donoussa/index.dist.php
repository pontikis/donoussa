<?php
/**
 * Front controller (single point of access) - Donoussa, a micro PHP framework
 */
require_once 'conf/settings.php';
require_once 'conf/init.php';

require_once C_CLASS_DONOUSSA_PATH;
require_once C_CLASS_DATASOURCE_PATH;

$ds = new data_source($conf['db'], $conf['mc']);
$ds->set_option('use_pst', true);
$ds->set_option('error_level', E_USER_NOTICE);

$dependencies = $conf['dependencies'];

$options = array(
	'memcached_keys_prefix' => 'myapp',
	'app_locale' => $app_locale,
);

$app = new donoussa($ds, $dependencies, $options);
if($app->front_controller()) {

	if($app->redirect) {
		header('Location: ' . $app->redirect);
		exit;
	} else {
		switch($app->request_type) {
			case 'regular':
				$section_urls = $app->section_urls;
				if($app->model)
					include_once $app->model;

				if($app->header)
					include_once $app->header;

				include_once $app->view;

				if($app->footer)
					include_once $app->footer;

				break;
			case 'ajax':
				include_once $app->ajax_request;
				break;
		}
	}
} else {
	trigger_error($app->last_error, E_USER_ERROR);
}