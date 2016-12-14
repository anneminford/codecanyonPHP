<?php
User::check_permission(2);

/* Check if user exists */
if(!User::x_exists('user_id', $_GET['user_id'])) {
	$_SESSION['error'][] = $language->admin_user_edit->error_message->invalid_account;
	User::get_back('admin/users-management');
}

if(!empty($_POST)) {
	/* Filter some the variables */
	$_POST['name']		= filter_var($_POST['name'], FILTER_SANITIZE_STRING);
	$_POST['email']		= filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
	$_POST['website']	= filter_var($_POST['website'], FILTER_VALIDATE_URL);
	$_POST['location']	= filter_var($_POST['location'], FILTER_SANITIZE_STRING);
	$_POST['about']		= filter_var($_POST['about'], FILTER_SANITIZE_STRING);
	$_POST['facebook']	= filter_var($_POST['facebook'], FILTER_SANITIZE_STRING);
	$_POST['twitter']	= filter_var($_POST['twitter'], FILTER_SANITIZE_STRING);
	$_POST['googleplus']= filter_var($_POST['googleplus'], FILTER_SANITIZE_STRING);

	/* Check for any errors */
	if(strlen($_POST['name']) < 3 || strlen($_POST['name']) > 32) {
		$_SESSION['error'][] = $language->admin_user_edit->error_message->name_length;
	}
	if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false) {
		$_SESSION['error'][] = $language->admin_user_edit->error_message->invalid_email;
	}
	if(User::x_exists('email', $_POST['email']) == true && $_POST['email'] !== User::x_to_y('user_id', 'email', $_GET['user_id'])) {
		$_SESSION['error'][] = $language->admin_user_edit->error_message->email_exists;
	}
	if(strlen($_POST['about']) > 128) {
		$_SESSION['error'][] = $language->admin_user_edit->error_message->long_about;
	}
	if(strlen($_POST['location']) > 64) {
		$_SESSION['error'][] = $language->admin_user_edit->error_message->long_location;
	}
	if(!empty($_POST['new_password']) && !empty($_POST['repeat_password'])) {
		if(strlen(trim($_POST['new_password'])) < 6) {
	        $_SESSION['error'][] = $language->admin_user_edit->error_message->short_password;
	    }
	    if($_POST['new_password'] !== $_POST['repeat_password']) {
	        $_SESSION['error'][] = $language->admin_user_edit->error_message->passwords_not_matching;
	    }
	}

	if(empty($_SESSION['error'])) {

		/* Update the basic user settings */
		$stmt = $database->prepare("UPDATE `users` SET `name` = ?, `email` = ?, `website` = ?, `location` = ?, `about` = ?, `facebook` = ?, `twitter` = ?, `googleplus` = ? WHERE `user_id` = {$_GET['user_id']}");
		$stmt->bind_param('ssssssss', $_POST['name'], $_POST['email'], $_POST['website'], $_POST['location'], $_POST['about'], $_POST['facebook'], $_POST['twitter'], $_POST['googleplus']);
		$stmt->execute(); 
		$stmt->close();

		/* Update the password if set */
		if(!empty($_POST['new_password']) && !empty($_POST['repeat_password'])) {
			$new_password = User::encrypt_password(User::x_to_y('user_id', 'username', $_GET['user_id']), $_POST['new_password']);

			$stmt = $database->prepare("UPDATE `users` SET `password` = ?  WHERE `user_id` = {$_GET['user_id']}");
			$stmt->bind_param('s', $new_password);
			$stmt->execute();
			$stmt->close();
		}

		/* Update the users persmissions if set */
		if(User::get_type($account_user_id) > 1) {
			$stmt = $database->prepare("UPDATE `users` SET `type` = ? WHERE `user_id` = {$_GET['user_id']}");
			$stmt->bind_param('s', $_POST['type']);
			$stmt->execute();
			$stmt->close();
		}
		
		$_SESSION['success'][] = $language->global->success_message->basic;
	}

	display_notifications();
}

$profile_account = new User($_GET['user_id']);
initiate_html_columns();

?>

<h3><?php echo $language->admin_user_edit->header; ?></h3>

<form action="" method="post" role="form">
	<div class="form-group">
		<label><?php echo $language->admin_user_edit->input->username; ?></label>
		<input type="text" class="form-control" value="<?php echo $profile_account->username; ?>" disabled="true"/>
	</div>

	<div class="form-group">
		<label><?php echo $language->admin_user_edit->input->name; ?></label>
		<input type="text" name="name" class="form-control" value="<?php echo $profile_account->name; ?>" />
	</div>

	<div class="form-group">
		<label><?php echo $language->admin_user_edit->input->email; ?></label>
		<input type="text" name="email" class="form-control" value="<?php echo $profile_account->email; ?>" />
	</div>

	<div class="form-group">
		<label><?php echo $language->admin_user_edit->input->website; ?></label>
		<input type="text" name="website" class="form-control" value="<?php echo $profile_account->website; ?>" />
	</div>

	<div class="form-group">
		<label><?php echo $language->admin_user_edit->input->location; ?></label>
		<input type="text" name="location" class="form-control" value="<?php echo $profile_account->location; ?>" />
	</div>

	<div class="form-group">
			<label><?php echo $language->admin_user_edit->input->about; ?></label>
		<input type="text" name="about" class="form-control" value="<?php echo $profile_account->about; ?>" />
	</div>

	<?php if(User::get_type($account_user_id) > 1) { ?>
    <div class="form-group">
		<label><?php echo $language->admin_user_edit->input->type; ?></label>
		<p class="help-block"><?php echo $language->admin_user_edit->input->type_help; ?></p>
		<input type="text" name="type" class="form-control" value="<?php echo $profile_account->type; ?>" />
	</div>
	<?php } ?>

    <hr />
    <h3><?php echo $language->admin_user_edit->header2; ?></h3>
    <p class="help-block"><?php echo $language->admin_user_edit->header2_help; ?></p>

	<div class="form-group">
   		<label><?php echo $language->admin_user_edit->input->facebook; ?></label>
    	<input type="text" name="facebook" class="form-control" value="<?php echo $profile_account->facebook; ?>" />
    </div>

    <div class="form-group">
   		<label><?php echo $language->admin_user_edit->input->twitter; ?></label>
    	<input type="text" name="twitter" class="form-control" value="<?php echo $profile_account->twitter; ?>" />
    </div>

    <div class="form-group">
   		<label><?php echo $language->admin_user_edit->input->googleplus; ?></label>
    	<input type="text" name="googleplus" class="form-control" value="<?php echo $profile_account->googleplus; ?>" />
    </div>

    <h3><?php echo $language->admin_user_edit->header3; ?></h3>
    <p class="help-block"><?php echo $language->admin_user_edit->header3_help; ?></p>

	<div class="form-group">
   		<label><?php echo $language->admin_user_edit->input->new_password; ?></label>
    	<input type="password" name="new_password" class="form-control" />
    </div>

    <div class="form-group">
   		<label><?php echo $language->admin_user_edit->input->repeat_password; ?></label>
    	<input type="password" name="repeat_password" class="form-control" />
    </div>


	<div class="form-group">
		<button type="submit" name="submit" class="btn btn-primary col-lg-4"><?php echo $language->global->submit_button; ?></button><br /><br />
	</div>

</form>