<?php

/**
 * Class donoussa
 *
 * Donoussa is a minimalistic PHP MVC framework, simple and easy to use.
 * It combines FLAT PHP code writing freedom with basic MVC features.
 * It bears the name of the small Greek island Donoussa.
 *
 * Required classes:
 * - Class dacapo
 * @link https://github.com/pontikis/dacapo
 *
 * @author     Christos Pontikis http://pontikis.net
 * @copyright  Christos Pontikis
 * @license    MIT http://opensource.org/licenses/MIT
 * @version    0.9.1 (07 Jul 2017)
 */
class donoussa {

	// arguments - no getters available (3)
	private $ds;
	private $dependencies;
	private $config;

	// getters available (23)
	private $page_id;
	private $package;
	private $request_type;
	private $url_title;
	private $url_title_param;
	private $url_description;
	private $real_url;
	private $section_urls;
	private $is_alias_of;
	private $page_title;
	private $page_description;
	private $page_dependencies_html;
	private $model;
	private $view;
	private $header;
	private $footer;
	private $modal_dialog;
	private $modal_confirm;
	private $ajax_request;
	private $redirect;
	private $last_error;
	private $last_error_code;
	private $log;

	// other vars - no getters available (6)
	private $action_url;
	private $page_properties;
	private $alias_of_ajax_url;
	private $page_depedencies;
	private $model_filename;
	private $view_filename;

