<?php
User::logged_in_redirect();

/* Initiate captcha */
include_once 'core/classes/Captcha.php';
$captcha = new Captcha($settings->recaptcha, $settings->public_key, $settings->private_key);


if(!empty($_POST)) {
	/* Clean the posted variable */
	$_POST['email'] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

	/* Check for any errors */
	if(!$captcha->is_valid()) {
		$_SESSION['error'][] = $language->global->error_message->invalid_captcha;
	}

	/* If there are no errors, resend the activation link */
	if(empty($_SESSION['error'])) {
		if(User::x_exists('email', $_POST['email'])) {
			/* Define some variables */
			$user_id 			= User::x_to_y('email', 'user_id', $_POST['email']);
			$lost_password_code = md5($_POST['email'] + microtime());

			/* Update the current activation email */
			$database->query("UPDATE `users` SET `lost_password_code` = '{$lost_password_code}' WHERE `user_id` = {$user_id}");

			/* Send the email */
			sendmail($_POST['email'],  $settings->contact_email, $language->lost_password->email->title, sprintf($language->lost_password->email->content, $settings->url, $_POST['email'], $lost_password_code));
			//printf($language->lost_password->email->content, $settings->url, $_POST['email'], $lost_password_code);
		}

		/* Set success message */
		$_SESSION['success'][] = $language->lost_password->notice_message->success;
	}

	display_notifications();

}

initiate_html_columns();

?>

<h3><?php echo $language->lost_password->header; ?></h3>

<form action="" method="post" role="form">
	<div class="form-group">
		<label><?php echo $language->lost_password->input->email; ?></label>
		<input type="text" name="email" class="form-control" />
	</div>

	<div class="form-group">
		  <?php $captcha->display(); ?>
	</div>

	<div class="form-group">
		<button type="submit" name="submit" class="btn btn-primary col-lg-4"><?php echo $language->global->submit_button; ?></button><br /><br />
	</div>

</form>