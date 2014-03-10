<?php
session_name('racer_web_app');
session_start();
require_once 'service.php';

$auth_key = Service::get_auth_key();

if ($auth_key) {
	Service::redirect('index.php');
}

if (isset($_POST['login'])) {
	if (Service::instance()->login($_POST['auth-key'])) {
		Service::redirect('index.php');
	}
}

$title = "Racer | Login page";
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
				<form action="login.php" method="post" class="form form-inline">
					<input class="input-xlarge" type="text" name="auth-key" value="" placeholder="Authentication string" />
					<input type="submit" id="login" name="login" value="Login" class="btn btn-primary" />
				</form>
			</div>
		</div>
		<div class="footer">
			<?php include 'footer.php' ?>
		</div>
	</body>
</html>