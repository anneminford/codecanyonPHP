<?php
ob_start();
session_start();
error_reporting(E_ALL);

include_once 'database/connect.php';
include_once 'functions/language.php';
include_once 'functions/general.php';
include_once 'classes/User.php';
include_once 'classes/Pagination.php';
include_once 'classes/Quotes.php';
include_once 'classes/Csrf.php';

/* Initialize variables */
$errors 				= array();
$settings 				= settings_data();
$token 					= new CsrfProtection();
$no_panel_pages			= array('index', 'search', 'authors', 'countries', 'my_favorites', 'my_quotes', 'quotes', 'profile', 'tag', 'quote', 'category', 'author', 'country', 'admin_categories_management', 'admin_authors_management', 'admin_quotes_management');
$full_width_pages 		= array('profile');
$pre_processing_pages	= array('quote', 'profile', 'category', 'author', 'country', 'tag');

/* Set the default timezone */
date_default_timezone_set($settings->time_zone);

/* If user is logged in get his data */
if(User::logged_in()) {
	$account_user_id = (isset($_SESSION['user_id']) == true) ? $_SESSION['user_id'] : $_COOKIE['user_id'];
	$account = new User($account_user_id);

	/* Update last activity */
	$database->query("UPDATE `users` SET `last_activity` = unix_timestamp() WHERE `user_id` = {$account_user_id}");
}


/* Include the preprocessing if needed */
if(isset($_GET['page']) && in_array($_GET['page'], $pre_processing_pages)) include 'processing/' . $_GET['page'] . '.php';

/* Establish the title of the page */
include_once 'functions/titles.php';
?>
