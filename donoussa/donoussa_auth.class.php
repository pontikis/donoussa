<?php

/**
 * Class auth
 * User authorization (create account, login etc)
 *
 * Required classes:
 * - Class data_source
 * @link https://github.com/pontikis/simple_data_source
 * - Class PasswordHash
 * @link http://openwall.com/phpass/
 *
 * @author     Christos Pontikis http://pontikis.net
 * @copyright  Christos Pontikis
 * @license    MIT http://opensource.org/licenses/MIT
 * @version    0.1.0 (10 Mar 2014)
 *
 */
class auth {

	/**
	 * Constructor
	 *
	 * @param data_source $ds
	 * @param array $options
	 */
	public function __construct(data_source $ds = null, $options = array()) {

		// initialize ------------------------------------------------------
		$this->ds = $ds;

		$this->user = null;
		$this->demographics= null;

		$this->matches = null;

		$this->last_error = null;
		$this->sql_error = false;

		// options -------------------------------------------------------------
		$defaults = array(
			't_users' => 'users', // the name of the table users
			'c_users_id' => 'id', // the name of primary key column of table users
			'c_users_email' => 'email', // the name of email column
			'c_users_username' => 'username', // the name of username column
			'c_users_password' => 'password', // the name of password column
			'c_users_status' => 'user_status_id', // the name of user status column
			'c_users_email_verification_date' => 'email_verification_date', // the name of email verification date column
			'c_users_email_date' => 'email_date', // the name of current email date column
			'c_users_registration_date' => 'registration_date', // the name of registration date column

			'c_users_demographics_id' => 'demographics_id', // the name of demographics id column (if exists)
			't_demographics' => 'demographics', // the name of the table demographics (if exists)
			'c_demographics_id' => 'id', // the name of primary key column of table demographics (if exists)

			'username_min_chars' => 6,
			'username_max_chars' => 40,
			'username_chars' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
			'username_special_chars' => '.-_',
			'usernames_reserved' => array(
				'root', 'admin', 'sys', 'administrator', 'sysadmin', 'postmaster'
			),

			'password_min_chars' => 8,
			'password_max_chars' => 150,
			'password_chars' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
			'password_special_chars' => '!"#$%&\'()*+,-./:;<=>?@[\]^_`{|}~', // CAUTION: escape (\) quotes
			'password_allow_space' => true,
			'password_min_strength' => 60,

		);

		$opt = array_merge($defaults, $options);

		$this->t_users = $opt['t_users'];
		$this->c_users_id = $opt['c_users_id'];
		$this->c_users_email = $opt['c_users_email'];
		$this->c_users_username = $opt['c_users_username'];
		$this->c_users_password = $opt['c_users_password'];
		$this->c_users_status = $opt['c_users_status'];
		$this->c_users_email_verification_date = $opt['c_users_email_verification_date'];
		$this->c_users_email_date = $opt['c_users_email_date'];
		$this->c_users_registration_date = $opt['c_users_registration_date'];
		$this->c_users_demographics_id = $opt['c_users_demographics_id'];
		$this->t_demographics = $opt['t_demographics'];
		$this->c_demographics_id = $opt['c_demographics_id'];

		$this->username_min_chars = $opt['username_min_chars'];
		$this->username_max_chars = $opt['username_max_chars'];
		$this->username_chars = $opt['username_chars'];
		$this->username_special_chars = $opt['username_special_chars'];
		$this->usernames_reserved = $opt['usernames_reserved'];

		$this->password_min_chars = $opt['password_min_chars'];
		$this->password_max_chars = $opt['password_max_chars'];
		$this->password_chars = $opt['password_chars'];
		$this->password_special_chars = $opt['password_special_chars'];
		$this->password_allow_space = $opt['password_allow_space'];
		$this->password_min_strength = $opt['password_min_strength'];
	}


