<?php
User::check_permission(1);

if(isset($_GET['delete'])) {

	/* Check for errors */
	if(!$token->is_valid()) {
		$_SESSION['error'][] = $language->global->error_message->invalid_token;
	}

	if(empty($_SESSION['error'])) {

		/* Delete category and all servers from that category */
		$database->query("DELETE FROM `categories` WHERE `category_id` = {$_GET['delete']}");

		$result = $database->query("SELECT `quote_id` FROM `quotes` WHERE `category_id` = {$_GET['delete']}");
		while($quotes = $result->fetch_object()) Quotes::delete_quote($quotes->quote_id);

		/* Set the success message & redirect*/
		$_SESSION['success'][] = $language->global->success_message->basic;
		redirect('admin/categories-management');
	}
}

if(!empty($_POST)) {
	/* Define some variables */
	$_POST['name']			= filter_var($_POST['name'], FILTER_SANITIZE_STRING);
	$_POST['description']	= filter_var($_POST['description'], FILTER_SANITIZE_STRING);
	$_POST['url']			= generateSlug(filter_var($_POST['url'], FILTER_SANITIZE_STRING));
	$_POST['parent_id']		= (int)$_POST['parent_id'];
	$required_fields 		= array('name', 'url');


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
		$_SESSION['error'][] = $languages['errors']['category_doesnt_exist'];
	}

	/* If there are no errors continue the updating process */
	if(empty($_SESSION['error'])) {

		$stmt = $database->prepare("INSERT INTO `categories` (`parent_id`, `name`, `description`, `url`) VALUES (?, ?, ?, ?)");
		$stmt->bind_param('ssss', $_POST['parent_id'], $_POST['name'], $_POST['description'], $_POST['url']);
		$stmt->execute();
		$stmt->close();

		$_SESSION['success'][] = $language->global->success_message->basic;
	}

	display_notifications();

}


initiate_html_columns();

?>
<div class="panel panel-default">
	<div class="panel-body">
		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th><?php echo $language->admin_categories_management->table->name; ?></th>
						<th><?php echo $language->admin_categories_management->table->url; ?></th>
						<th><?php echo $language->admin_categories_management->table->actions; ?></th>
					</tr>
				</thead>
				<tbody>

					<?php
					$result = $database->query("SELECT `category_id`, `parent_id`, `name`, `url` FROM `categories` WHERE `parent_id` = '0' ORDER BY `category_id` ASC");
					while($category = $result->fetch_object()) {	
					?>
						<tr class="bg-primary">
							<td><?php echo $category->name; ?></td>
							<td><a href="category/<?php echo $category->url; ?>" class="white"><?php echo $category->url; ?></td>
							<td><?php category_admin_buttons($category->category_id, $token->hash); ?></td>
						</tr>
							<?php 
							$subcategories_result = $database->query("SELECT `category_id`, `parent_id`, `name`, `url` FROM `categories` WHERE `parent_id` = {$category->category_id} ORDER BY `category_id` ASC");
							while($subcategory = $subcategories_result->fetch_object()) {	
							?>
							<tr>
								<td><?php echo $subcategory->name; ?></td>
								<td><a href="category/<?php echo $subcategory->url; ?>"><?php echo $subcategory->url; ?></td>
								<td><?php category_admin_buttons($subcategory->category_id, $token->hash); ?></td>
							</tr>
							<?php } ?>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>




<div class="panel panel-default">
	<div class="panel-heading">
		<?php echo $language->admin_categories_management->header;  ?>
	</div>
	<div class="panel-body">

		<form action="" method="post" role="form">
			<div class="form-group">
				<label><?php echo $language->admin_categories_management->input->name; ?> *</label>
				<input type="text" name="name" class="form-control" />
			</div>

			<div class="form-group">
				<label><?php echo $language->admin_categories_management->input->description; ?></label>
				<p class="help-block"><?php echo $language->admin_categories_management->input->description_help; ?></p>
				<input type="text" name="description" class="form-control" />
			</div>

			<div class="form-group">
				<label><?php echo $language->admin_categories_management->input->url; ?> *</label>
				<p class="help-block"><?php echo $language->admin_categories_management->input->url_help; ?></p>
				<input type="text" name="url" class="form-control" />
			</div>


			<div class="form-group">
				<label><?php echo $language->admin_categories_management->input->parent; ?></label>
				<select name="parent_id" class="form-control">
					<option value="0">None</option>
					<?php 
					$result = $database->query("SELECT `category_id`, `name` FROM `categories` WHERE `parent_id` = '0' ORDER BY `name` ASC");
					while($category = $result->fetch_object()) echo '<option value="' . $category->category_id . '">' . $category->name . '</option>'; 
					?>
				</select>
			</div>

			<button type="submit" name="submit" class="btn btn-primary"><?php echo $language->global->submit_button; ?></button>
		</form>
	</div>
</div>