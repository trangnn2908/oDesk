<?php
session_name('racer_web_app');
session_start();

require_once 'service.php';

$auth_key = Service::get_auth_key();

if (!$auth_key) {
	Service::redirect('login.php');
}

$search_term = isset($_GET['search-term']) ? $_GET['search-term'] : '';

$use_cache = $search_term ? false : true;

$list_tracks = Service::instance()->get_list_track($auth_key, $search_term, $use_cache);

$title = 'Racer | List tracks';
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
				<div class="clearfix">
					<form class="form form-inline" method="get" action="">
						<input type="text" class="form-control" id="search-term" value="<?php echo $search_term ?>" name="search-term" placeholder="Search Track" />
						<button type="submit" class="btn btn-primary">Search</button>
					</form>
				</div>
				
				<div class="navbar navbar-fixed-bottom func-button-group">
					<div class="pull-right">
						<button id="start-race-button" type="button" class="btn btn-primary"><i class="icon-flag icon-white"></i> START RACE CONTROL</button>
					</div>
				</div>
				
				<table class="table table-bordered table-striped table-condensed">
					<thead>
						<tr>
							<th width="50">No.</th>
							<th>Name</th>
							<th width="10">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($list_tracks as $index => $track) : ?>
							<tr id="<?php echo $track['id'] ?>" class="track-select">
								<td><?php echo $index + 1; ?></td>
								<td><?php echo $track['name']; ?></td>
								<td>
									<input type="radio" name="track-id" class="track-id-radio" value="<?php echo $track['id'] ?>" />
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</body>
</html>