	/**
	 *
	 * @param string $email
	 * @return bool
	 */
	public function check_user_email($email) {

		$ds = $this->ds;
		$t_users = $this->t_users;
		$c_users_id = $this->c_users_id;
		$c_users_email = $this->c_users_email;

		$this->last_error = null;
		$this->sql_error = false;

		// valid email ---------------------------------------------------------
		if(!$this->is_valid_email($email)) {
			$this->last_error = 'email_invalid';
			return false;
		}

		// valid email domain --------------------------------------------------
		if(!$this->is_valid_email_domain($email)) {
			$this->last_error = 'email_domain_invalid';
			return false;
		}

		// check unique email | CASE IN-SENSITIVE ------------------------------
		$sql = "SELECT count($c_users_id) as total_rows FROM $t_users WHERE {$ds->lower($c_users_email)} = ?";
		$bind_params = array(mb_strtolower($email));
		$query_options = array(
			'get_row' => true
		);
		$res = $ds->select($sql, $bind_params, $query_options);
		if(!$res) {
			$this->last_error = $ds->last_error;
			$this->sql_error = true;
			return false;
		}

		$rs = $ds->data;
		if($rs['total_rows'] != 0) {
			$this->last_error = 'email_in_use';
			return false;
		}

		return true;

	}

	/**
	 *
	 * @param string $username username
	 * @return bool
	 */
	public function check_username($username) {

		$ds = $this->ds;
		$t_users = $this->t_users;
		$c_users_id = $this->c_users_id;
		$c_users_username = $this->c_users_username;

		$this->matches = null;
		$this->last_error = null;
		$this->sql_error = false;

		/**
		 *  MySQLi
		 *  a UNIQUE index is case insensitive, so duplicated records like 'User', 'user' will not permitted
		 *
		 *  PostgreSQL
		 *  a UNIQUE index is NOT case insensitive
		 *  So, to avoid duplicated records like 'User', 'user'
		 *  CREATE UNIQUE INDEX users_idx1 on users (lower(username));
		 *  CREATE UNIQUE INDEX users_idx2 on users (lower(email));
		 *
		 */

		// check for unique username | CASE IN-SENSITIVE -----------------------
		$sql = "SELECT count($c_users_id) as total_rows FROM $t_users WHERE {$ds->lower($c_users_username)} = ?";
		$bind_params = array(mb_strtolower($username));
		$query_options = array(
			'get_row' => true
		);
		$res = $ds->select($sql, $bind_params, $query_options);
		if(!$res) {
			$this->last_error = $ds->last_error;
			$this->sql_error = true;
			return false;
		}
		$rs = $ds->data;
		if($rs['total_rows'] != 0) {
			$this->last_error = 'username_in_use';
			return false;
		}

		// check username min length -------------------------------------------
		if($this->username_min_chars) {
			if(strlen($username) < $this->username_min_chars) {
				$this->last_error = 'username_characters_less_than_min';
				return false;
			}
		}

		// check username max length -------------------------------------------
		if($this->username_max_chars) {
			if(strlen($username) > $this->username_max_chars) {
				$this->last_error = 'username_characters_more_than_max';
				return false;
			}
		}

		// check valid username ------------------------------------------------
		$regex_username = '/[^' .
			preg_quote($this->username_chars .
				$this->username_special_chars, '/') .
			']/u';
		if(preg_match($regex_username, $username, $this->matches)) {
			$this->last_error = 'username_invalid_characters';
			return false;
		}

		if($this->username_special_chars) {
			// check username for consecutively special characters -------------
			foreach(str_split($this->username_special_chars) as $spchar) {
				if(preg_match('/' . preg_quote($spchar) . '{2,}/u', $username, $this->matches)) {
					$this->last_error = 'username_consecutively_special_character';
					return false;
				}
			}

			// check username for special character as first character ---------
			if(in_array(substr($username, 0, 1), str_split($this->username_special_chars))) {
				$this->last_error = 'username_starts_with_special_character';
				return false;
			}

			// check username for special character as last character ----------
			if(in_array(substr($username, -1), str_split($this->username_special_chars))) {
				$this->last_error = 'username_ends_with_special_character';
				return false;
			}
		}

		// check reserved username ---------------------------------------------
		if($this->usernames_reserved) {
			if(in_array($username, $this->usernames_reserved)) {
				$this->last_error = 'username_reserved';
				return false;
			}
		}

		return true;

	}

