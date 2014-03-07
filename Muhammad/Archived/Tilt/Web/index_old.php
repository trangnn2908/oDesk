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

function get_croped_image($file, $width, $height) {
	$image = new Imagick();
	$image->setbackgroundcolor('white');
	$image->readImage($file);
	$image = $image->flattenimages();
	if (!$image->getImageAlphaChannel()) {
		$image->setImageAlphaChannel(Imagick::ALPHACHANNEL_SET);
	}

	$image->scaleimage($width, $height);

	return $image;
}

function merge_images($image1, $image2, $merge_file = null) {

	$imagick = new Imagick();

	$bg = new ImagickPixel();
	$bg->setcolor('white');

	$imagick->newimage(427, 214, $bg);

	$imagick1 = get_croped_image($image1, 200, 200);
	$imagick2 = get_croped_image($image2, 200, 200);
	$imagick_border = $image = new Imagick();
	$imagick_border->readimage("images/image-bg_hover.png");

	$imagick->compositeimage($imagick1, Imagick::COMPOSITE_COPY, 6, 6);
	$imagick->compositeimage($imagick2, Imagick::COMPOSITE_COPY, 219, 6);
	$imagick->compositeimage($imagick_border, Imagick::COMPOSITE_DEFAULT, 1, 1);
	$imagick->compositeimage($imagick_border, Imagick::COMPOSITE_DEFAULT, 213, 1);
	
	$imagick->setformat('jpg');
	$merge_file = $merge_file ? : "images/mergeds/" . uniqid() . '.jpg';
	$imagick->writeimage($merge_file);

	return $merge_file;
}

if (!isset($_GET['id'])) {
	$data = array();
} else {
	$data = array();

	$url = "http://tiltenvironment-mxb46jnj9p.elasticbeanstalk.com/services/";
	$id = $_GET['id'];
	$url .= $id;
	$url .= "?access_token=85d49ddf-1d26-496a-97b9-868a2470c1b1";

	$data = json_decode(get_content_from_url($url), true);
	if ($data !== null) {
		try {
			$merged_image_file = "images/mergeds/" . md5($id) . '.jpg';

			if (!file_exists($merged_image_file)) {
				$merged_image = merge_images($data['image1'], $data['image2'], $merged_image_file);
			}

			$data['share_img'] = "http://{$_SERVER['SERVER_NAME']}/{$merged_image_file}";
		} catch (Exception $e) {
			$data = array();
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo isset($data['ownername']) ? htmlspecialchars($data['ownername'] . "'s post", ENT_QUOTES, 'utf-8', true) : "Tilt" ?></title>
		<meta content="" name="description" />
		<meta property="fb:app_id" content="413769265424782" />
		<meta property="og:type" content="tiltmobi:photo" /> 
		<meta property="og:site_name" content="Tilt" />
		<meta property="og:image" content="<?php echo isset($data['share_img']) ? $data['share_img'] : "http://tilt.la/fbprofile.jpg" ?>" />
		<meta property="og:title" content="<?php echo isset($data['ownername']) ? htmlspecialchars($data['ownername'] . "'s post", ENT_QUOTES, 'utf-8', true) : "Tilt" ?>" />
		<meta property="og:description" content="<?php echo isset($data['content']) ? $data['content'] : "Tilt is a social service that allows you to post comparisons between anything and share them with your friends. Within the Tilt post you will have access to a unique tagging system (different than a hashtag!) that provides other users with a way to search and discover comparisons." ?>" />
		<meta property="og:url" content="<?php echo "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] ?>" />

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
                                <img class="compare-image" src="<?php echo isset($data['image1']) ? $data['image1'] : 'images/_blank.png' ?>" alt="Image1" title="Image1" />
                                <br /><span class="compare-title"><?php echo isset($data['compareitemleft']) ? $data['compareitemleft'] : 'tilt' ?></span>
                                <br /><div class="image-title"><button disabled="disabled"><?php echo isset($data['nnumber']) ? $data['nnumber'] . ($data['nnumber'] > 1 ? " tilts" : ' tilt') : "tilts" ?></button></div>
                            </div>
                            <div class="image-divider"><img src="images/versus_button.png" /></div>
                            <div class="image-container">
                                <img class="compare-image" src="<?php echo isset($data['image2']) ? $data['image2'] : 'images/_blank.png' ?>" alt="Image2" title="Image2" />
                                <br /><span class="compare-title"><?php echo isset($data['compareitemright']) ? $data['compareitemright'] : '' ?></span>
                                <br /><div class="image-title"><button disabled="disabled"><?php echo isset($data['pnumber']) ? $data['pnumber'] . ($data['pnumber'] > 1 ? " tilts" : ' tilt') : "tilts" ?></button></div>
                            </div>
                            <span class="clear"></span>
                        </div>
                        <div class="owner-container">
							<div class="owner-icon"><img src="<?php echo isset($data['ownerimage']) ? $data['ownerimage'] : "images/_blank.png" ?>"<?php echo isset($data['ownername']) ? " alt='{$data['ownername']}'" : "" ?><?php echo isset($data['ownername']) ? " title='{$data['ownername']}'" : "" ?> /></div>
                            <div class="owner-details">
                                <div class="owner-name"><?php echo isset($data['ownername']) ? $data['ownername'] : "" ?></div>
                                <div class="post-date"><?php echo isset($data['date']) ? "posted on " . date('F d, Y', (int) $data['date'] / 1000) : "" ?></div>
                                <div class="post-desc"><?php echo isset($data['content']) ? $data['content'] : "" ?></div>
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