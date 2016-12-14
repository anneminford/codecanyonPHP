<?php
/* Fetch the users data & Set a session with the profile id for the form */
$_SESSION['profile_user_id'] = $profile_user_id = User::x_to_y('username', 'user_id', $_GET['username']);

/* Check if user exists */
$user_exists = ($profile_user_id !== NULL);

/* If user exists -> get his profile data */
if($user_exists) {
	$profile_account = new User($profile_user_id);
}

/* Check if user exists, if not -> display error */
if(!$user_exists) {
	$_SESSION['error'][] = $language->error_message->invalid_account;
} else
/* Check if profile is private */
if(
	($profile_account->private && !User::logged_in()) ||
	($profile_account->private && User::logged_in() && $account_user_id != $profile_user_id)
) {
	/* Set error message and redirect */
	$_SESSION['error'][] = $language->error_message->private_profile;
	User::get_back('index');
}

?>