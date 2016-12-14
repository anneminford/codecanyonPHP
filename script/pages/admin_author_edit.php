<?php
User::check_permission(1);

/* Check if category exists */
if(!User::x_exists('author_id', $_GET['author_id'], 'authors')) {
	$_SESSION['error'][] = $language->admin_author_edit->error_message->invalid_author;
	User::get_back('admin/authors-management');
}


if(!empty($_POST)) {
	/* Define some variables */
	$_POST['name']				 		= filter_var($_POST['name'], FILTER_SANITIZE_STRING);
	$_POST['url']						= generateSlug(filter_var($_POST['url'], FILTER_SANITIZE_STRING));
	$_POST['birth_year']				= (int) $_POST['birth_year'];
	$_POST['death_year']				= (int) $_POST['death_year'];
	$_POST['country_code']				= (country_check(0, $_POST['country_code'])) ? $_POST['country_code'] : 'US';
	$_GET['author_id']					= (int) $_GET['author_id'];
	$required_fields = array('name', 'url');


	/* Check for the required fields */
	foreach($_POST as $key=>$value) {
		if(empty($value) && in_array($key, $required_fields) == true) {
			$_SESSION['error'][] = $language->global->error_message->empty_fields;
			break 1;
		}
	}

	/* If there are no errors continue the updating process */
	if(empty($_SESSION['error'])) {

		$stmt = $database->prepare("UPDATE `authors` SET `name` = ?, `url` = ?, `country_code` = ?, `birth_year` = ?, `death_year` = ? WHERE `author_id` = ?");
		$stmt->bind_param('ssssss',  $_POST['name'], $_POST['url'], $_POST['country_code'], $_POST['birth_year'], $_POST['death_year'], $_GET['author_id']);
		$stmt->execute();
		$stmt->close();

		/* Set a success message */
		$_SESSION['success'][] = $language->global->success_message->basic;
		redirect('admin/authors-management');
	}

	display_notifications();

}

/* Get $author data from the database */
$stmt = $database->prepare("SELECT * FROM `authors` WHERE `author_id` = ?");
$stmt->bind_param('s', $_GET['author_id']);
$stmt->execute();
bind_object($stmt, $author);
$stmt->fetch();
$stmt->close();

initiate_html_columns();

?>


<h3><?php echo $language->admin_author_edit->header; ?></h3>


<form action="" method="post" role="form">

	<div class="form-group">
		<label><?php echo $language->admin_author_edit->input->name; ?> *</label>
		<input type="text" name="name" class="form-control" value="<?php echo $author->name; ?>" />
	</div>

	<div class="form-group">
		<label><?php echo $language->admin_author_edit->input->url; ?> *</label>
		<p class="help-block"><?php echo $language->admin_author_edit->input->url_help; ?></p>
		<input type="text" name="url" class="form-control" value="<?php echo $author->url; ?>" />
	</div>

	<div class="form-group">
		<label><?php echo $language->admin_author_edit->input->birth_year; ?></label>
		<input type="text" name="birth_year" class="form-control" value="<?php echo $author->birth_year; ?>" />
	</div>

	<div class="form-group">
		<label><?php echo $language->admin_author_edit->input->death_year; ?></label>
		<input type="text" name="death_year" class="form-control" value="<?php echo $author->death_year; ?>" />
	</div>

	<div class="form-group">
		<label><?php echo $language->admin_author_edit->input->country; ?> *</label>
		<select name="country_code" class="form-control">
			<?php country_check(1, $author->country_code); ?>
		</select>
	</div>

	<button type="submit" name="submit" class="btn btn-primary"><?php echo $language->global->submit_button; ?></button>
</form>