	/**
	 *
	 * @param $password
	 * @param $password_repeat
	 * @return bool
	 */
	public function check_password($password, $password_repeat) {

		$this->matches = null;
		$this->last_error = null;
		$this->sql_error = false;

		// check password min length -------------------------------------------
		if($this->password_min_chars) {
			if(strlen($password) < $this->password_min_chars) {
				$this->last_error = 'password_characters_less_than_min';
				return false;
			}
		}

		// check password max length -------------------------------------------
		if($this->password_max_chars) {
			if(strlen($password) > $this->password_max_chars) {
				$this->last_error = 'password_characters_more_than_max';
				return false;
			}
		}

		// check valid password ------------------------------------------------
		$regex_password = '/[^' .
			preg_quote($this->password_chars .
				($this->password_allow_space ? ' ' : '') .
				$this->password_special_chars, '/') .
			']/u';
		if(preg_match($regex_password, $password, $this->matches)) {
			$this->last_error = 'password_invalid_characters';
			return false;
		}

		if($this->password_allow_space) {
			// check password for consecutively spaces -------------------------
			if(preg_match('/\s{2,}/', $password)) {
				$this->last_error = 'password_consecutively_spaces';
				return false;
			}

			// check password for space as first character ---------------------
			if(substr($password, 0, 1) == ' ') {
				$this->last_error = 'password_starts_with_space';
				return false;
			}

			// check password for space as last character ----------------------
			if(substr($password, -1) == ' ') {
				$this->last_error = 'password_ends_with_space';
				return false;
			}
		}

		// check password strength ---------------------------------------------
		if($this->password_min_strength) {
			if($this->password_strength($password, $this->password_special_chars) < $this->password_min_strength) {
				$this->last_error = 'password_is_not_strong';
				return false;
			}
		}

		// check if password matches with repeat -------------------------------
		if($password !== $password_repeat) {
			$this->last_error = 'password_repeat_mismatch';
			return false;
		}

		return true;
	}

	/**
	 * Check if a password belongs to user (identified by username or email)
	 *
	 * @param string $user username or email
	 * @param string $password
	 * @param $user_active
	 * @param bool $email_verification
	 * @return bool
	 */
	public function check_valid_user($user, $password, $user_active, $email_verification = null) {

		$ds = $this->ds;
		$t_users = $this->t_users;
		$c_users_email = $this->c_users_email;
		$c_users_username = $this->c_users_username;
		$c_users_password = $this->c_users_password;
		$c_users_status = $this->c_users_status;
		$c_users_email_verification_date = $this->c_users_email_verification_date;
		$c_users_email_date = $this->c_users_email_date;
		$c_users_registration_date = $this->c_users_registration_date;

		$t_demographics = $this->t_demographics;
		$c_users_demographics_id = $this->c_users_demographics_id;
		$c_demographics_id = $this->c_demographics_id;

		$this->last_error = null;
		$this->sql_error = false;

		$user_identifier = $this->is_valid_email($user) ? 'email' : 'username';

		// check if password belongs to given user -----------------------------
		if($user_identifier == 'email') {
			$sql = "SELECT * FROM $t_users WHERE {$ds->lower($c_users_email)} = ?";
			$bind_params = array(mb_strtolower($user));
		} else {
			$sql = "SELECT * FROM $t_users WHERE $c_users_username = ?";
			$bind_params = array($user);
		}

		$res = $ds->select($sql, $bind_params);
		if(!$res) {
			$this->last_error = $ds->last_error;
			$this->sql_error = true;
			return false;
		}

		if($ds->num_rows == 1) {
			$a_user = $ds->data;

			if($a_user[0][$c_users_status] == $user_active) {
				// Initialize the hasher without portable hashes (this is more secure)
				$hasher = new PasswordHash(8, false);

				// use case-sensitive credentials
				if($user_identifier == 'email') {
					$valid_user = (mb_strtolower($a_user[0][$c_users_email]) == mb_strtolower($user) && $hasher->CheckPassword($password, $a_user[0][$c_users_password]));
				} else {
					$valid_user = ($a_user[0][$c_users_username] == $user && $hasher->CheckPassword($password, $a_user[0][$c_users_password]));
				}

				if($valid_user) {

					// check if email is verified
					if($email_verification == 'registration' &&
						$a_user[0][$c_users_email_date] == $a_user[0][$c_users_registration_date] &&
						!$a_user[0][$c_users_email_verification_date]) {
						$this->last_error = 'email_not_verified';
					} else if($email_verification == 'always' &&
						!$a_user[0][$c_users_email_verification_date]) {
						$this->last_error = 'email_not_verified';
					} else {

						$this->user = $a_user[0];

						if($this->t_demographics) {
							$sql = "SELECT * FROM $t_demographics WHERE $c_demographics_id = ?";
							$bind_params = array($a_user[0][$c_users_demographics_id]);
							$query_options = array(
								'get_row' => true
							);
							$res = $ds->select($sql, $bind_params, $query_options);
							if(!$res) {
								$this->last_error = $ds->last_error;
								$this->sql_error = true;
								return false;
							}
						}
						$this->demographics = $ds->data;

						return true;
					}

				} else {
					$this->last_error = 'password_is_wrong';
				}
			} else {
				$this->last_error = 'account_is_not_active';
			}

		} else if($ds->num_rows == 0) {
			if($this->is_valid_email($user)) {
				$this->last_error = 'user_email_not_found';
			} else {
				$this->last_error = 'username_not_found';
			}
		} else {
			$this->last_error = 'user_data_fatal_error';
		}

		return false;

	}


