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

	if (!$flag_color && !$message) {
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
				<div class="span5 text-center">
					<form action="send.php?track_id=<?php echo $track_id ?>" method="post" class="form form-horizontal">
						<h1>Flag color</h1>
						<input type="hidden" name="flag-color" value="" id="flag-color" />
						<div class="flags-group">
							<a href="javascript:;" data-select-value="yellow" data-select-type="color" class="btn-flag-01"></a>
							<a href="javascript:;" data-select-value="red" data-select-type="color" class="btn-flag-02"></a>
							<a href="javascript:;" data-select-value="blue" data-select-type="color" class="btn-flag-03"></a>
							<a href="javascript:;" data-select-value="green" data-select-type="color" class="btn-flag-04"></a><br />							
							<a href="javascript:;" data-select-value="yellow" data-select-type="color" class="btn-flag-05"></a>
							<a href="javascript:;" data-select-value="red" data-select-type="color" class="btn-flag-06"></a>
							<a href="javascript:;" data-select-value="blue" data-select-type="color" class="btn-flag-07"></a>
							<a href="javascript:;" data-select-value="green" data-select-type="color" class="btn-flag-08"></a>
						</div>

						<h1>Local</h1>
						<input type="hidden" name="flag-local" value="0" id="flag-local" />
						<div class="number-group">
							<?php for ($i = 1; $i <= 25; $i ++) : ?>
								<a href="javascript:;" title="Local <?php echo $i ?>"><?php echo $i ?></a>
								<?php if ($i % 5 == 0) : ?>
									<br />
								<?php endif; ?>
							<?php endfor; ?>
						</div>
						<div class="input-wrapper clearfix">
							<button class="btn" type="submit" id="send-message" name="send" value="Send Flag">Send Message</button>
							<div>
								<input type="text" name="message" value="" placeholder="Message to send" />
							</div>

						</div>
						<a href="logout.php" class="btn btn-red">End Race Control</a>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>