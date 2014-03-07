<?php
session_name('racer_web_app');
session_start();

require_once 'service.php';

$auth_key = Service::get_auth_key();

if (!$auth_key) {
	Service::redirect('login.php', 'You have to login before', 'alert-error');
}

$track_id = isset($_GET['track_id']) ? $_GET['track_id'] : null;

if (!$track_id) {
	Service::redirect('index.php', 'Track id or Action is invalid', 'alert-error');
}

$event_id = Service::instance()->get_event_id($auth_key, $track_id);

if (isset($_POST['send']) && $_POST['send'] == 'Send Flag') {
	$message = $_POST['message'];
	$flag_color = $_POST['flag-color'];
	$flag_local = $_POST['flag-local'];
	
	if(!$flag_color && !$message) {
		Service::set_message("You must choose flag color or message", 'alert-error');
	} else {
		$data = Service::instance()->send_flag_text_to_service($auth_key, $track_id, $flag_color, $flag_local, $message);
//		Service::set_message("You sent flag success! Event id: " . $event_id . " data received" . json_encode($data));
		Service::set_message("You sent flag and text success!");
	}
}

$title = "Racer | Send message"
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<?php include 'header.php'; ?>
	</head>
	<body>
		<div id="container">
			<?php include_once 'nav.php'; ?>
			<div class="container-fluid">
				<form action="send.php?track_id=<?php echo $track_id ?>" method="post" class="form form-horizontal">
					<div class="control-group">
						<label class="control-label">Flag color</label>
						<div class="controls">
							<input type="hidden" name="flag-color" value="" id="flag-color" />
							<a href="javascript:;" data-select-value="yellow" data-select-type="color" class="btn btn-warning flag-select"><i class="icon-flag"></i></a>
							<a href="javascript:;" data-select-value="red" data-select-type="color" class="btn btn-danger flag-select"><i class="icon-flag"></i></a>
							<a href="javascript:;" data-select-value="blue" data-select-type="color" class="btn btn-primary flag-select"><i class="icon-flag"></i></a>
							<a href="javascript:;" data-select-value="green" data-select-type="color" class="btn btn-success flag-select"><i class="icon-flag"></i></a>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Local</label>
						<div class="controls">
							<input type="hidden" name="flag-local" value="0" id="flag-local" />
							<?php for ($i = 1; $i <= 25; $i ++) : ?>
							<a href="javascript:;" data-select-value="<?php echo $i ?>" data-select-type="local" class="btn flag-select"><?php echo $i ?></a>
								<?php if ($i % 5 == 0) : ?>
									<br />
								<?php endif; ?>
							<?php endfor; ?>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Message</label>
						<div class="controls">
							<input class="input-xlarge" type="text" name="message" value="" placeholder="Message to send" />
						</div>
					</div>
					<div class="control-group">
						<div class="controls">
							<input type="submit" id="send-message" name="send" value="Send Flag" class="btn btn-primary" />
							<a href="logout.php" class="btn btn-warning">End Race Control</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</body>
</html>