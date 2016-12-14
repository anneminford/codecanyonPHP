<?php
User::check_permission(1);

if(isset($_GET['status'])) {
	$user_data = new User($_GET['status']);

	/* Check for errors and permissions */
	if(!$token->is_valid()) {
		$_SESSION['error'][] = $language->global->error_message->invalid_token;
	}
	if($_GET['status'] == $account_user_id) {
		$_SESSION['error'][] = $language->admin_users_management->error_message->self_status;
	}
	if(User::get_type($_GET['status']) > 0 && User::get_type($account_user_id) < 2) {
		$_SESSION['error'][] = $language->global->error_message->command_denied;
	}

	if(empty($_SESSION['error'])) {
		if($user_data->active == true) $new_value = 0; else $new_value = 1;

		$database->query("UPDATE `users` SET `active` = {$new_value} WHERE `user_id` = {$_GET['status']}");
		$_SESSION['success'][] = $language->global->success_message->basic;
	} 
	
	display_notifications();

}

if(isset($_GET['delete'])) {
	$user_data = new User($_GET['delete']);

	/* Check for errors and permissions */
	if(!$token->is_valid()) {
		$_SESSION['error'][] = $language->global->error_message->invalid_token;
	}
	if($_GET['delete'] == $account_user_id) {
		$_SESSION['error'][] = $language->admin_users_management->error_message->self_delete;
	}
	if(User::get_type($account_user_id) < 2) {
		$_SESSION['error'][] = $language->global->error_message->command_denied;
	}

	if(empty($_SESSION['error'])) {
		$database->query("DELETE FROM `users` WHERE `user_id` = {$_GET['delete']}");
		
		$_SESSION['success'][] = $language->global->success_message->basic;
	}
	
	display_notifications();
	
}

initiate_html_columns();

?>

<div class="table-responsive">
	<table class="table">
		<thead>
			<tr>
				<th><?php echo $language->admin_users_management->table->username; ?></th>
				<th><?php echo $language->admin_users_management->table->name; ?></th>
				<th><?php echo $language->admin_users_management->table->email; ?></th>
				<th><?php echo $language->admin_users_management->table->ip; ?></th>
				<th><?php echo $language->admin_users_management->table->registration_date; ?></th>
				<th><?php echo $language->admin_users_management->table->actions; ?></th>
			</tr>
		</thead>
		<tbody id="results">
			
		</tbody>
	</table>
</div>

<script>
$(document).ready(function() {
	/* Load first answers */
	showMore(0, 'processing/admin_users_show_more.php', '#results', '#showMoreUsers');
});
</script>