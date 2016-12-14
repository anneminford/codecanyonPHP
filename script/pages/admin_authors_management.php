<?php
User::check_permission(1);

if(isset($_GET['delete'])) {

	/* Check for errors */
	if(!$token->is_valid()) {
		$_SESSION['error'][] = $language->global->error_message->invalid_token;
	}

	if(empty($_SESSION['error'])) {

		/* Delete authors and all quotes from that author */
		$database->query("DELETE FROM `authors` WHERE `author_id` = {$_GET['delete']}");

		$result = $database->query("SELECT `quote_id` FROM `quotes` WHERE `author_id` = {$_GET['delete']}");
		while($quotes = $result->fetch_object()) Quotes::delete_quote($quotes->quote_id);

		/* Set the success message & redirect*/
		$_SESSION['success'][] = $language->global->success_message->basic;
		redirect('admin/authors-management');
	}
}

if(!empty($_POST)) {
	/* Define some variables */
	$_POST['name']				 		= filter_var($_POST['name'], FILTER_SANITIZE_STRING);
	$_POST['url']						= generateSlug(filter_var($_POST['url'], FILTER_SANITIZE_STRING));
	$_POST['country_code']				= (country_check(0, $_POST['country_code'])) ? $_POST['country_code'] : 'US';
	$_POST['birth_year']				= (int)$_POST['birth_year'];
	$_POST['death_year']				= (int)$_POST['death_year'];

	$required_fields = array('name', 'url', 'country_code');

	/* Check for the required fields */
	foreach($_POST as $key=>$value) {
		if(empty($value) && in_array($key, $required_fields) == true) {
			$_SESSION['error'][] = $language->global->error_message->empty_fields;
			break 1;
		}
	}

	/* If there are no errors continue the updating process */
	if(empty($_SESSION['error'])) {

		$stmt = $database->prepare("INSERT INTO `authors` (`name`, `url`, `country_code`, `birth_year`, `death_year`) VALUES (?, ?, ?, ?, ?)");
		$stmt->bind_param('sssss', $_POST['name'], $_POST['url'], $_POST['country_code'], $_POST['birth_year'], $_POST['death_year']);
		$stmt->execute();
		$stmt->close();

		$_SESSION['success'][] = $language->global->success_message->basic;
	}

	display_notifications();

}


initiate_html_columns();

?>


<div class="panel panel-default">
	<div class="panel-heading">
		<?php echo $language->admin_authors_management->header; ?>
	</div>
	<div class="panel-body">

		<form action="" method="post" role="form">
			<div class="form-group">
				<label><?php echo $language->admin_authors_management->input->name; ?> *</label>
				<input type="text" name="name" class="form-control" />
			</div>

			<div class="form-group">
				<label><?php echo $language->admin_authors_management->input->url; ?> *</label>
				<p class="help-block"><?php echo $language->admin_authors_management->input->url_help; ?></p>
				<input type="text" name="url" class="form-control" />
			</div>

			<div class="form-group">
				<label><?php echo $language->admin_authors_management->input->birth_year; ?></label>
				<input type="text" name="birth_year" class="form-control" />
			</div>

			<div class="form-group">
				<label><?php echo $language->admin_authors_management->input->death_year; ?></label>
				<input type="text" name="death_year" class="form-control" />
			</div>

			<div class="form-group">
				<label><?php echo $language->admin_authors_management->input->country; ?> *</label>
				<select name="country_code" class="form-control">
					<?php country_check(1, $country_code); ?>
				</select>
			</div>

			<button type="submit" name="submit" class="btn btn-primary"><?php echo $language->global->submit_button; ?></button>
		</form>
	</div>
</div>


<div class="panel panel-default">
	<div class="panel-body">
		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th><?php echo $language->admin_authors_management->table->name; ?></th>
						<th><?php echo $language->admin_authors_management->table->url; ?></th>
						<th><?php echo $language->admin_authors_management->table->birth_year; ?></th>
						<th><?php echo $language->admin_authors_management->table->death_year; ?></th>
						<th><?php echo $language->admin_authors_management->table->country; ?></th>
						<th><?php echo $language->admin_authors_management->table->actions; ?></th>
					</tr>
				</thead>
				<tbody id="results">
					
				</tbody>
			</table>
		</div>
	</div>
</div>


<script>
$(document).ready(function() {
	/* Load first answers */
	showMore(0, 'processing/admin_authors_show_more.php', '#results', '#showMoreAuthors');
});
</script>