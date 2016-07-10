<?php

header("Access-Control-Allow-Origin: *");
include "controllers/home.php";
include "controllers/login.php";
include "controllers/logout.php";
include "controllers/sign_up.php";
include "controllers/test_api.php";
include "route.php";



$route=new route();
$route->add('/home',"home");
$route->add('/test-api',"test_api_post");
$route->add('/login','login_test');
$route->add('/signup','sign_up');
$route->add('/logout','logout');

$route->submit();
