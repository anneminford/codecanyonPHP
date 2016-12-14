<?php
User::logged_in_redirect();

/* Initiate captcha */
include_once 'core/classes/Captcha.php';
$captcha = new Captcha($settings->recaptcha, $settings->public_key, $settings->private_key);

if(!empty($_POST)) {
	/* Clean some posted variables */
	$_POST['username']	= filter_var($_POST['username'], FILTER_SANITIZE_STRING);
	$_POST['name']		= filter_var($_POST['name'], FILTER_SANITIZE_STRING);
	$_POST['email']		= filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

	/* Define some variables */
	$fields = array('username', 'name', 'email' ,'password', 'repeat_password');

	/* Check for any errors */
	foreach($_POST as $key=>$value) {
		if(empty($value) && in_array($key, $fields) == true) {
			$_SESSION['error'][] = $language->global->error_message->empty_fields;
			break 1;
		}
	}
	if(!$captcha->is_valid()) {
		$_SESSION['error'][] = $language->global->error_message->invalid_captcha;
	}
	if(strlen($_POST['username']) < 3 || strlen($_POST['username']) > 32) {
		$_SESSION['error'][] = $language->register->error_message->username_length;
	}
	if(strlen($_POST['name']) < 3 || strlen($_POST['name']) > 32) {
		$_SESSION['error'][] = $language->register->error_message->name_length;
	}
	if(User::x_exists('username', $_POST['username'])) {
		$_SESSION['error'][] = sprintf($language->register->error_message->user_exists, $_POST['username']);
	}
	if(User::x_exists('email', $_POST['email'])) {
		$_SESSION['error'][] = $language->register->error_message->email_exists;
	}
	if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false) {
		$_SESSION['error'][] = $language->register->error_message->invalid_email;
	}
	if(strlen(trim($_POST['password'])) < 6) {
		$_SESSION['error'][] = $language->register->error_message->short_password;
	}
	if($_POST['password'] !== $_POST['repeat_password']) {
		$_SESSION['error'][] = $language->register->error_message->passwords_not_matching;
	}


	/* If there are no errors continue the registering process */
	if(empty($_SESSION['error'])) {
		/* Define some needed variables */ 
		$password 	= User::encrypt_password($_POST['username'], $_POST['password']);
		$active 	= ($settings->email_confirmation == 0) ? "1" : "0";
		$email_code = md5($_POST['email'] + microtime());
		$date = new DateTime();
		$date = $date->format('Y-m-d H:i:s');

		/* Add the user to the database */
		$stmt = $database->prepare("INSERT INTO `users` (`username`, `password`, `email`, `email_activation_code`, `name`, `active`, `ip`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->bind_param('ssssssss', $_POST['username'], $password, $_POST['email'], $email_code, $_POST['name'], $active, $_SERVER['REMOTE_ADDR'], $date);
		$stmt->execute();
		$stmt->close();

		/* If active = 1 then login the user, else send the user an activation email */
		if($active == "1") {
			$_SESSION['user_id'] = User::login($_POST['username'], $password);
			redirect("status/loggedin");
		} else {
			$_SESSION['success'][] = $language->register->success_message->registration;
			sendmail($_POST['email'], $settings->contact_email, $language->register->email->title, sprintf($language->register->email->content, $settings->url, $_POST['email'], $email_code));
			//printf($language->register->email->content, $settings->url, $_POST['email'], $email_code);
		}
	}

	display_notifications();

}

initiate_html_columns();

?>

<h3><?php echo $language->register->header; ?></h3>

<form action="" method="post" role="form">
	<div class="form-group">
		<label><?php echo $language->register->input->username; ?></label>
		<input type="text" name="username" class="form-control" placeholder="<?php echo $language->register->input->username; ?>" />
	</div>

	<div class="form-group">
		<label><?php echo $language->register->input->name; ?></label>
		<input type="text" name="name" class="form-control" placeholder="<?php echo $language->register->input->name; ?>" />
	</div>

	<div class="form-group">
		<label><?php echo $language->register->input->email; ?></label>
		<input type="text" name="email" class="form-control" placeholder="<?php echo $language->register->input->email; ?>" />
	</div>

	<div class="form-group">
		<label><?php echo $language->register->input->password; ?></label>
		<input type="password" name="password" class="form-control" placeholder="<?php echo $language->register->input->password; ?>" />
	</div>

	<div class="form-group">
		<label><?php echo $language->register->input->repeat_password; ?></label>
		<input type="password" name="repeat_password" class="form-control" placeholder="<?php echo $language->register->input->repeat_password; ?>" />
	</div>

	<div class="form-group">
		  <?php $captcha->display(); ?>
	</div>

	<div class="form-group">
		<button type="submit" name="submit" class="btn btn-primary col-lg-4"><?php echo $language->global->submit_button; ?></button><br /><br />
	</div>

</form>
