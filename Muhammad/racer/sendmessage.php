<?php

session_name('racer_web_app');
session_start();

require_once 'service.php';

header('Content-type: application/json');

$auth_key = Service::get_auth_key();

if (!$auth_key) {
	$data = array('error' => 10, 'msg' => 'Not authenticate');
} else {
	$track_id = isset($_GET['track_id']) ? $_GET['track_id'] : null;
	$action = isset($_GET['action']) ? $_GET['action'] : null;
	if (!$track_id || !$action || !in_array($action, array('sendglobalflag', 'sendlocalflag', 'sendtext'))) {
		$data = array('error' => 20, 'msg' => 'Track id or Action is invalid');
	} else {
		$color = isset($_GET['color']) ? $_GET['color'] : '';
		$number = isset($_GET['number']) ? $_GET['number'] : '';
		$message = isset($_GET['text']) ? $_GET['text'] : '';
		$data = Service::instance()->send_flag_text_to_service($auth_key, $track_id, $action, $color, $number, $message);
	}
}

echo json_encode($data);
