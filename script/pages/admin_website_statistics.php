<?php
User::check_permission(1);

initiate_html_columns();
?>

<div class="row">
	<div class="col-md-6">
		
		<?php
		$result = $database->query("
			SELECT 
				(SELECT COUNT(*) FROM `categories`) AS `categories_count`,
				(SELECT COUNT(*) FROM `comments`) AS `comments_count`,
				(SELECT COUNT(*) FROM `reports`) AS `reports_count`,
				(SELECT COUNT(*) FROM `quotes`) AS `quotes_count`,
				(SELECT COUNT(*) FROM `users`) AS `users_count`
			");
		$total_data = $result->fetch_object();
		?>

		<h4><?php echo $language->admin_website_statistics->header; ?></h4>

		<table class="table-fixed-full table-statistics">
			<tr>
				<td style="width:50%"><?php echo $language->admin_website_statistics->table->categories; ?></td>
				<td style="width:50%"><kbd><?php echo $total_data->categories_count; ?></kbd></td>
			</tr>
			<tr>
				<td style="width:50%"><?php echo $language->admin_website_statistics->table->comments; ?></td>
				<td style="width:50%"><kbd><?php echo $total_data->comments_count; ?></kbd></td>
			</tr>
			<tr>
				<td style="width:50%"><?php echo $language->admin_website_statistics->table->reports; ?></td>
				<td style="width:50%"><kbd><?php echo $total_data->reports_count; ?></kbd></td>
			</tr>
			<tr>
				<td style="width:50%"><?php echo $language->admin_website_statistics->table->quotes; ?></td>
				<td style="width:50%"><kbd><?php echo $total_data->quotes_count; ?></kbd></td>
			</tr>
			<tr>
				<td style="width:50%"><?php echo $language->admin_website_statistics->table->accounts; ?></td>
				<td style="width:50%"><kbd><?php echo $total_data->users_count; ?></kbd></td>
			</tr>
		</table>

	</div>

	<div class="col-md-6">
		
		<?php
		$result = $database->query("
			SELECT 
				(SELECT COUNT(*) FROM `comments` WHERE YEAR(`date_added`) = YEAR(CURDATE()) AND MONTH(`date_added`) = MONTH(CURDATE())) AS `comments_count`,
				(SELECT COUNT(*) FROM `reports` WHERE YEAR(`date`) = YEAR(CURDATE()) AND MONTH(`date`) = MONTH(CURDATE())) AS `reports_count`,
				(SELECT COUNT(*) FROM `quotes` WHERE YEAR(`date_added`) = YEAR(CURDATE()) AND MONTH(`date_added`) = MONTH(CURDATE())) AS `quotes_count`,
				(SELECT COUNT(*) FROM `users` WHERE YEAR(`date`) = YEAR(CURDATE()) AND MONTH(`date`) = MONTH(CURDATE())) AS `users_count`
			");
		$monthly_data = $result->fetch_object();
		?>

		<h4><?php echo $language->admin_website_statistics->header2; ?></h4>

		<table class="table-fixed-full table-statistics">
			<tr>
				<td style="width:50%"><?php echo $language->admin_website_statistics->table->comments; ?></td>
				<td style="width:50%"><kbd><?php echo $monthly_data->comments_count; ?></kbd></td>
			</tr>
			<tr>
				<td style="width:50%"><?php echo $language->admin_website_statistics->table->reports; ?></td>
				<td style="width:50%"><kbd><?php echo $monthly_data->reports_count; ?></kbd></td>
			</tr>
			<tr>
				<td style="width:50%"><?php echo $language->admin_website_statistics->table->quotes; ?></td>
				<td style="width:50%"><kbd><?php echo $monthly_data->quotes_count; ?></kbd></td>
			</tr>
			<tr>
				<td style="width:50%"><?php echo $language->admin_website_statistics->table->accounts; ?></td>
				<td style="width:50%"><kbd><?php echo $monthly_data->users_count; ?></kbd></td>
			</tr>
		</table>

	</div>
</div>

<br />


<div class="row">
	<div class="col-md-6">

		<?php
		$result = $database->query("
			SELECT
				(SELECT COUNT(`user_id`) FROM `users` WHERE YEAR(`date`) = YEAR(CURDATE()) AND MONTH(`date`) = MONTH(CURDATE()) AND DAY(`date`) = DAY(CURDATE())) AS `new_users_today`,
				(SELECT COUNT(`user_id`) FROM `users` WHERE `type` = '2') AS `owner_users`,
				(SELECT COUNT(`user_id`) FROM `users` WHERE `type` = '1') AS `admin_users`,
				(SELECT COUNT(`user_id`) FROM `users` WHERE `private` = '1') AS `private_users`,
				(SELECT COUNT(`user_id`) FROM `users` WHERE `active` = '1') AS `confirmed_users`,
				(SELECT COUNT(`user_id`) FROM `users` WHERE `active` = '0') AS `unconfirmed_users`
			");
		$users_data = $result->fetch_object();
		?>

		<h4><?php echo $language->admin_website_statistics->header3; ?></h4>

		<table class="table-fixed-full table-statistics">
			<tr>
				<td style="width:50%"><?php echo $language->admin_website_statistics->table->new_accounts_today; ?></td>
				<td style="width:50%"><kbd><?php echo $users_data->new_users_today; ?></kbd></td>
			</tr>

			<tr>
				<td style="width:50%"><?php echo $language->admin_website_statistics->table->online_accounts; ?></td>
				<td style="width:50%"><kbd><?php echo User::online_users(30); ?></kbd></td>
			</tr>
			<tr>
				<td colspan="2">
					<?php
					$result = $database->query("SELECT `name`, `username` FROM `users` WHERE `last_activity` > UNIX_TIMESTAMP() - 30");
					
					while($users = $result->fetch_object())
						echo '<a href="profile/' . $users->username . '">' . $users->name . '</a>, ';

					?>
				</td>
			</tr>

			<tr>
				<td style="width:50%"><?php echo $language->admin_website_statistics->table->active_accounts_today; ?></td>
				<td style="width:50%"><kbd><?php echo User::online_users(86400); ?></kbd></td>
			</tr>
			<tr>
				<td colspan="2">
					<?php
					$result = $database->query("SELECT `name`, `username` FROM `users` WHERE `last_activity` > UNIX_TIMESTAMP() - 86400");
					
					while($users = $result->fetch_object())
						echo '<a href="profile/' . $users->username . '">' . $users->name . '</a>, ';

					?>
				</td>
			</tr>

			<tr>
				<td style="width:50%"><?php echo $language->admin_website_statistics->table->owners; ?></td>
				<td style="width:50%"><kbd><?php echo $users_data->owner_users; ?></kbd></td>
			</tr>

			<tr>
				<td style="width:50%"><?php echo $language->admin_website_statistics->table->admins; ?></td>
				<td style="width:50%"><kbd><?php echo $users_data->admin_users; ?></kbd></td>
			</tr>

			<tr>
				<td style="width:50%"><?php echo $language->admin_website_statistics->table->private_accounts; ?></td>
				<td style="width:50%"><kbd><?php echo $users_data->private_users; ?></kbd></td>
			</tr>

			<tr>
				<td style="width:50%"><?php echo $language->admin_website_statistics->table->confirmed_accounts; ?></td>
				<td style="width:50%"><kbd><?php echo $users_data->confirmed_users; ?></kbd></td>
			</tr>

			<tr>
				<td style="width:50%"><?php echo $language->admin_website_statistics->table->unconfirmed_accounts; ?></td>
				<td style="width:50%"><kbd><?php echo $users_data->unconfirmed_users; ?></kbd></td>
			</tr>
		</table>

	</div>

	<div class="col-md-6">

		<?php
		$result = $database->query("
			SELECT
				(SELECT COUNT(`quote_id`) AS `count` FROM `quotes` WHERE YEAR(`date_added`) = YEAR(CURDATE()) AND MONTH(`date_added`) = MONTH(CURDATE()) AND DAY(`date_added`) = DAY(CURDATE())) AS `new_quotes_today`,
				(SELECT COUNT(`quote_id`) AS `count` FROM `quotes` WHERE `image` = '') AS `no_image_quotes`,
				(SELECT COUNT(`quote_id`) AS `count` FROM `quotes` WHERE `image` != '') AS `image_quotes`,
				(SELECT COUNT(`quote_id`) AS `count` FROM `quotes` WHERE `active` = '1') AS `active_quotes`,
				(SELECT COUNT(`quote_id`) AS `count` FROM `quotes` WHERE `active` = '0') AS `inactive_quotes`
			");
		$quotes_data = $result->fetch_object();
		?>

		<h4><?php echo $language->admin_website_statistics->header4; ?></h4>

		<table class="table-fixed-full table-statistics">
			<tr>
				<td style="width:50%"><?php echo $language->admin_website_statistics->table->new_quotes_today; ?></td>
				<td style="width:50%"><kbd><?php echo $quotes_data->new_quotes_today; ?></kbd></td>
			</tr>

			<tr>
				<td style="width:50%"><?php echo $language->admin_website_statistics->table->no_image_quotes; ?></td>
				<td style="width:50%"><kbd><?php echo $quotes_data->no_image_quotes; ?></kbd></td>
			</tr>

			<tr>
				<td style="width:50%"><?php echo $language->admin_website_statistics->table->image_quotes; ?></td>
				<td style="width:50%"><kbd><?php echo $quotes_data->image_quotes; ?></kbd></td>
			</tr>

			<tr>
				<td style="width:50%"><?php echo $language->admin_website_statistics->table->active_quotes; ?></td>
				<td style="width:50%"><kbd><?php echo $quotes_data->active_quotes; ?></kbd></td>
			</tr>

			<tr>
				<td style="width:50%"><?php echo $language->admin_website_statistics->table->inactive_quotes; ?></td>
				<td style="width:50%"><kbd><?php echo $quotes_data->inactive_quotes; ?></kbd></td>
			</tr>

		</table>

	</div>
</div>