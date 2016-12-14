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
		if(User::x_exists('email', $_POST['email']) && !User::user_active(User::x_to_y('email', 'username', $_POST['email']))) {
			/* Define some variables */
			$user_id 	= User::x_to_y('email', 'user_id', $_POST['email']);
			$email_code = md5($_POST['email'] + microtime());

			/* Update the current activation email */
			$database->query("UPDATE `users` SET `email_activation_code` = '{$email_code}' WHERE `user_id` = {$user_id}");

			/* Send the email */
			sendmail($_POST['email'], $settings->contact_email, $language->resend_activation->email->title, sprintf($language->resend_activation->email->content, $settings->url, $_POST['email'], $email_code));
			//printf($language->resend_activation->email->content, $settings->url, $_POST['email'], $email_code);
		}

		/* Store success message */
		$_SESSION['success'][] = $language->resend_activation->notice_message->success;
	}

	display_notifications();
	
}

initiate_html_columns();

?>

<h3><?php echo $language->resend_activation->header; ?></h3>

<form action="" method="post" role="form">
	<div class="form-group">
		<label><?php echo $language->resend_activation->input->email; ?></label>
		<input type="text" name="email" class="form-control" />
	</div>

	<div class="form-group">
		  <?php $captcha->display(); ?>
	</div>

	<div class="form-group">
		<button type="submit" name="submit" class="btn btn-primary col-lg-4"><?php echo $language->global->submit_button; ?></button><br /><br />
	</div>

</form>