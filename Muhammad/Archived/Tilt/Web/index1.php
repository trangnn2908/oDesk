<?php

function get_content_from_url($url) {
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

$data = array(
	'image_share_fb' => 'http://d3kt8hfx7qigf.cloudfront.net/fb-tilt.png',
	'image_share_fb_secure' => 'https://d3kt8hfx7qigf.cloudfront.net/fb-tilt.png',
	'title_share_fb' => 'Tilt',
	'image_share_tw' => 'http://d22d7v2y1t140g.cloudfront.net/m_10323555_W8dPBQahkN95.jpg',
	'ownername' => '',
	'ownerimage' => 'images/_blank.png',
	'url' => 'http://m.tilt.la',
	'date_show' => '',
	'content' => "Tilt is a social service that allows you to post comparisons between anything and share them with your friends. Within the Tilt post you will have access to a unique tagging system (different than a hashtag!) that provides other users with a way to search and discover comparisons.",
	'image1' => 'images/_blank.png',
	'image2' => 'images/_blank.png',
	'pnumber_content' => 'tilts',
	'nnumber_content' => 'tilts',
	'compareitemright' => '',
	'compareitemleft' => '',
);

if (isset($_GET['id'])) {
	$url = "http://testenv-gp37dutjwx.elasticbeanstalk.com/services/";
	$id = $_GET['id'];
	$url .= $id;
	$url .= "?access_token=85d49ddf-1d26-496a-97b9-868a2470c1b1";

	$service_data = json_decode(get_content_from_url($url), true);
var_dump("<pre>", $service_data); die;
	if ($service_data !== null) {
		$data = array(
			'image_share_fb' => "http://d3kt8hfx7qigf.cloudfront.net/{$id}.jpg",
			'image_share_fb_secure' => "https://d3kt8hfx7qigf.cloudfront.net/{$id}.jpg",
			'title_share_fb' => htmlspecialchars($service_data['ownername'] . "'s post", ENT_QUOTES, 'utf-8', true),
			'image_share_tw' => 'http://d22d7v2y1t140g.cloudfront.net/m_10323555_W8dPBQahkN95.jpg',
			'ownername' => $service_data['ownername'],
			'ownerimage' => $service_data['ownerimage'],
			'url' => "http://m.tilt.la/?id={$id}",
			'date_show' => "posted on " . date('F d, Y', (int) $service_data['date'] / 1000),
			'content' => $service_data['content'],
			'image1' => $service_data['image1'],
			'image2' => $service_data['image2'],
			'pnumber_content' => $service_data['pnumber'] . ($service_data['pnumber'] > 1 ? " tilts" : ' tilt'),
			'nnumber_content' => $service_data['nnumber'] . ($service_data['nnumber'] > 1 ? " tilts" : ' tilt'),
			'compareitemright' => $service_data['compareitemright'],
			'compareitemleft' => $service_data['compareitemleft'],
		);
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# tiltmobi: http://ogp.me/ns/fb/tiltmobi#">
		<title><?php echo $data['title_share_fb'] ?></title>

		<meta name="description" content="<?php echo $data['content'] ?>" />
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />

		<meta property="fb:app_id" content="413769265424782" />
		<meta property="og:type" content="tiltmobi:photo" />
		<meta property="og:title" content="<?php echo $data['title_share_fb'] ?>" />
		<meta property="og:image" content="<?php echo $data['image_share_fb'] ?>" />
		<meta property="og:image:secure_url" content="<?php echo $data['image_share_fb_secure'] ?>" />
		<meta property="og:description" content="<?php $data['content'] ?>" />
		<meta property="og:url" content="<?php echo $data['url'] ?>" />

		<meta name="twitter:card" content="summary" />
		<meta name="twitter:site" content="@Pheed" />
		<meta name="twitter:image" content="<?php echo $data['image_share_tw'] ?>" />
		<meta name="fragment" content="!" />

		<link rel="canonical" href="<?php echo $data['url'] ?>" />
		<link rel="stylesheet" type="text/css" href="https://d2gnmrx58i4xh3.cloudfront.net/css/css.pack.muaddib-2.9.13.2.css">
			<link rel="stylesheet" type="text/css" href="https://d2gnmrx58i4xh3.cloudfront.net/css/login.muaddib-2.9.13.2.css">

				<link rel="stylesheet" href="css/style.css" />
				<script type="application/javascript" src="js/jquery.min.js"></script>
				</head>
				<body>
					<div class="page-wrapper">
						<div class="white-liner"></div>
						<div class="page-container">
							<div class="phone-shine"></div>	
							<div class="phone-bg">
								<div class="phone-content">
									<div class="logo"><a href="#"><img src="images/tilt.png" width="150" /></a></div>
									<div class="compare-image-container">
										<div class="image-container">
											<img class="compare-image" src="<?php echo $data['image1'] ?>" alt="Image1" title="Image1" />
											<br /><span class="compare-title"><?php echo $data['compareitemleft'] ?></span>
											<br /><div class="image-title"><button disabled="disabled"><?php echo $data['nnumber_content'] ?></button></div>
										</div>
										<div class="image-divider"><img src="images/versus_button.png" /></div>
										<div class="image-container">
											<img class="compare-image" src="<?php echo $data['image2'] ?>" alt="Image2" title="Image2" />
											<br /><span class="compare-title"><?php echo $data['compareitemright'] ?></span>
											<br /><div class="image-title"><button disabled="disabled"><?php echo$data['pnumber_content'] ?></button></div>
										</div>
										<span class="clear"></span>
									</div>
									<div class="owner-container">
										<div class="owner-icon"><img src="<?php echo $data['ownerimage'] ?>" alt='<?php echo $data['ownername'] ?>' title='<?php echo $data['ownername'] ?>' /></div>
										<div class="owner-details">
											<div class="owner-name"><?php echo $data['ownername'] ?></div>
											<div class="post-date"><?php echo $data['date_show'] ?></div>
											<div class="post-desc"><?php echo $data['content'] ?></div>
										</div>
										<span class="clear"></span>
									</div>
								</div>
							</div>
							<div class="page-foot">
								<img src="images/download_btn.png"  onclick="javascript:document.location = 'http://tilt.la'" class="dl_app" />
							</div>
						</div>
					</div>
					<script type="text/javascript">
						$(function() {
							$('.image-divider').css({height: $('.compare-image-container').height()});
						});
					</script>
				</body>

				</html>