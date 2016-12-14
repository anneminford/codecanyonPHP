<?php
$_GET['country_code'] = filter_var($_GET['country_code'], FILTER_SANITIZE_STRING);

$country_exists = (User::x_exists('country_code', $_GET['country_code'], 'authors'));

/* Check if country exists and the GET variable is not empty*/
if(empty($_GET['country_code']) || !$country_exists) User::get_back();
?>