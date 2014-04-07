<?php

return array(

	'service' => array(
		'url_prefix' => 'http://ragetankcispotter-jtzr2auyms.elasticbeanstalk.com/',
		'action' => array(
			'auth' => 'spotter/auth?spotterkey=',
			'gettracks' => 'spotter/gettracks?spotterkey=',
			'search-tracks' => 'spotter/searchtrack?spotterkey=',
			'createevent' => 'spotter/createevent?spotterkey=',
			'sendglobalflag' => 'spotter/sendglobalflag?spotterkey=',
			'sendlocalflag' => 'spotter/sendlocalflag?spotterkey=',
			'sendtext' => 'feed/sendtext?spotterkey='
		),
	),
	'cache' => array(
		'dir' => 'data/cached/',
		'expire_time' => 3600
	)
);
