<div id="header" class="navbar">
	<div class="navbar-inner">
		<div class="container">
			<a href="index.php" class="brand">Racer</a>
			<?php if (Service::get_auth_key()) : ?>
				<h6 class="pull-right">
					<a href="logout.php" title="Logout">Logout</a>
				</h6>
			<?php endif; ?>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="page-header">
		<h1><?php echo $title ?></h1>
	</div>

	<div class="alert-message warning" id="alert">
		<?php echo Service::show_message(); ?>
	</div>
</div>