	/**
	 * Constructor
	 *
	 * @param dacapo $ds
	 * @param array $dependencies
	 * @param array $config
	 */
	public function __construct(dacapo $ds, $dependencies, $config) {

		// initialize ----------------------------------------------------------
		$this->ds = $ds;
		$this->dependencies = $dependencies;

		// config --------------------------------------------------------------
		$defaults = array(
			't_page_properties' => 'page_properties', // the name of the table which keeps page properties
			't_page_dependencies' => 'page_dependencies', // the name of the table which keeps page dependencies
			't_page_url' => 'page_url', // the name of the table which keeps url(s) per page
			'regular_request' => 1,
			'ajax_request' => 2,
			'bundled_css' => false,
			'bundled_js' => false,
			'bundle_permissions' => false,
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
			'assets_query_string' => '',
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
		$this->package = null;
		$this->request_type = null;
		$this->url_title = null;
		$this->url_title_param = null;
		$this->url_description = null;
		$this->real_url = null;
		$this->section_urls = null;
		$this->page_title = null;
		$this->page_description = null;
		$this->page_properties = null;
		$this->is_alias_of = null;
		$this->alias_of_ajax_url = null;
		$this->page_depedencies = null;
		$this->page_dependencies_html = null;
		$this->model = null;
		$this->view = null;
		$this->model_filename = null;
		$this->view_filename = null;
		$this->header = null;
		$this->footer = null;
		$this->modal_dialog = null;
		$this->modal_confirm = null;
		$this->ajax_request = null;
		$this->redirect = null;
		$this->last_error = null;
		$this->last_error_code = null;
		$this->log = null;
	}


	// public functions - getters ----------------------------------------------
	public function getPageId() {
		return $this->page_id;
	}

	public function getPackage() {
		return $this->package;
	}

	public function getRequestType() {
		return $this->request_type;
	}

	public function getUrlTitle() {
		return $this->url_title;
	}

	public function getUrlTitleParam() {
		return $this->url_title_param;
	}

	public function getUrlDescription() {
		return $this->url_description;
	}

	public function getRealUrl() {
		return $this->real_url;
	}

	public function getSectionUrls() {
		return $this->section_urls;
	}

	public function getPageTitle() {
		return $this->page_title;
	}

	public function getPageDescription() {
		return $this->page_description;
	}

	public function getIsAliasOf() {
		return $this->is_alias_of;
	}

	public function getPageDependenciesHtml() {
		return $this->page_dependencies_html;
	}

	public function getModel() {
		return $this->model;
	}

	public function getView() {
		return $this->view;
	}

	public function getHeader() {
		return $this->header;
	}

	public function getFooter() {
		return $this->footer;
	}

	public function getModalDialog() {
		return $this->modal_dialog;
	}

	public function getModalConfirm() {
		return $this->modal_confirm;
	}

	public function getAjaxRequest() {
		return $this->ajax_request;
	}

	public function getRedirect() {
		return $this->redirect;
	}

	public function getLastError() {
		return $this->last_error;
	}

	public function getLastErrorCode() {
		return $this->last_error_code;
	}

	public function getLog() {
		return $this->log;
	}


	// public functions - main methods -----------------------------------------

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
				$this->last_error = __METHOD__ . ' ' . "{$config['messages'][$this->last_error_code]}: $ds->getLastError()";
				return false;
			}
			$rs = $ds->getData();
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
				$this->last_error = __METHOD__ . ' ' . "{$config['messages'][$this->last_error_code]} ($action_url): $ds->getLastError()";
				return false;
			}
			$url_properties = $ds->getData();

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
				$this->url_title = $url_properties['title'];
				$this->url_title_param = $url_properties['title_param'];
				$this->url_description = $url_properties['description'];
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
				$this->last_error = __METHOD__ . ' ' . "{$config['messages'][$this->last_error_code]} ($action_url): $ds->getLastError()";
				return false;
			}
			$page_properties = $ds->getData();

			if($config['memcached_keys_prefix']) {
				$ds->push_to_memcached($mc_key, $page_properties);
			}

		}
		$this->page_properties = $page_properties;

		if($page_properties) {
			$this->package = $page_properties['package'];
			$this->page_title = $page_properties['title'];
			$this->page_description = $page_properties['description'];
			$this->real_url = $page_properties['real_url'];
			$this->model_filename = $page_properties['model_filename'] ? $page_properties['model_filename'] : $config['model_filename'];
			$this->view_filename = $page_properties['view_filename'] ? $page_properties['view_filename'] : $config['view_filename'];
			$this->modal_dialog = $page_properties['modal_dialog'];
			$this->modal_confirm = $page_properties['modal_confirm'];
			$this->is_alias_of = $page_properties['is_alias_of'];
		}

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
					$this->last_error = __METHOD__ . ' ' . "{$config['messages'][$this->last_error_code]} ($action_url): $ds->getLastError()";
					return false;
				}
				$page_dependencies_tmp = $ds->getData();

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
			$this->page_dependencies_html = $this->_create_page_dependencies_html();

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

			$model = C_PROJECT_PATH . $this->real_url . '/' . $this->model_filename;
			$view = C_PROJECT_PATH . $this->real_url . '/' . $this->view_filename;
			$header = $page_properties['header'] ? C_PROJECT_PATH . $page_properties['header'] : null;
			$footer = $page_properties['footer'] ? C_PROJECT_PATH . $page_properties['footer'] : null;

			if($model) {
				if(file_exists($model) && is_file($model)) {
					$this->model = $model;
				}
			}

			if($header) {
				if(file_exists($header) && is_file($header)) {
					$this->header = $header;
				} else {
					$this->last_error_code = 'invalid_header_file';
					$this->last_error = __METHOD__ . ' ' . "{$config['messages'][$this->last_error_code]} ($header)";
					return false;
				}
			}

			if($view) {
				if(file_exists($view) && is_file($view)) {
					$this->view = $view;
				} else {
					$this->last_error_code = 'invalid_view_file';
					$this->last_error = __METHOD__ . ' ' . "{$config['messages'][$this->last_error_code]} ($view)";
					return false;
				}
			}

			if($footer) {
				if(file_exists($footer) && is_file($footer)) {
					$this->footer = $footer;
				} else {
					$this->last_error_code = 'invalid_footer_file';
					$this->last_error = __METHOD__ . ' ' . "{$config['messages'][$this->last_error_code]} ($footer)";
					return false;
				}
			}

			// LOG LINE
			if($config['keep_log']) {
				if($this->is_alias_of) {
					$this->log = 'REGULAR REQUEST (alias of ' . $this->is_alias_of . '): ' . $action_url;
				} else {
					$this->log = 'REGULAR REQUEST: ' . $action_url;
				}

			}

		} else if($this->request_type == 'ajax') {

			if(!$this->_is_ajax()) {
				$this->last_error_code = 'direct_access_of_ajax_request';
				$this->last_error = __METHOD__ . ' ' . "{$config['messages'][$this->last_error_code]} ($action_url)";
				return false;
			}

			if($page_properties['auth_required']) {

				// user is not authenticated
				if((!isset($_SESSION['user_id'])) || ($_SESSION['user_id'] <= '0')) {
					$this->last_error_code = 'user_authorization_needed';
					$this->last_error = __METHOD__ . ' ' . "{$config['messages'][$this->last_error_code]} ($action_url)";
					return false;
				} else {

					// check access according to user role
					if($page_properties['roles']) {
						$a_roles = explode(',', $page_properties['roles']);
						if(!in_array($_SESSION['user_role_id'], $a_roles)) {
							$this->last_error_code = 'access_denied';
							$this->last_error = __METHOD__ . ' ' . "{$config['messages'][$this->last_error_code]} ($action_url)";
							return false;
						}
					}

				}
			}

			// CSRF protection
			/*			if(session_id() != '') {
							if(array_key_exists('HTTP_X_CSRF_TOKEN', $_SERVER)) {
								if(sha1(session_id() . $this->page_id) !== $_SERVER['HTTP_X_CSRF_TOKEN']) {
									$this->last_error_code = 'csrf_token_not_match';
									$this->last_error = __METHOD__ . ' ' . "{$config['messages'][$this->last_error_code]} ($action_url)";
									return false;
								}
							}
						}*/

			if($this->is_alias_of) {
				$a_url = explode('/', $action_url);
				// remove first element (the page_id of alias, which put to make unique the alias url)
				array_splice($a_url, 0,2);
				$this->alias_of_ajax_url = '/' . implode('/', $a_url);
				$this->ajax_request = C_PROJECT_PATH . $this->alias_of_ajax_url;
			} else {
				$this->ajax_request = C_PROJECT_PATH . $action_url;
			}

			if(!file_exists($this->ajax_request) || !is_file($this->ajax_request)) {
				$this->last_error_code = 'invalid_ajax_request';
				$this->last_error = __METHOD__ . ' ' . "{$config['messages'][$this->last_error_code]} ($action_url)";
				return false;
			}

			// LOG LINE
			if($config['keep_log']) {
				if($this->is_alias_of) {
					$this->log = 'AJAX REQUEST: ' . $action_url . ' (alias of ' . $this->alias_of_ajax_url . ')';
				} else {
					$this->log = 'AJAX REQUEST: ' . $action_url;
				}
			}

		} else {

			// LOG LINE
			if($config['keep_log']) {
				$this->log = 'UNKNOWN REQUEST: ' . $action_url;
			}

			if($this->_is_ajax()) {
				$this->last_error_code = 'invalid_ajax_request';
				$this->last_error = __METHOD__ . ' ' . "{$config['messages'][$this->last_error_code]} ($action_url)";
				return false;
			} else {
				$this->redirect = C_PROJECT_HOST . $section_urls[$config['page_id_404_page_not_found']];
				return true;
			}

		}

		return true;
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
			$this->last_error = __METHOD__ . ' ' . "{$config['messages'][$this->last_error_code]}: $ds->getLastError()";
			return false;
		}
		$rs = $ds->getData();
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
			$this->last_error = __METHOD__ . ' ' . "{$config['messages'][$this->last_error_code]}: $ds->getLastError()";
			return false;
		}
		$rs = $ds->getData();
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

	// private functions -------------------------------------------------------

	/**
	 * @return string
	 */
	private function _create_page_dependencies_html() {

		$config = $this->config;
		$dependencies = $this->dependencies;
		$page_properties = $this->page_properties;
		$html = '';

		$now = now_on_server();

		$bundled_css_basename = $this->page_id . '_' . $config['app_locale'] . '.css';
		$bundled_css_filename = C_PROJECT_PATH . $config['bundled_css'] . $bundled_css_basename;
		$bundled_css_exists = file_exists($bundled_css_filename);
		$bundled_css_contents = '/*' . PHP_EOL . "BUNDLED AT $now " . str_repeat("#", 54) . PHP_EOL . '*/';

		$bundled_js_basename = $this->page_id . '_' . $config['app_locale'] . '.js';
		$bundled_js_filename = C_PROJECT_PATH . $config['bundled_js'] . $bundled_js_basename;
		$bundled_js_exists = file_exists($bundled_js_filename);
		$bundled_js_contents = '/*' . PHP_EOL . "BUNDLED AT $now " . str_repeat("#", 54) . PHP_EOL . '*/';

		foreach($this->page_depedencies as $key => $page_dep) {

			$dep = $dependencies[$key];
			$dep_default = $dep['default'];

			switch($dep['type']) {
				case 'css':
					$exclude_css_from_bundle = !$config['bundled_css'] || array_key_exists('exclude_from_bundle', $dep) && $dep['exclude_from_bundle'];

					$elem_id = array_key_exists('element_id', $dep) ? 'id="' . $dep['element_id'] . '" ' : '';
					if($page_dep == 1) {
						if(array_key_exists('session', $dep) && isset($_SESSION[$dep['session']['variable']])) {
							$href = $dep['session']['values'][$_SESSION[$dep['session']['variable']]];
							$href = (filter_var($href, FILTER_VALIDATE_URL) === FALSE) ? C_LIB_FRONT_END_URL . $href : $href;
						} else {
							if($key == 'page_css') {
								$href = C_PROJECT_URL . $page_properties['real_url'] . '/' . $dep_default . $config['assets_query_string'];
							} else if($key == 'common_css') {
								$href = C_PROJECT_URL . $dep_default . $config['assets_query_string'];
							} else {
								$href = (filter_var($dep_default, FILTER_VALIDATE_URL) === FALSE) ? C_LIB_FRONT_END_URL . $dep_default : $dep_default;
							}
						}
					} else {
						$href = $page_dep;
					}
					if($exclude_css_from_bundle) {
						$html .= '<link ' . $elem_id . 'rel="stylesheet" type="text/css" href="' . $href . '">' . PHP_EOL;
					} else {
						if(!$bundled_css_exists) {
							$dep_filename = C_PROJECT_PATH . $config['bundled_css'] . $page_dep;
							$log_name = $page_dep;
							if($page_dep == 1) {
								if($key == 'page_css') {
									$dep_filename = C_PROJECT_PATH . $page_properties['real_url'] . '/' . $dep_default;
									$log_name = $page_properties['real_url'] . '/' . $dep_default;
								} else if($key == 'common_css') {
									$dep_filename = C_PROJECT_PATH . $dep_default;
									$log_name = $dep_default;
								} else {
									$dep_filename = C_PROJECT_PATH . C_LIB_FRONT_END_BASE_URL . $dep_default;
									$log_name = $dep_default;
								}
							}
							$bundled_css_contents .= PHP_EOL . PHP_EOL . '/* ' . $log_name . ' ' . str_repeat("#", max(0, 73 - strlen($log_name))) . ' */' . PHP_EOL;
							if(array_key_exists('minify', $dep) && $dep['minify']) {
								$bundled_css_contents .= CssMin::minify(file_get_contents($dep_filename));
							} else {
								$bundled_css_contents .= file_get_contents($dep_filename);
							}
						}
					}
					break;

				case 'js':

					$exclude_js_from_bundle = !$config['bundled_js'] || array_key_exists('exclude_from_bundle', $dep) && $dep['exclude_from_bundle'];

					if($page_dep == 1) {

						if(array_key_exists('locale', $dep)) {
							if(array_key_exists($config['app_locale'], $dep['locale'])) {
								$src = $dep['locale'][$config['app_locale']];
							} else {
								$src = $dep_default ? $dep_default : '';
							}
							if($src) {
								$src = (filter_var($src, FILTER_VALIDATE_URL) === FALSE) ? C_LIB_FRONT_END_URL . $src : $src;
							}
						} else {
							if($key == 'page_js') {
								$src = C_PROJECT_URL . $page_properties['real_url'] . '/' . $dep_default . $config['assets_query_string'];
							} else if($key == 'common_js') {
								$src = C_PROJECT_URL . $dep_default . $config['assets_query_string'];
							} else {
								$src = (filter_var($dep_default, FILTER_VALIDATE_URL) === FALSE) ? C_LIB_FRONT_END_URL . $dep_default : $dep_default;
							}
						}
					} else {
						$src = $page_dep;
					}
					if($exclude_js_from_bundle) {
						if($src) {
							if(array_key_exists('condition', $dep)) {
								$html .= $dep['condition']['start'] . PHP_EOL;
							}
							$html .= '<script src="' . $src . '" type="text/javascript"></script>' . PHP_EOL;
							if(array_key_exists('condition', $dep)) {
								$html .= $dep['condition']['end'] . PHP_EOL;
							}
						}
					} else {
						if(!$bundled_js_exists) {

							$dep_filename = C_PROJECT_PATH . $config['bundled_js'] . $page_dep;
							$log_name = $page_dep;
							if($page_dep == 1) {
								if(array_key_exists('locale', $dep)) {
									if(array_key_exists($config['app_locale'], $dep['locale'])) {
										$dep_filename = C_PROJECT_PATH . C_LIB_FRONT_END_BASE_URL . $dep['locale'][$config['app_locale']];
										$log_name = $dep['locale'][$config['app_locale']];
									} else {
										$dep_filename = $dep_default ? C_PROJECT_PATH . C_LIB_FRONT_END_BASE_URL . $dep_default : '';
										$log_name = $dep_default;
									}
								} else {
									if($key == 'page_js') {
										$dep_filename = C_PROJECT_PATH . $page_properties['real_url'] . '/' . $dep_default;
										$log_name = $page_properties['real_url'] . '/' . $dep_default;
									} else if($key == 'common_js') {
										$dep_filename = $dep_default;
										$log_name = $dep_default;
									} else {
										$dep_filename = C_PROJECT_PATH . C_LIB_FRONT_END_BASE_URL . $dep_default;
										$log_name = $dep_default;
									}
								}
							}

							if($dep_filename) {
								$bundled_js_contents .= PHP_EOL . PHP_EOL . '/* ' . $log_name . ' ' . str_repeat("#", max(0, 73 - strlen($log_name))) . ' */' . PHP_EOL;
								if(array_key_exists('minify', $dep) && $dep['minify']) {
									$bundled_js_contents .= \JShrink\Minifier::minify(file_get_contents($dep_filename));
								} else {
									$bundled_js_contents .= file_get_contents($dep_filename);
								}
							}

						}
					}

					break;
			}

		}


		if($config['bundled_css']) {
			if(!$bundled_css_exists) {
				file_put_contents($bundled_css_filename, $bundled_css_contents);
				if($config['bundle_permissions']) {
					chmod($bundled_css_filename, $config['bundle_permissions']);
				}

			}
			$html .= '<link rel="stylesheet" type="text/css" href="' . C_PROJECT_URL . $config['bundled_css'] . $bundled_css_basename . $config['assets_query_string'] . '">' . PHP_EOL;
		}

		if($config['bundled_js']) {
			if(!$bundled_js_exists) {
				file_put_contents($bundled_js_filename, $bundled_js_contents);
				if($config['bundle_permissions']) {
					chmod($bundled_js_filename, $config['bundle_permissions']);
				}
			}
			$html .= '<script src="' . C_PROJECT_URL . $config['bundled_js'] . $bundled_js_basename . $config['assets_query_string'] . '" type="text/javascript"></script>' . PHP_EOL;
		}

		return $html;

	}


	/**
	 * @return bool
	 */
	private function _is_ajax() {
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND
			strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
	}

}