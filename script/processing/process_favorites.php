<?php
include '../core/init.php';

$_POST['quote_id'] = (int) $_POST['quote_id'];

if(User::logged_in()) {
	$query = $database->query("SELECT `id` FROM `favorites` WHERE `user_id` = {$account_user_id} AND `quote_id` = {$_POST['quote_id']}");
	if($query->num_rows > 0) {
		$database->query("DELETE FROM `favorites` WHERE `user_id` = {$account_user_id} AND `quote_id` = {$_POST['quote_id']}");
		$database->query("UPDATE `quotes` SET `favorites` = `favorites` - 1 WHERE `quote_id` = {$_POST['quote_id']}");
		
		echo "unfavorited";
	} else {
		$database->query("INSERT INTO `favorites` (`user_id`, `quote_id`) VALUES ({$account_user_id}, {$_POST['quote_id']})");
		$database->query("UPDATE `quotes` SET `favorites` = `favorites` + 1 WHERE `quote_id` = {$_POST['quote_id']}");

		echo "favorited";
	}

} else echo "not logged in";
?>