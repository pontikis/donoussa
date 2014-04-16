<?php

/**
 * Donoussa - micro PHP framework
 *
 * Donoussa is a minimalistic PHP MVC framework, simple and easy to use.
 * It combines FLAT PHP code writing freedom with basic MVC features.
 * It bears the name of the small Greek island Donoussa.
 *
 * @author     Christos Pontikis http://pontikis.net
 * @copyright  Christos Pontikis
 * @license    MIT http://opensource.org/licenses/MIT
 * @version    0.8.1 (16 Apr 2014)
 */
class donoussa {

	/**
	 * Constructor
	 *
	 * @param data_source $ds
	 * @param array $dependencies
	 * @param array $config
	 */
	public function __construct(data_source $ds, $dependencies, $config) {

		// initialize ----------------------------------------------------------
		$this->version = '0.8.1';
		$this->ds = $ds;
		$this->dependencies = $dependencies;

		// config --------------------------------------------------------------
		$defaults = array(
			't_page_properties' => 'page_properties', // the name of the table which keeps page properties
			't_page_dependencies' => 'page_dependencies', // the name of the table which keeps page dependencies
			't_page_url' => 'page_url', // the name of the table which keeps url(s) per page
			'regular_request' => 1,
			'ajax_request' => 2,
			'model_filename' => 'index.php',
			'view_filename' => 'index.view.php',
			'app_locale' => 'en_US',
			'memcached_keys_prefix' => '',
			'multilingual' => false,
			'force_select_language' => false,
			'cookie_app_language' => '',
			'page_id_login' => 'login',
			'page_id_change_password' => 'change_password',
			'page_id_select_language' => 'select_language',
			'page_id_404_page_not_found' => '404_page_not_found',
			'page_id_403_access_denied' => '403_access_denied',
			'page_id_maintenance' => 'maintenance',
			'maintenance_mode' => false,
			'keep_log' => false,
			'messages' => array(
				'error_retrieving_section_urls' => 'Error retrieving section URLs',
				'error_retrieving_url_properties' => 'Error retrieving URL properties',
				'error_retrieving_page_properties' => 'Error retrieving page properties',
				'error_retrieving_page_dependencies' => 'Error retrieving page dependencies',
				'error_retrieving_page_ids' => 'Error retrieving page ids',
				'invalid_header_file' => 'Invalid header file',
				'invalid_view_file' => 'Invalid view file',
				'invalid_footer_file' => 'Invalid footer file',
				'invalid_ajax_request' => 'Invalid ajax request',
				// the following messages are not informative deliberately
				'direct_access_of_ajax_request' => 'Invalid Request',
				'user_authorization_needed' => 'Invalid Request',
				'access_denied' => 'Invalid Request',
				'csrf_token_not_match' => 'Invalid Request'
			)
		);
		$this->config = array_merge($defaults, $config);

		$this->action_url = null;
		$this->page_id = null;
		$this->request_type = null;
		$this->real_url = null;
		$this->section_urls = null;
		$this->page_title = null;
		$this->page_description = null;
		$this->page_properties = null;
		$this->page_depedencies = null;
		$this->page_depedencies_html = null;
		$this->model = null;
		$this->view = null;
		$this->header = null;
		$this->footer = null;
		$this->ajax_request = null;
		$this->redirect = null;
		$this->last_error = null;
		$this->last_error_code = null;
		$this->log = null;
	}