	/**
	 * @param $email
	 * @param $user_active
	 * @param null $email_verification
	 * @return bool
	 */
	public function check_valid_email($email, $user_active, $email_verification = null) {

		$ds = $this->ds;
		$t_users = $this->t_users;
		$c_users_email = $this->c_users_email;
		$c_users_status = $this->c_users_status;
		$c_users_email_verification_date = $this->c_users_email_verification_date;
		$c_users_email_date = $this->c_users_email_date;
		$c_users_registration_date = $this->c_users_registration_date;

		$this->last_error = null;
		$this->sql_error = false;

		// valid email ---------------------------------------------------------
		if(!$this->is_valid_email($email)) {
			$this->last_error = 'email_invalid';
			return false;
		}

		// valid email domain --------------------------------------------------
		if(!$this->is_valid_email_domain($email)) {
			$this->last_error = 'email_domain_invalid';
			return false;
		}

		// check email belongs to an active user | CASE IN-SENSITIVE -----------
		$sql = "SELECT * FROM $t_users WHERE {$ds->lower($c_users_email)} = ?";
		$bind_params = array(mb_strtolower($email));
		$res = $ds->select($sql, $bind_params);
		if(!$res) {
			$this->last_error = $ds->last_error;
			$this->sql_error = true;
			return false;
		}


		$a_user = $ds->data;
		$total_rows = $ds->num_rows;

		if($total_rows == 0) {
			$this->last_error = 'email_unknown';
			return false;
		} else if($total_rows == 1) {
			if($a_user[0][$c_users_status] != $user_active) {
				$this->last_error = 'account_is_not_active';
			} else if($email_verification == 'registration' &&
				$a_user[0][$c_users_email_date] == $a_user[0][$c_users_registration_date] &&
				!$a_user[0][$c_users_email_verification_date]) {
				$this->last_error = 'email_not_verified';
			} else if($email_verification == 'always' && !$a_user[0][$c_users_email_verification_date]) {
				$this->last_error = 'email_not_verified';
			} else {
				$this->user = $a_user[0];
				return true;
			}

		} else {
			$this->last_error = 'user_data_fatal_error';
		}

		return false;

	}


