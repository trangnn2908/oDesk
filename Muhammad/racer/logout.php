<?php

session_name('racer_web_app');
session_start();

require_once 'service.php';

Service::instance()->logout();

Service::redirect('login.php', 'Logout success!');