	/**
	 * Donoussa - micro PHP framework
	 * Front controller (single point of access)
	 * It uses a Dynamic Lookup (using database or memcached) Invocation
	 */
	public function front_controller() {

		$ds = $this->ds;
		$dependencies = $this->dependencies;
		$config = $this->config;

		// GET PAGES WITH UNIQUE URL (SECTIONS) --------------------------------
		$section_urls = array();
		$mc_key = $config['memcached_keys_prefix'] . '_' . 'section_urls';
		if($config['memcached_keys_prefix']) {
			$section_urls = $ds->pull_from_memcached($mc_key);
		}
		if(!$section_urls) {
			$section_urls = array();
			$sql = "SELECT u.page_id as section_id, u.url " .
				"FROM {$config['t_page_url']} u INNER JOIN {$config['t_page_properties']} p ON p.page_id = u.page_id " .
				"WHERE p.unique_url=1 AND u.request_type = {$config['regular_request']}";
			$bind_params = array();
			$res = $ds->select($sql, $bind_params);
			if(!$res) {
				$this->last_error_code = 'error_retrieving_section_urls';
				$this->last_error = __METHOD__ . ' ' . "{$config['messages'][$this->last_error_code]}: $ds->last_error";
				return false;
			}
			$rs = $ds->data;
			foreach($rs as $row) {
				$section_urls[$row['section_id']] = C_PROJECT_URL . $row['url'];
			}

			if($config['memcached_keys_prefix']) {
				$ds->push_to_memcached($mc_key, $section_urls);
			}
		}
		$this->section_urls = $section_urls;

		// GET CURRENT URL (action_url) ----------------------------------------
		$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		$duri = urldecode($uri);
		$action_url = substr($duri, strlen(C_PROJECT_URL));
		$this->action_url = $action_url;

		// initialize
		$url_properties = array();
		$page_properties = array();
		$page_dependencies = array();

		// GET URL PROPERTIES --------------------------------------------------
		$mc_key = $config['memcached_keys_prefix'] . '_' . 'url_' . sha1($action_url);
		if($config['memcached_keys_prefix']) {
			$url_properties = $ds->pull_from_memcached($mc_key);
		}
		if(!$url_properties) {
			$sql = "SELECT * FROM {$config['t_page_url']} WHERE url = ?";
			$bind_params = array($action_url);
			$query_options = array("get_row" => true);
			$res = $ds->select($sql, $bind_params, $query_options);
			if(!$res) {
				$this->last_error_code = 'error_retrieving_url_properties';
				$this->last_error = __METHOD__ . ' ' . "{$config['messages'][$this->last_error_code]} ($action_url): $ds->last_error";
				return false;
			}
			$url_properties = $ds->data;

			if($config['memcached_keys_prefix']) {
				// do not cash invalid URLs
				if(array_key_exists('page_id', $url_properties)) {
					$ds->push_to_memcached($mc_key, $url_properties);
				}
			}
		}

		if(array_key_exists('page_id', $url_properties)) {

			$this->page_id = $url_properties['page_id'];

			if($url_properties['request_type'] == $config['regular_request']) {
				$this->request_type = "regular";
			}
			if($url_properties['request_type'] == $config['ajax_request']) {
				$this->request_type = "ajax";
			}
		}


		// GET PAGE PROPERTIES -------------------------------------------------
		$page_id = $this->page_id;

		$mc_key = $config['memcached_keys_prefix'] . '_' . 'page_prop_' . sha1($page_id);
		if($config['memcached_keys_prefix']) {
			$page_properties = $ds->pull_from_memcached($mc_key);
		}

		if(!$page_properties) {
			$sql = "SELECT * FROM {$config['t_page_properties']} WHERE page_id = ?";
			$bind_params = array($page_id);
			$query_options = array("get_row" => true);
			$res = $ds->select($sql, $bind_params, $query_options);
			if(!$res) {
				$this->last_error_code = 'error_retrieving_page_properties';
				$this->last_error = __METHOD__ . ' ' . "{$config['messages'][$this->last_error_code]} ($action_url): $ds->last_error";
				return false;
			}
			$page_properties = $ds->data;

			if($config['memcached_keys_prefix']) {
				$ds->push_to_memcached($mc_key, $page_properties);
			}

		}
		$this->page_properties = $page_properties;

		$this->page_title = $page_properties['title'];
		$this->page_description = $page_properties['description'];
		$this->real_url = $page_properties['real_url'];


		if($this->request_type == "regular") {

			// GET PAGE DEPENDENCIES (CSS + JS) --------------------------------
			$mc_key = $config['memcached_keys_prefix'] . '_' . 'page_dep_' . sha1($page_id) . '_' . $config['app_locale'];
			if($config['memcached_keys_prefix']) {
				$page_dependencies = $ds->pull_from_memcached($mc_key);
			}

			if(!$page_dependencies) {
				$sql = "SELECT * FROM {$config['t_page_dependencies']} WHERE page_id = ?";
				$bind_params = array($page_id);
				$query_options = array("get_row" => true);
				$res = $ds->select($sql, $bind_params, $query_options);
				if(!$res) {
					$this->last_error_code = 'error_retrieving_page_dependencies';
					$this->last_error = __METHOD__ . ' ' . "{$config['messages'][$this->last_error_code]} ($action_url): $ds->last_error";
					return false;
				}
				$page_dependencies_tmp = $ds->data;

				// remove null dependencies
				unset($page_dependencies_tmp['id']);
				unset($page_dependencies_tmp['page_id']);
				foreach($page_dependencies_tmp as $key => $val) {
					if(!$val) {
						unset($page_dependencies_tmp[$key]);
					}
				}

				// sort according to default load order
				$page_dependencies = array();
				foreach($dependencies as $key => $dep) {
					if(array_key_exists($key, $page_dependencies_tmp)) {
						$page_dependencies[$key] = $page_dependencies_tmp[$key];
					}
				}

				if($config['memcached_keys_prefix']) {
					$ds->push_to_memcached($mc_key, $page_dependencies);
				}

			}
			$this->page_depedencies = $page_dependencies;

			// CREATE DEPENDENCIES (CSS + JS) HTML
			$this->page_depedencies_html = $this->create_page_dependencies_html();

		}

		// ---------------------------------------------------------------------
		// MAIN CONTROLLER -----------------------------------------------------
		// ---------------------------------------------------------------------
		if($config['maintenance_mode']) {
			if(C_PROJECT_URL . $action_url != $section_urls[$config['page_id_maintenance']]) {
				$this->redirect = C_PROJECT_HOST . $section_urls[$config['page_id_maintenance']];
				return true;
			}
		}

		if($this->request_type == 'regular') {

			if($config['multilingual'] && $config['force_select_language']) {
				if($config['cookie_app_language']) {
					if(!isset($_COOKIE[$config['cookie_app_language']])) {
						if(C_PROJECT_URL . $action_url != $section_urls[$config['page_id_select_language']]) {
							$this->redirect = C_PROJECT_HOST . $section_urls[$config['page_id_select_language']];
							return true;
						}
					}
				}
			}

			if($page_properties['auth_required']) {

				// user is not authenticated
				if((!isset($_SESSION['user_id'])) || ($_SESSION['user_id'] <= '0')) {
					$_SESSION['session_call_url'] = C_PROJECT_HOST . $_SERVER['REQUEST_URI'];

					$this->redirect = C_PROJECT_HOST . $section_urls[$config['page_id_login']];
					return true;

				} else {

					if($_SESSION['password_reset'] == 1) {
						if(C_PROJECT_URL . $action_url != $section_urls[$config['page_id_change_password']]) {
							$this->redirect = C_PROJECT_HOST . $section_urls[$config['page_id_change_password']];
							return true;
						}
					}

					// check access according to user role
					if($page_properties['roles']) {
						$a_roles = explode(',', $page_properties['roles']);
						if(!in_array($_SESSION['user_role_id'], $a_roles)) {
							$this->redirect = C_PROJECT_HOST . $section_urls[$config['page_id_403_access_denied']];
							return true;
						}
					}
				}
			}

			$model = C_PROJECT_PATH . $page_properties['real_url'] . '/' . $config['model_filename'];
			$view = C_PROJECT_PATH . $page_properties['real_url'] . '/' . $config['view_filename'];
			$header = C_PROJECT_PATH . $page_properties['header'];
			$footer = C_PROJECT_PATH . $page_properties['footer'];

			if(file_exists($model) && is_file($model)) {
				$this->model = $model;
			}

			if(file_exists($header) && is_file($header)) {
				$this->header = $header;
			} else {
				$this->last_error_code = 'invalid_header_file';
				$this->last_error = __METHOD__ . ' ' . "{$config['messages'][$this->last_error_code]} ($header): $ds->last_error";
				return false;
			}

			if(file_exists($view) && is_file($view)) {
				$this->view = $view;
			} else {
				$this->last_error_code = 'invalid_view_file';
				$this->last_error = __METHOD__ . ' ' . "{$config['messages'][$this->last_error_code]} ($view): $ds->last_error";
				return false;
			}

			if(file_exists($footer) && is_file($footer)) {
				$this->footer = $footer;
			} else {
				$this->last_error_code = 'invalid_footer_file';
				$this->last_error = __METHOD__ . ' ' . "{$config['messages'][$this->last_error_code]} ($footer): $ds->last_error";
				return false;
			}

			// AJAX CSRF PROTECTION
			if(session_id() != '') {
				$_SESSION['X-CSRF-Token'] = md5(uniqid(mt_rand(), true));
			}

			// LOG LINE
			if($config['keep_log']) {
				$this->log = 'REGULAR REQUEST: ' . $action_url;
			}

		} else if($this->request_type == 'ajax') {

			if(!$this->is_ajax()) {
				$this->last_error_code = 'direct_access_of_ajax_request';
				$this->last_error = __METHOD__ . ' ' . "{$config['messages'][$this->last_error_code]} ($action_url): $ds->last_error";
				return false;
			}

			if($page_properties['auth_required']) {

				// user is not authenticated
				if((!isset($_SESSION['user_id'])) || ($_SESSION['user_id'] <= '0')) {
					$this->last_error_code = 'user_authorization_needed';
					$this->last_error = __METHOD__ . ' ' . "{$config['messages'][$this->last_error_code]} ($action_url): $ds->last_error";
					return false;
				} else {

					// check access according to user role
					if($page_properties['roles']) {
						$a_roles = explode(',', $page_properties['roles']);
						if(!in_array($_SESSION['user_role_id'], $a_roles)) {
							$this->last_error_code = 'access_denied';
							$this->last_error = __METHOD__ . ' ' . "{$config['messages'][$this->last_error_code]} ($action_url): $ds->last_error";
							return false;
						}
					}

				}
			}

			// CSRF protection
			if(session_id() != '') {
				if($_SESSION['X-CSRF-Token'] !== $_SERVER['HTTP_X_CSRF_TOKEN']) {
					$this->last_error_code = 'csrf_token_not_match';
					$this->last_error = __METHOD__ . ' ' . "{$config['messages'][$this->last_error_code]} ($action_url): $ds->last_error";
					return false;
				}
			}

			$this->ajax_request = C_PROJECT_PATH . $action_url;

			if(!file_exists($this->ajax_request) || !is_file($this->ajax_request)) {
				$this->last_error_code = 'invalid_ajax_request';
				$this->last_error = __METHOD__ . ' ' . "{$config['messages'][$this->last_error_code]} ($action_url): $ds->last_error";
				return false;
			}

			// LOG LINE
			if($config['keep_log']) {
				$this->log = 'AJAX REQUEST: ' . $action_url;
			}

		} else {
			$this->redirect = C_PROJECT_HOST . $section_urls[$config['page_id_404_page_not_found']];
			return true;
		}

		return true;
	}


