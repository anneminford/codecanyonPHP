<?php
include '../core/init.php';

/* Initiate captcha */
include_once '../core/classes/Captcha.php';
$captcha = new Captcha($settings->recaptcha, $settings->public_key, $settings->private_key);

/* Process variables */
$_POST['message']		= filter_var($_POST['message'], FILTER_SANITIZE_STRING);
$_POST['type']			= (int) $_POST['type'];
$_POST['reported_id']	= (int) $_POST['reported_id'];


/* Check for errors */
$stmt = $database->prepare("SELECT `id` FROM `reports` WHERE `type` = ? AND `reported_id` = ?");
$stmt->bind_param('ss', $_POST['type'], $_POST['reported_id']);
$stmt->execute();
$stmt->store_result();
$num_rows = $stmt->num_rows;
$stmt->fetch();
$stmt->close();

if($num_rows) {
	$errors[] = $languages['errors']['already_reported'];
}
if(!$captcha->is_valid()) {
	$errors[] = $language->global->error_message->invalid_captcha;
}
if(!$token->is_valid()) {
	$errors[] = $language->global->error_message->invalid_token;
}
if(strlen($_POST['message']) > 512) {
	$errors[] = $language->process_reports->error_message->long_message;
}
if(strlen($_POST['message']) < 5) {
	$errors[] = $language->process_reports->error_message->short_message;
}

if(empty($errors)) {
	$date = new DateTime();
	$date = $date->format('Y-m-d H:i:s');
	
	$stmt = $database->prepare("INSERT INTO `reports` (`user_id`, `type`, `reported_id`, `message`, `date`) VALUES (?, ?, ?, ?, ?)");
	$stmt->bind_param('sssss', $account_user_id, $_POST['type'], $_POST['reported_id'], $_POST['message'], $date);
	$stmt->execute();
	$stmt->close();

	echo "success";
} else echo output_errors($errors);
?>