	/**
	 * Get password strength
	 *
	 * @param string $password
	 * @param $special_chars
	 *
	 * @return int
	 */
	public function password_strength($password, $special_chars) {

		$a_strength = array(
			'length' => 0,
			'upper-lower' => 0,
			'number-count' => 0,
			'special-count' => 0,
			'unique' => 0
		);

		// get password length
		$length = strlen($password);

		if($length == 0) {
			return 0;
		}

		// password length ---------------------------------------------------------
		if($length >= 8 && $length <= 15) {
			$a_strength['length'] = 60;
		}
		if($length >= 16 && $length <= 35) {
			$a_strength['length'] = 80;
		}
		if($length > 35) {
			$a_strength['length'] = 100;
		}

		// upper - lower -----------------------------------------------------------
		/*** check if password is not all lower case ***/
		if(strtolower($password) != $password) {
			$a_strength['upper-lower'] += 50;
		}
		/*** check if password is not all upper case ***/
		if(strtoupper($password) != $password) {
			$a_strength['upper-lower'] += 50;
		}

		// numbers count -----------------------------------------------------------
		/*** get the numbers in the password ***/
		preg_match_all('/[0-9]/', $password, $numbers);
		$numbers_count = count($numbers[0]);

		if($numbers_count > 0 && $numbers_count < max(floor($length * 2 / 8), 2)) {
			$a_strength['number-count'] += 50;

			if($numbers_count >= floor($length * 2 / 8) && $numbers_count <= floor($length * 3 / 8)) {
				$a_strength['number-count'] += 50;
			}
		}

		/*	echo '<pre>';
			echo 'Numbers<br>';
			print_r($numbers[0]);
			echo '</pre>';*/

		// special chars count -----------------------------------------------------
		preg_match_all('/[' . preg_quote($special_chars, '/') . ']/', $password, $specialchars);
		$special_count = count($specialchars[0]);

		if($special_count > 0) {
			$a_strength['special-count'] += 25;
		}
		if($special_count >= floor($length * 2 / 10)) {
			$a_strength['special-count'] += 25;
		}
		if($special_count >= floor($length * 3 / 10)) {
			$a_strength['special-count'] += 25;
		}
		if($special_count >= floor($length * 4 / 10)) {
			$a_strength['special-count'] += 25;
		}


		/*	echo '<pre>';
			echo 'Special<br>';
			print_r($specialchars[0]);
			echo '</pre>';*/

		// unique chars count ------------------------------------------------------
		$chars = str_split($password);
		$unique_chars_count = count(array_unique($chars));

		if($length > 6) {
			if($unique_chars_count >= floor($length * 6 / 10)) {
				$a_strength['unique'] += 25;
			}
			if($unique_chars_count >= floor($length * 7 / 10)) {
				$a_strength['unique'] += 25;
			}
			if($unique_chars_count >= floor($length * 8 / 10)) {
				$a_strength['unique'] += 25;
			}
			if($unique_chars_count >= floor($length * 9 / 10)) {
				$a_strength['unique'] += 25;
			}
		}

		/*	echo '<pre>';
			echo $unique_chars_count . ' / ' . $length;
			echo '</pre>';


			echo '<pre>';
			print_r($a_strength);
			echo '</pre>';*/


		// strength is a number from 0-100
		return floor(array_sum($a_strength) / count($a_strength));

	}


	/**
	 * Create random string from given string
	 *
	 * @param string $chars the string to use its characters
	 * @param int $len rando string length
	 * @return string
	 */
	public function suggest_password($chars, $len) {
		$ret = '';
		$l = strlen($chars) - 1;
		for($i = 0; $i < $len; $i++) {
			$ret .= $chars[mt_rand(0, $l)];
		}
		return $ret;
	}

	/**
	 * Check if expression is valid email address
	 *
	 * @param $email
	 * @return mixed
	 */
	public function is_valid_email($email) {
		return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
	}

	/**
	 * @param $email
	 * @return bool
	 */
	public function is_valid_email_domain($email) {
		return checkdnsrr($this->getDomainFromEmail($email));
	}


	/**
	 * @param $email
	 * @return string
	 */
	public function getDomainFromEmail($email) {
		return substr(strrchr($email, '@'), 1);
	}


	/**
	 * Set option
	 *
	 * @param $opt
	 * @param $val
	 */
	public function set_option($opt, $val) {
		$this->$opt = $val;
	}

}