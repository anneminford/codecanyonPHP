<?php
User::check_permission(1);

/* Check if category exists */
if(!User::x_exists('category_id', $_GET['category_id'], 'categories')) {
	$_SESSION['error'][] = $language->admin_category_edit->error_message->invalid_category;
	User::get_back('admin/categories-management');
}


if(!empty($_POST)) {
	/* Define some variables */
	$_POST['name']				 		= filter_var($_POST['name'], FILTER_SANITIZE_STRING);
	$_POST['url']						= generateSlug(filter_var($_POST['url'], FILTER_SANITIZE_STRING));
	$_POST['parent_id']					= (int)$_POST['parent_id'];
	$_POST['description']		 		= filter_var($_POST['description'], FILTER_SANITIZE_STRING);
	$_GET['category_id']				= (int)$_GET['category_id'];
	$required_fields = array('name', 'url');


	/* Check for the required fields */
	foreach($_POST as $key=>$value) {
		if(empty($value) && in_array($key, $required_fields) == true) {
			$_SESSION['error'][] = $language->global->error_message->empty_fields;
			break 1;
		}
	}

	/* Check if the select value was changed for categories */
	$categories = array();
	$result = $database->query("SELECT `category_id` FROM `categories` WHERE `parent_id` = '0' ORDER BY `category_id` ASC");
	while($category = $result->fetch_object()) $categories[] = $category->category_id; $categories[] = "0";
	if(!in_array($_POST['parent_id'], $categories)) {
		$_SESSION['error'][] = $language->admin_category_edit->error_message->invalid_category;
	}

	/* If there are no errors continue the updating process */
	if(empty($_SESSION['error'])) {

		$stmt = $database->prepare("UPDATE `categories` SET `parent_id` = ?, `name` = ?, `description` = ?, `url` = ? WHERE `category_id` = ?");
		$stmt->bind_param('sssss', $_POST['parent_id'], $_POST['name'], $_POST['description'], $_POST['url'], $_GET['category_id']);
		$stmt->execute();
		$stmt->close();

		/* Set a success message */
		$_SESSION['success'][] = $language->global->success_message->basic;
		redirect('admin/categories-management');
	}

	display_notifications();

}

/* Get $category data from the database */
$stmt = $database->prepare("SELECT * FROM `categories` WHERE `category_id` = ?");
$stmt->bind_param('s', $_GET['category_id']);
$stmt->execute();
bind_object($stmt, $category);
$stmt->fetch();
$stmt->close();

initiate_html_columns();

?>


<h3><?php echo $language->admin_category_edit->header; ?></h3>

<form action="" method="post" role="form" enctype="multipart/form-data">
	<div class="form-group">
		<label><?php echo $language->admin_category_edit->input->name; ?></label>
		<input type="text" name="name" class="form-control" value="<?php echo $category->name; ?>" />
	</div>

	<div class="form-group">
		<label><?php echo $language->admin_category_edit->input->description; ?></label>
		<p class="help-block"><?php echo $language->admin_category_edit->input->description_help; ?></p>
		<input type="text" name="description" class="form-control" value="<?php echo $category->description; ?>"/>
	</div>

	<div class="form-group">
		<label><?php echo $language->admin_category_edit->input->url; ?></label>
		<p class="help-block"><?php echo $language->admin_category_edit->input->url_help; ?></p>
		<input type="text" name="url" class="form-control" value="<?php echo $category->url; ?>" />
	</div>

	<div class="form-group">
		<label><?php echo $language->admin_category_edit->input->parent; ?></label>
		<select name="parent_id" class="form-control">
			<?php echo '<option value="' . $category->parent_id . '">Current parent: ' . User::x_to_y('category_id', 'name', $category->parent_id, 'categories') . '</option>'; ?>
			<option value="0">None</option>
			<?php 
			$result = $database->query("SELECT `category_id`, `name` FROM `categories` WHERE `parent_id` = '0' AND `category_id` != {$category->parent_id} AND `category_id` != {$category->category_id} ORDER BY `name` ASC");
			while($category_parent = $result->fetch_object()) echo '<option value="' . $category_parent->category_id . '">' . $category_parent->name . '</option>'; 
			?>
		</select>
	</div>

	<button type="submit" name="submit" class="btn btn-primary"><?php echo $language->global->submit_button; ?></button>
</form>