	/**
	 * @return string
	 */
	private function create_page_dependencies_html() {

		$config = $this->config;
		$dependencies = $this->dependencies;
		$page_properties = $this->page_properties;
		$html = '';

		foreach($this->page_depedencies as $key => $page_dep) {

			$dep = $dependencies[$key];
			$dep_default = $dep['default'];

			switch($dep['type']) {
				case 'css':
					$elem_id = array_key_exists('element_id', $dep) ? 'id="' . $dep['element_id'] . '" ' : '';
					if($page_dep == 1) {
						if(array_key_exists('session', $dep) && isset($_SESSION[$dep['session']['variable']])) {
							$href = $dep['session']['values'][$_SESSION[$dep['session']['variable']]];
							$href = (filter_var($href, FILTER_VALIDATE_URL) === FALSE) ? C_LIB_FRONT_END_URL . $href : $href;
						} else {
							if($key == 'page_css') {
								$href = C_PROJECT_URL . $page_properties['real_url'] . '/' . $dep_default;
							} else if($key == 'common_css') {
								$href = C_PROJECT_URL . $dep_default;
							} else {
								$href = (filter_var($dep_default, FILTER_VALIDATE_URL) === FALSE) ? C_LIB_FRONT_END_URL . $dep_default : $dep_default;
							}
						}
					} else {
						$href = $page_dep;
					}
					$html .= '<link ' . $elem_id . 'rel="stylesheet" type="text/css" href="' . $href . '">"' . PHP_EOL;
					break;

				case 'js':
					if($page_dep == 1) {

						if(array_key_exists('locale', $dep)) {
							if(array_key_exists($config['app_locale'], $dep['locale'])) {
								$src = $dep['locale'][$config['app_locale']];
							} else {
								$src = $dep_default ? $dep_default : '';
							}
							$src = (filter_var($src, FILTER_VALIDATE_URL) === FALSE) ? C_LIB_FRONT_END_URL . $src : $src;
						} else {
							if($key == 'page_js') {
								$src = C_PROJECT_URL . $page_properties['real_url'] . '/' . $dep_default;
							} else if($key == 'common_js') {
								$src = C_PROJECT_URL . $dep_default;
							} else {
								$src = (filter_var($dep_default, FILTER_VALIDATE_URL) === FALSE) ? C_LIB_FRONT_END_URL . $dep_default : $dep_default;
							}
						}
					} else {
						$src = $page_dep;
					}

					if($src) {
						if(array_key_exists('condition', $dep)) {
							$html .= $dep['condition']['start'] . PHP_EOL;
						}
						$html .= '<script src="' . $src . '" type="text/javascript"></script>' . PHP_EOL;
						if(array_key_exists('condition', $dep)) {
							$html .= $dep['condition']['end'] . PHP_EOL;
						}
					}
					break;
			}

		}

		return $html;

	}


