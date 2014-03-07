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

if (!isset($_GET['id'])) {
	$data = array();
} else {
	$data = array();

	$url = "http://testenv-gp37dutjwx.elasticbeanstalk.com/services/";
	$id = $_GET['id'];
	$url .= $id;
	$url .= "?access_token=85d49ddf-1d26-496a-97b9-868a2470c1b1";

	$data = json_decode(get_content_from_url($url), true);
	if ($data !== null) {
		try {
			$merged_image_file = "images/mergeds/" . md5($id) . '.jpg';

			if (!file_exists($merged_image_file)) {
				$image1_file = "images/mergeds/image_" . uniqid() . ".jpg";
				$image2_file = "images/mergeds/image_" . uniqid() . ".jpg";

				file_put_contents($image1_file, file_get_contents($data['image1']));
				file_put_contents($image2_file, file_get_contents($data['image2']));


				unlink($image1_file);
				unlink($image2_file);
			}

			$data['share_img'] = "http://{$_SERVER['SERVER_NAME']}/{$merged_image_file}";
		} catch (Exception $e) {
			$data = array();
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# tiltmobi: http://ogp.me/ns/fb/tiltmobi#">
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title><?php echo isset($data['ownername']) ? htmlspecialchars($data['ownername'] . "'s post", ENT_QUOTES, 'utf-8', true) : "Tilt" ?></title>
<meta name="description" content="Finished my #Rihanna #digitalpainting. Happy birthday.">
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta property="fb:app_id" content="413769265424782" /> 
<meta property="og:image:secure_url" content=<?php echo isset($data['id']) ? "https://d3kt8hfx7qigf.cloudfront.net/" . $data['id'] .".jpg" : "https://d3kt8hfx7qigf.cloudfront.net/fb-tilt.png" ?>>
<meta property="og:type" content="tiltmobi:photo" />
<meta property="og:image" content=<?php echo isset($data['id']) ? "http://d3kt8hfx7qigf.cloudfront.net/" . $data['id'] .".jpg" : "http://d3kt8hfx7qigf.cloudfront.net/fb-tilt.png" ?>>
<meta property="og:title" content="<?php echo isset($data['ownername']) ? htmlspecialchars($data['ownername'] . "'s post", ENT_QUOTES, 'utf-8', true) : "Tilt" ?>" />
<meta property="og:description" content="<?php echo isset($data['content']) ? $data['content'] : "Tilt is a social service that allows you to post comparisons between anything and share them with your friends. Within the Tilt post you will have access to a unique tagging system (different than a hashtag!) that provides other users with a way to search and discover comparisons." ?>" />
<meta property="og:url" content=<?php echo isset($data['id']) ? "http://m.tilt.la?id=" . $data['id'] : "http://m.tilt.la" ?>>
<meta name="twitter:card" content="summary">
<meta name="twitter:site" content="@Pheed">
<meta name="twitter:image" content="http://d22d7v2y1t140g.cloudfront.net/m_10323555_W8dPBQahkN95.jpg">
<meta name="fragment" content="!">
<link rel="canonical" href=<?php echo isset($data['id']) ? "http://m.tilt.la?id=" . $data['id'] : "http://m.tilt.la" ?>>
<link rel="stylesheet" type="text/css" href="https://d2gnmrx58i4xh3.cloudfront.net/css/css.pack.muaddib-2.9.13.2.css">
<link rel="stylesheet" type="text/css" href="https://d2gnmrx58i4xh3.cloudfront.net/css/login.muaddib-2.9.13.2.css">
</head>
<body>
        
<div id="wrapper" data-bg-image="https://d1dcwuzxl9elyu.cloudfront.net/1209979/VFbviGS5vfq3.jpeg" data-bg-layout="0">
    <div id="main">

        
    
    <div id="snap-bar">
        <div class="profile">
            <a href="/jeffstahl" class="imagelink"><span class="profile-picture" style="background: url(https://d1dcwuzxl9elyu.cloudfront.net/1209979/32x32_ZEMSeB2VfYsD.jpeg);"></span></a>
            <a href="/jeffstahl" class="namelink"><span class="name">Jeff Stahl</span></a>
        </div>
        <div class="filter-type filter-type-all active clickable">
            <span class="icon"></span>
        </div>

        <div class="filter-type filter-type-noremixes clickable">
            <span class="icon"></span>
        </div>


        <div class="filter-type filter-type-text clickable">
            <span class="icon"></span>
        </div>

        <div class="filter-type filter-type-voice clickable">
            <span class="icon"></span>
        </div>

        <div class="filter-type filter-type-photo clickable">
            <span class="icon"></span>
        </div>

        <div class="filter-type filter-type-video clickable">
            <span class="icon"></span>
        </div>

        <div class="filter-type filter-type-live clickable">
            <span class="icon"></span>
        </div>


        <div class="dropdown-wrapper">
            <ul class="close">
<li class="clickable active timeline">
                    <span class="img timeline"></span>
                    <span class="label">Timeline</span>
                </li>

                <li class="clickable favorites">
                    <div>
                        <span class="img favorites"></span>
                        <span class="label">Favorites</span>
                    </div>
                </li>

                <li class="clickable pheedbacks">
                    <span class="img pheedbacks"></span>
                    <span class="label">My Pheedbacks</span>
                </li>
            </ul>
</div>

        <div class="right">
            
    

    <button class="sub-btn big" data-cid="1209979" data-name="Jeff Stahl" data-plan="0" data-monthly="0.99" data-subscribed="0" data-published="1" data-private="false">
        <span class="icon"></span>
            <span class="txt"></span>
    </button>

        </div>
    </div>

        
    
    <div id="header" class="guest ">
        <div id="header-wrapper">
            <div class="sec-left">
                    <div class="logo"><a href="/"><img src="https://d2gnmrx58i4xh3.cloudfront.net/i/pheed-logo-small.png" alt="Pheed Logo"></a></div>

                        
                        <div class="search-wrapper">
                            <input id="search" type="text" class="search" placeholder="Search" value=""><span class="magnify"></span>
                        </div>

                        <a href="/featured" title="Pheed featured channels" id="featured" class="gradient-black">
                            <span class="icon"></span>
                            <span class="text">Featured</span>
                        </a>
            </div>


                <div class="sec-right">
                    
    <div id="header-actions" class="guest">
        <span class="top-text">Download the App!</span>
        <a href="/getapp?ios=1" target="_blank" class="ios" title="Download Pheed iPhone App" alt="Download Pheed iPhone App"></a>
        <a href="/getapp?android=1" target="_blank" class="android" title="Download Pheed Android App" alt="Download Pheed Android App"></a>
        <div class="sep"></div>
        <span class="top-text">Have an account?</span>
        <a href="javascript:void(0);" id="header-login-btn">Log In</a>
    </div>

                </div>
        </div>
    </div>

        <div id="expend">
            <div id="content-wrapper" class="channelview single">
                
    

    <button class="sub-btn big" data-cid="1209979" data-name="Jeff Stahl" data-plan="0" data-monthly="0.99" data-subscribed="0" data-published="1" data-private="false">
        <span class="icon"></span>
            <span class="txt"></span>
    </button>

                <div class="view-channel">
<img src="https://d1dcwuzxl9elyu.cloudfront.net/1209979/32x32_ZEMSeB2VfYsD.jpeg"><a href="/jeffstahl">Jeff Stahl<span class="ph-button lower"><span class="icon"></span></span></a>
</div>
                <hr style="margin: 15px 0 25px 0;">
<div id="pheeds"></div>
            </div>
        </div>


    <script type="text/javascript">
        $(document).ready(function() {
            var pheed = {"status": "2", "is_dislike": false, "owner_monthly": "0.99", "dislikes": "2", "height": "1044", "download": "0", "is_favorite": false, "likes": "344", "replies": "0", "owner": "1209979", "media_id": "10323555", "owner_verified": "1", "id": "47265286", "owner_icon": "https://d1dcwuzxl9elyu.cloudfront.net/1209979/32x32_ZEMSeB2VfYsD.jpeg", "album": "0", "is_like": false, "copyright": "1", "width": "1044", "owner_plan": "0", "owner_url": "jeffstahl", "type": "2", "media_url": "https://d22d7v2y1t140g.cloudfront.net/m_10323555_W8dPBQahkN95.jpg", "body": "Finished my #Rihanna #digitalpainting. Happy birthday.", "owner_bg": "https://d1dcwuzxl9elyu.cloudfront.net/1209979/VFbviGS5vfq3.jpeg", "remixes": "3", "is_remixed": false, "owner_perm_level": "1", "nickname": "Jeff Stahl", "url": "None", "created_at": "1392912657000"};
            PHTimeline.pheeds[pheed.id] = pheed;
            $('#content-wrapper').data('pheed-id', pheed.id);
            PHTimeline.pheeds[pheed.id] = pheed;
            $('#pheeds').html(PHTimeline.createPheed(pheed));
            PHTimeline.bindEvents();
            PHInit.filtersBar();

        
        PHChannel.init(false, false, false, false);
        });
    </script>
</div>

</div>
<div id="footer-wrapper">
    <div id="footer">
        <ul>
<li>
<a href="/">Pheed</a> <span class="cpr">© 2013</span><div class="sep"></div>
</li>
            <li>
<a href="/about">About</a> <div class="sep"></div>
</li>
            <li>
<a href="/terms-of-use">Terms &amp; Conditions</a> <div class="sep"></div>
</li>
            <li><a href="http://heldfsdp.pheed.com/" target="_blank">Help Center</a></li>
        </ul>
</div>

</div>

</body>
</html>