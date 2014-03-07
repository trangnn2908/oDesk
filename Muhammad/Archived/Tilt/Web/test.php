<?php

function get_content_from_url($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	$tmp = curl_exec($ch);
	curl_close($ch);
	if ($tmp != false) {
		return $tmp;
	}
}

var_dump(get_content_from_url('http://local.tit2/index_fixed.php?id=ccc15796-3d8f-4976-9750-112a84f332b1'));