	/**
	 * @param $a_locales
	 * @return bool
	 */
	public function clear_front_controller_cache($a_locales) {

		$ds = $this->ds;
		$config = $this->config;

		// get URLs
		$urls = array();
		$sql = "SELECT url FROM {$config['t_page_url']}";
		$bind_params = array();
		$res = $ds->select($sql, $bind_params);
		if(!$res) {
			$this->last_error_code = 'error_retrieving_section_urls';
			$this->last_error = __METHOD__ . ' ' . "{$config['messages'][$this->last_error_code]}: $ds->last_error";
			return false;
		}
		$rs = $ds->data;
		foreach($rs as $row) {
			$urls[] = $row['url'];
		}

		// get page_ids
		$page_ids = array();
		$sql = "SELECT page_id FROM {$config['t_page_properties']}";
		$bind_params = array();
		$res = $ds->select($sql, $bind_params);
		if(!$res) {
			$this->last_error_code = 'error_retrieving_page_ids';
			$this->last_error = __METHOD__ . ' ' . "{$config['messages'][$this->last_error_code]}: $ds->last_error";
			return false;
		}
		$rs = $ds->data;
		foreach($rs as $row) {
			$page_ids[] = $row['page_id'];
		}

		// ---------------------------------------------------------------------
		$mc_key = $config['memcached_keys_prefix'] . '_' . 'section_urls';
		$ds->delete_from_memcached($mc_key);

		// ---------------------------------------------------------------------
		foreach($urls as $url) {
			$mc_key = $config['memcached_keys_prefix'] . '_' . 'url_' . sha1($url);
			$ds->delete_from_memcached($mc_key);
		}

		// ---------------------------------------------------------------------
		foreach($page_ids as $page_id) {

			$mc_key = $config['memcached_keys_prefix'] . '_' . 'page_prop_' . sha1($page_id);
			$ds->delete_from_memcached($mc_key);

			foreach($a_locales as $locale) {
				$mc_key = $config['memcached_keys_prefix'] . '_' . 'page_dep_' . sha1($page_id) . '_' . $locale;
				$ds->delete_from_memcached($mc_key);
			}
		}

		return true;

	}

	/**
	 * @return bool
	 */
	private function is_ajax() {
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND
		strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
	}

}