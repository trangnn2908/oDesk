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

function merge_images($images = array(), $merge_file = null) {
	if (!empty($images)) {
		$merge_image = new Imagick();

		foreach ($images as $image) {
			$im = new Imagick($image);
			$im->scaleimage(200, 200, true);
			$merge_image->addimage($im);
		}

		$merge_image->resetiterator();
		$combined = $merge_image->appendimages(false);
		$merge_file = $merge_file ? : "images/mergeds/" . uniqid("merged_") . ".jpg";
		$combined->setImageFormat("jpeg");
		$combined->writeimage($merge_file);

		return $merge_file;
	}
}

header('Content-type: application/json');

if (!isset($_GET['id'])) {
	echo json_encode(NULL);
	exit();
}

$url = "http://tiltenvironment-mxb46jnj9p.elasticbeanstalk.com/services/";
$id = $_GET['id'];
$url .= $id;
$url .= "?access_token=85d49ddf-1d26-496a-97b9-868a2470c1b1";

$res = get_content_from_url($url);
if ($res == "null") {
	echo json_encode(NULL);
} else {
	try {
		$merged_image_file = "images/mergeds/" . md5($id) . '.jpg';
		$data = json_decode($res, true);
		
		if (!file_exists($merged_image_file)) {
			$image1_file = "images/mergeds/image_" . uniqid() . ".jpg";
			$image2_file = "images/mergeds/image_" . uniqid() . ".jpg";

			file_put_contents($image1_file, file_get_contents($data['fullImage1']));
			file_put_contents($image2_file, file_get_contents($data['fullImage2']));

			$merged_image = merge_images(array($image1_file, $image2_file), $merged_image_file);

			unlink($image1_file);
			unlink($image2_file);
		}

		$data['merged_image'] = $merged_image_file;
		$res = json_encode($data);

		echo $res;
	} catch (Exception $e) {
		echo $e->getMessage();
	}
}
exit();
?>