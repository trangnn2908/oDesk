<?php

class Service {

	private static $_instance = null;
	private $_config = array();

	private function __construct() {
		$this->_config = $this->get_config_from_file();
	}

	private function get_config_from_file() {
		return require_once 'config.php';
	}

	public static function instance() {
		if (!self::$_instance) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public static function redirect($url = '', $msg = '', $type = 'alert-success') {
		if ($msg) {
			self::set_message($msg, $type);
		}

		header("Location: {$url}");

		exit();
	}

	public static function get_auth_key() {
		if (isset($_SESSION['auth']) && $_SESSION['auth']) {
			return $_SESSION['auth'];
		} else {
			return null;
		}
	}

	public function login($auth) {
		if ($this->check_login_with_service($auth)) {
			$_SESSION['auth'] = $auth;
			self::set_message('Login success!', 'alert-success');
			return true;
		} else {
			self::set_message('Login fail! Invalid Credentials', 'alert-error');
			return false;
		}
	}

	public function logout() {
		session_destroy();
		return true;
	}

	public static function set_message($msg, $type = 'alert-success') {
		$_SESSION['msg'] = "{$type}|{$msg}";
	}

	public static function get_message() {
		if (isset($_SESSION['msg']) && $_SESSION['msg']) {
			$msg_data = explode('|', $_SESSION['msg']);
			return "<div class='fade in alert {$msg_data[0]}'><a class='close' data-dismiss='alert'>&times;</a>{$msg_data[1]}</div>";
		} else {
			return "";
		}
	}

	public static function remove_message() {
		unset($_SESSION['msg']);
	}

	public static function show_message() {
		$msg = self::get_message();
		if ($msg) {
			self::remove_message();
		}
		return $msg;
	}

	private function _get_content_from_url($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		$tmp = curl_exec($ch);
		curl_close($ch);
		if ($tmp != false) {
			return $tmp;
		}
	}

	private function _get_data_from_service($action, $auth_key, $param = array(), $cache_file = false) {

		if (!isset($this->_config['service']['action'][$action]) || !$auth_key) {
			return null;
		} else {
			$url = $this->_config['service']['url_prefix'] . $this->_config['service']['action'][$action] . $auth_key;
			$param_string = "";
			if (!empty($param)) {
				foreach ($param as $param_name => $param_value) {
					$param_string .= "&{$param_name}={$param_value}";
				}
			}
			$url .= $param_string;

			$data = json_decode($this->_get_content_from_url($url), true);

			if ($cache_file) {
				$data_to_cache = json_encode(array('time' => time(), 'data' => $data));
				file_put_contents($this->_config['cache']['dir'] . "{$cache_file}.json", $data_to_cache);
			}
			return $data;
		}
	}

	public function check_login_with_service($auth_key) {
		$auth_key_received = $this->_get_data_from_service('auth', $auth_key);

		return $auth_key_received === $auth_key;
	}

	public function get_event_id($auth_key, $track_id) {
		if ($track_id && $auth_key) {
			if (isset($_SESSION['event_id'][$auth_key][$track_id]) && $_SESSION['event_id'][$auth_key][$track_id]) {
				$event_id = $_SESSION['event_id'][$auth_key][$track_id];
			} else {
				$event = $this->_get_data_from_service('createevent', $auth_key, array('trackid' => $track_id));
				$event_id = isset($event['id']) ? $event['id'] : null;
				$_SESSION['event_id'][$auth_key][$track_id] = $event_id;
			}
			return $event_id;
		} else {
			return null;
		}
	}

	public function send_flag_text_to_service($auth_key, $track_id, $action = '', $color = '', $local = 0, $iswaving = 'false', $message = '') {
		if ($action && in_array($action, array('sendglobalflag', 'sendlocalflag', 'sendtext'))) {
			$event_id = $this->get_event_id($auth_key, $track_id);
			if ($event_id) {
				switch ($action) {
					case 'sendglobalflag':
						if (!$color) {
							$error = 3;
							$msg = 'Send global flag require color of flag';
						} else {
							$param = array(
								'eventid' => $event_id,
								'color' => $color,
								'iswaving' => $iswaving,
							);
						}
						break;
					case 'sendlocalflag':
						if (!$color || !$local) {
							$error = 4;
							$msg = 'Send local flag require local and color flag is yellow';
						} else {
							$param = array(
								'eventid' => $event_id,
								'color' => $color,
								'number' => $local,
								'iswaving' => $iswaving,
							);
						}
						break;
					case 'sendtext':
						if (!$message) {
							$error = 5;
							$msg = 'Send text require text to send';
						} else {
							$param = array(
								'eventid' => $event_id,
								'text' => $message,
							);
						}
						break;
					default :
						break;
				}

				if ($error) {
					return array('error' => $error, 'msg' => $msg);
				}

				$data = $this->_get_data_from_service($action, $auth_key, $param);

				return array('error' => 0, 'msg' => 'success', 'data' => $data);
			} else {
				return array('error' => 2, 'msg' => 'Event not created yet');
			}
		} else {
			return array('error' => 1, 'msg' => 'Invalid action');
		}
	}

	public function get_list_track($auth_key, $search_term = '', $from_cached = false) {
		$cache_file = false;

		if ($search_term !== '') {
			$action = 'search-tracks';
			$param = array('searchterm' => $search_term);
		} else {
			$action = 'gettracks';
			$param = array();
		}

		if ($from_cached) {
			$file_name = md5($auth_key . "-" . $search_term);

			if (!file_exists($this->_config['cache']['dir'] . "{$file_name}.json")) {
				$cache_file = $file_name;
			} else {
				$cached_data = json_decode(file_get_contents($this->_config['cache']['dir'] . "{$file_name}.json"), true);
				if ($cached_data['time'] >= time() - $this->_config['cache']['expire_time']) {
					return $cached_data['data'];
				} else {
					$cache_file = $file_name;
				}
			}
		}

		return $this->_get_data_from_service($action, $auth_key, $param, $cache_file);
	}

}
