<?php
User::check_permission(1);

/* Check if report exists */
if(!User::x_exists('id', $_GET['report_id'], 'reports')) {
	$_SESSION['error'][] = $language->admin_report_edit->error_message->invalid_report;
	User::get_back('admin/reports-management');
}

/* Get $report data from the database */
$stmt = $database->prepare("SELECT * FROM `reports` WHERE `id` = ?");
$stmt->bind_param('s', $_GET['report_id']);
$stmt->execute();
bind_object($stmt, $report);
$stmt->fetch();
$stmt->close();

/* Convert the type of the report into text */
switch($report->type) {
	case '0' : $report->type_text = 'Comment';		$report->type_db_from = 'comments';	$report->type_db_where = 'id'; break;
	case '1' : $report->type_text = 'Quote';		$report->type_db_from = 'quotes';	$report->type_db_where = 'quote_id'; break;
	case '2' : $report->type_text = 'User';			$report->type_db_from = 'users';	$report->type_db_where = 'user_id'; break;
}

/* Check if the admin wants to delete the report or the reported */
if(isset($_GET['delete']) && ($_GET['delete'] == 'reported' || $_GET['delete'] == 'report')) {

	if($_GET['delete'] == 'reported') {

		/* Delete the reported $type and also remove the report */
		if($report->type == 1) {
			Quotes::delete_quote($report->id);
		} else {
			$database->query("DELETE FROM `{$report->type_db_from}` WHERE `{$report->type_db_where}` = {$report->reported_id}");
		}
		
		$database->query("DELETE FROM `reports` WHERE `id` = {$report->id}");

	} else

	if($_GET['delete'] == 'report') {

		/* Delete the report */
		$database->query("DELETE FROM `reports` WHERE `id` = {$report->id}");

	}
	
	/* Set a success message and redirect */
	$_SESSION['success'][] = $language->global->success_message->basic;
	redirect('admin/reports-management');
}

initiate_html_columns();

?>

<h3><?php echo $language->admin_report_edit->header; ?></h3>


<div class="form-group">
	<label><?php echo $language->admin_report_edit->input->user_profile; ?></label>
	<p class="form-control-static"><?php echo User::get_profile_link($report->user_id); ?></p>
</div>

<div class="form-group">
	<label><?php echo $language->admin_report_edit->input->date; ?></label>
	<input type="text" class="form-control" value="<?php echo $report->date; ?>" disabled="true" />
</div>

<div class="form-group">
	<label><?php echo $language->admin_report_edit->input->type; ?></label>
	<input type="text" class="form-control" value="<?php echo $report->type_text; ?>" disabled="true" />
</div>

<div class="form-group">
	<label><?php echo $language->admin_report_edit->input->reported_id; ?></label>
	<input type="text" name="email" class="form-control" value="<?php echo $report->reported_id; ?>" disabled="true" />
</div>

<div class="form-group">
	<label><?php printf($language->admin_report_edit->input->reported, $report->type_text); ?></label>
	<?php
	switch($report->type) {
		case '0' :
			$result = $database->query("SELECT * FROM `comments` WHERE `id` = {$report->reported_id} AND `type` = {$report->type}");
			$data = $result->fetch_object();
		?>
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="media">
					<div class="media-body">
						<h4 class="media-heading">
							<?php echo User::get_profile_link($data->user_id); ?>
						</h4>

						<?php echo $data->comment; ?>

						<br />
						<span class="text-muted"><?php echo $data->date_added; ?></span>

					</div>
				</div>
			</div>
		</div>
		<?php
		break;

		case '1' :
			$result = $database->query("SELECT `quote_id`, `content` FROM `quotes` WHERE `quote_id` = {$report->reported_id}");
			$quote = $result->fetch_object();
		?>
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="media">
					<div class="media-body">
						<?php echo $quote->content; ?>
					</div>
				</div>
			</div>
		</div>
		<?php
		break;

		case '2' :
			echo '<p class="form-control-static">' . User::get_profile_link($report->user_id) . '</p>';
		break;
	}
	?>		
</div>

<div class="form-group">
	<label><?php echo $language->admin_report_edit->input->reason; ?></label>
	<textarea class="form-control" rows="4" style="resize:none"; disabled="true"><?php echo $report->message; ?></textarea>
</div>



<div class="form-group">
	<a href="admin/edit-report/<?php echo $_GET['report_id']; ?>?delete=reported" data-confirm="<?php echo $language->global->info_message->confirm_delete; ?>" >
		<button type="button" class="btn btn-default"><?php printf($language->admin_report_edit->button->delete, $report->type_text); ?></button>
	</a>

	<a href="admin/edit-report/<?php echo $_GET['report_id']; ?>?delete=report" data-confirm="<?php echo $language->global->info_message->confirm_delete; ?>">
		<button type="button" class="btn btn-primary"><?php printf($language->admin_report_edit->button->delete, 'report'); ?></button>
	</a>

	<br /><br />
</div>
