<?php
session_name('racer_web_app');
session_start();

require_once 'service.php';

$auth_key = Service::get_auth_key();

if (!$auth_key) {
	Service::redirect('login.php', 'You have to login before', 'alert-error');
}

$track_id = isset($_GET['track_id']) ? $_GET['track_id'] : null;
$track_name = isset($_GET['track_name']) ? $_GET['track_name'] : '';

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

$title = "Racer | Control track : {$track_name}";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<?php include 'header.php'; ?>
	</head>
	<body>
		<div id="container">
			<?php include_once 'nav.php'; ?>
			<div class="container">
				<div class="row">
					<div class="span6 text-center">
						<input type="hidden" name="track-id" id="track-id" value="<?php echo $track_id ?>"/>
						<h1>Global</h1>
						<div class="flags-group">
							<a title="Restart flag" class="btn-flag-restart"></a>
							<a title="White flag" class="btn-flag-white"></a>
							<a title="Finish flag" class="btn-flag-finish"></a>
							<a title="Black flag" class="btn-flag-black"></a>
							<br />							
							<a title="Red flag" class="btn-flag-red"></a>
							<a title="Green flag" class="btn-flag-green"></a>
							<a title="Yellow flag" class="btn-flag-yellow"></a>
							<a title="Safety flag" class="btn-flag-safety"></a>
						</div>
						<h1>Local</h1>
						<div class="number-group">
							<?php for ($i = 1; $i <= 25; $i ++) : ?>
								<a id="flag-number-<?php echo $i ?>" title="Local <?php echo $i ?>"><?php echo $i ?> <i></i></a>
								<?php if ($i % 5 == 0) : ?>
									<br />
								<?php endif; ?>
							<?php endfor; ?>
						</div>
						<div id="local-type-select">
							<ul>
								<li><a href="javascript:;" data-local="0" data-waving="true">WAVING</a></li>
								<li><a href="javascript:;" data-local="0" data-waving="false">STANDING</a></li>
							</ul>
						</div>
						<div class="input-wrapper clearfix">
							<button class="btn" type="submit" id="send-message" name="send" value="Send Flag">Send Message</button>
							<div>
								<input type="text" id="message" name="message" value="" placeholder="Message to send" />
							</div>

						</div>
						<a href="logout.php" class="btn btn-red">End Race Control</a>
					</div>
					<div class="span6 text-center">
						<h1>History</h1>
						<table id='history-table' class="table table-bordered table-condensed table-striped">
							<thead>
								<tr>
									<th class="text-center" width="150">Action</th>
									<th class="text-center" width="100">Message</th>
									<th class="text-center" width="150">Sent time</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="footer">
			<?php include 'footer.php' ?>
		</div>
	</body>
</html>