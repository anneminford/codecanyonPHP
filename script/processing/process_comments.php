<?php
include '../core/init.php';

/* Initiate captcha */
include_once '../core/classes/Captcha.php';
$captcha = new Captcha($settings->recaptcha, $settings->public_key, $settings->private_key);

/* Process variables */
@$_POST['comment'] = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);

/* Define the captcha variable */
if(!isset($_POST['delete']))

/* Check for errors */
if(!isset($_POST['delete'])) {

	if(!$captcha->is_valid()) {
		$errors[] = $language->global->error_message->invalid_captcha;
	}
	if(!$token->is_valid()) {
		$errors[] = $language->global->error_message->invalid_token;
	}
	if(strlen($_POST['comment']) > 512) {
		$errors[] = $language->process_comments->error_message->long_message;
	}
	if(strlen($_POST['comment']) < 5) {
		$errors[] = $language->process_comments->error_message->short_message;
	}

} else {
	if(!User::is_admin($account_user_id)) {
		$errors[] = $language->global->error_message->command_denied;
	}
}


if(empty($errors)) {
	$date = new DateTime();
	$date = $date->format('Y-m-d H:i:s');

	if(!isset($_POST['delete'])) {
		$stmt = $database->prepare("INSERT INTO `comments` (`quote_id`, `user_id`, `comment`, `date_added`) VALUES (?, ?, ?, ?)");
		$stmt->bind_param('ssss',  $_SESSION['quote_id'], $account_user_id, $_POST['comment'], $date);
		$stmt->execute();
		$stmt->close();
	} else {
		$stmt = $database->prepare("DELETE FROM `comments` WHERE `id` = ?");
		$stmt->bind_param('s', $_POST['reported_id']);
		$stmt->execute();
		$stmt->close();
	}

	echo "success";
} else echo output_errors($errors);
?>