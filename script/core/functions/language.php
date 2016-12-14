<?php

/* Set the Languages directory */
$path = (file_exists('languages/english.php')) ? 'languages/' : '../languages/';

/* Set the default language */
$default_language = 'english';

/* Establish the current language variable */
$lang = $default_language;

/* Determine all the langauges available in the directory */
$files = glob($path . '*.php');

$languages = preg_replace('(' . $path . '|.php)', '', $files);

/* If the cookie is set and the language file exists, override the default language */
if(isset($_COOKIE['language']) && in_array($_COOKIE['language'], $languages)) $lang = $_COOKIE['language']; 

/* Check if the language wants to be checked via the GET variable */
if(isset($_GET['language'])) {
	$_GET['language'] = filter_var($_GET['language'], FILTER_SANITIZE_STRING);

	/* Check if the requested language exists and set it if needed */
	if(in_array($_GET['language'], $languages)) {
		setcookie('language', $_GET['language'], time()+60*60*24*3);
		$lang = $_GET['language'];
	}
}

/* Include the language file */
include $path . $lang . '.php'; 

?>