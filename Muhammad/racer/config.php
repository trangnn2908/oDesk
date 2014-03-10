<?php

return array(

	'service' => array(
		'url_prefix' => 'http://ragetankcispotter-jtzr2auyms.elasticbeanstalk.com/spotter/',
		'action' => array(
			'auth' => 'auth?spotterkey=',
			'gettracks' => 'gettracks?spotterkey=',
			'search-tracks' => 'searchtrack?spotterkey=',
			'createevent' => 'createevent?spotterkey=',
			'sendglobalflag' => 'sendglobalflag?spotterkey=',
			'sendlocalflag' => 'sendlocalflag?spotterkey=',
			'sendtext' => 'sendtext?spotterkey='
		),
	),
	'cache' => array(
		'dir' => 'data/cached/',
		'expire_time' => 3600
	)
);
