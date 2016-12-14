<?php
User::check_permission(1);

/* Check if server exists */
if(!User::x_exists('quote_id', $_GET['quote_id'], 'quotes')) {
	$_SESSION['error'][] = $language->admin_quote_edit->error_message->invalid_quote;
	User::get_back();
}

/* Get $quote data from the database */
$stmt = $database->prepare("SELECT * FROM `quotes` WHERE `quote_id` = ?");
$stmt->bind_param('s', $_GET['quote_id']);
$stmt->execute();
bind_object($stmt, $quote);
$stmt->fetch();
$stmt->close();


if(isset($_GET['type']) && empty($_POST)) {

	/* Check if the token is valid */
	if(!$token->is_valid()) {
		$_SESSION['error'][] = $language->global->error_message->invalid_token;
	}

	/* Check if there are no errors */
	if(empty($_SESSION['error'])) { 
		/* Set a success message */
		$_SESSION['success'][] = $language->global->success_message->basic;


		if($_GET['type'] == 'delete') {
			Quotes::delete_quote($quote->quote_id);
			redirect();
		}


	}

}

if(!empty($_POST)) {
	/* Define some variables */
	$_POST['categories'] = (!isset($_POST['categories'])) ? null : $_POST['categories'];
	$content = filter_var($_POST['content'], FILTER_SANITIZE_STRING);
	$active	= (isset($_POST['active'])) ? 1 : 0;
	$image = (empty($_FILES['image']['name']) == false) ? true : false;
	$_POST['author_id'] = (int) $_POST['author_id'];
 	if(!is_null($_POST['categories'])) foreach($_POST['categories'] as $category_id) $_POST[$category_id] = (int) $category_id;
	$allowed_extensions = array('jpg', 'jpeg', 'gif');
	$required_fields = array('content', 'author_id', 'categories');

 	/* Processing the tags */
	$unprocessed_tags = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);
	$tags_array = preg_replace('/[^a-zA-Z0-9 ]+/', '', array_filter(preg_split('/(\s*,+\s*)+/', $unprocessed_tags)));

	/* Check for the required fields */
	foreach($_POST as $key=>$value) {
		if((empty($value) || is_null($_POST[$key])) && in_array($key, $required_fields) == true) {
			$_SESSION['error'][] = $language->global->error_message->empty_fields;
			break 1;
		}
	}

	/* Check for banner image errors */
	if($image == true) {
		$image_file_name		= $_FILES['image']['name'];
		$image_file_extension	= explode('.', $image_file_name);
		$image_file_extension	= strtolower(end($image_file_extension));
		$image_file_temp		= $_FILES['image']['tmp_name'];
		$image_file_size		= $_FILES['image']['size'];
		list($image_width, $image_height)	= getimagesize($image_file_temp);

		if(in_array($image_file_extension, $allowed_extensions) !== true) {
			$_SESSION['error'][] = $language->global->error_message->invalid_file_type;
		}
		if($image_file_size > $settings->cover_max_size) {
			$_SESSION['error'][] = sprintf($language->global->error_message->invalid_image_size, formatBytes($settings->cover_max_size));
		}
		if($image_width < 650) {
			$_SESSION['error'][] = $language->admin_quote_edit->error_message->small_image;
		}
	}

	/* More checks */
	if(strlen($content) > 1024) {
		$_SESSION['error'][] = $language->admin_quote_edit->error_message->long_content;
	}
	if(!is_null($_POST['categories'])) {
		foreach($_POST['categories'] as $category_id) {
			if(!User::x_exists('category_id', $category_id, 'categories')) {
				$_SESSION['error'][] = $language->admin_quote_edit->error_message->invalid_category;
				break;
			}
		}
	}
	if(!User::x_exists('author_id', $_POST['author_id'], 'authors')) {
		$_SESSION['error'][] = $language->admin_quote_edit->error_message->invalid_author;
	}
	if(count($_POST['categories']) > $settings->quote_maximum_categories) {
		$_SESSION['error'][] = $language->admin_quote_edit->error_message->many_categories;
	}
	if(count($tags_array) > $settings->quote_maximum_tags) {
		$_SESSION['error'][] = $language->admin_quote_edit->error_message->many_tags;
	}

	/* If there are no errors, proceed with the updating */
	if(empty($_SESSION['error'])) {

		/* Banner update process */
		if($image == true) {

			/* Delete current image & thumbnail */
			@unlink('user_data/quotes/' . $quote->image);

			/* Generate new name for image */
			$image_new_name = md5(time().rand()) . '.' . $image_file_extension;

			/* Resize if needed and upload */
			if($image_width > 650 || $image_height > 600) {
				resize($image_file_temp, 'user_data/quotes/' . $image_new_name, '650', '500');
			} else {			
				move_uploaded_file($image_file_temp, 'user_data/quotes/' . $image_new_name);	
			}

			/* Execute query */
			$database->query("UPDATE `quotes` SET `image` = '{$image_new_name}' WHERE `quote_id` = {$quote->quote_id}");

		} 

		/* Start updating the quote */
		$stmt = $database->prepare("UPDATE `quotes` SET `author_id` = ?, `content` = ?, `active` = ? WHERE `quote_id` = {$quote->quote_id}");
		$stmt->bind_param('sss', $_POST['author_id'], $content, $active);
		$stmt->execute();

		/* Update categories */
		$database->query("DELETE FROM `associations` WHERE `type` = 1 AND `quote_id` = {$quote->quote_id}");
		foreach($_POST['categories'] as $category_id) $database->query("INSERT INTO `associations` (`type`, `quote_id`, `target_id`) VALUES (1, {$quote->quote_id}, {$category_id})");
		
		/* Update tags */
		$database->query("DELETE FROM `associations` WHERE `type` = 0 AND `quote_id` = {$quote->quote_id}");
		foreach($tags_array as $name) {
			$slug = generateSlug($name);

			$stmt = $database->query("SELECT `tag_id` FROM `tags` WHERE `url` = '{$slug}'");
			$tag = $stmt->fetch_object();
			
			if($stmt->num_rows) {
				$database->query("INSERT INTO `associations` (`type`, `quote_id`, `target_id`) VALUES (0, {$quote->quote_id}, {$tag->tag_id})");
			}
			else {
				$result = $database->query("INSERT INTO `tags` (`name`, `url`) VALUES ('{$name}', '{$slug}')");
				$database->query("INSERT INTO `associations` (`type`, `quote_id`, `target_id`) VALUES (0, {$quote->quote_id}, {$database->insert_id})");
			}
		}
		$database->query("DELETE FROM `tags` WHERE `tag_id` NOT IN (SELECT `target_id` FROM `associations` WHERE `type` = 0)");

		/* Set a success message */
		$_SESSION['success'][] = $language->global->success_message->basic;

	}
}


/* Refresh the quote data */
$stmt = $database->prepare("SELECT * FROM `quotes` WHERE `quote_id` = ?");
$stmt->bind_param('s', $_GET['quote_id']);
$stmt->execute();
bind_object($stmt, $quote);
$stmt->fetch();
$stmt->close();

/* Get category information for the quote */
$category_result = $database->query("SELECT `category_id` FROM `categories` LEFT JOIN `associations` ON `categories` . `category_id` = `associations` . `target_id` WHERE `associations` . `type` = 1 AND `associations` . `quote_id` = {$quote->quote_id}");
$current_categories = array();
while($category = $category_result->fetch_object()) $current_categories[] = $category->category_id;

/* Get tags information for the quote */
$tag_result = $database->query("SELECT `name` FROM `tags` LEFT JOIN `associations` ON `tags` . `tag_id` = `associations` . `target_id` WHERE `associations` . `type` = 0 AND `associations` . `quote_id` = {$quote->quote_id}");
$current_tags = array();
while($tag = $tag_result->fetch_object()) $current_tags[] = $tag->name;

/* Get author information for the quote */
$author_result = $database->query("SELECT `author_id`, `name`, `url` FROM `authors` WHERE `author_id` = {$quote->author_id}");
$current_author = $author_result->fetch_object();


display_notifications();

initiate_html_columns();

?>

<h3><?php echo $language->admin_quote_edit->header; ?></h3>

<form action="" method="post" role="form" enctype="multipart/form-data">
	<div class="form-group">
		<label><?php echo $language->admin_quote_edit->input->content; ?> *</label>
		<textarea name="content" class="form-control" rows="6"><?php echo $quote->content; ?></textarea>
	</div>

	<div class="form-group">
		<label><?php echo $language->admin_quote_edit->input->category; ?> *</label>
		<select name="categories[]" class="form-control selectpicker" multiple  data-max-options="<?php echo $settings->quote_maximum_categories; ?>" data-live-search="true">
			<?php 
			$result = $database->query("SELECT `category_id`, `name` FROM `categories` WHERE `parent_id` = '0' ORDER BY `name` ASC");
			while($category = $result->fetch_object()) {
				echo '<option value="' . $category->category_id . '" ' . ((in_array($category->category_id, $current_categories)) ? 'selected' : null) . '>' . $category->name . '</option>'; 

				$subcategory_result = $database->query("SELECT `category_id`, `name` FROM `categories` WHERE `parent_id` = {$category->category_id}  ORDER BY `name` ASC");
				while($subcategory = $subcategory_result->fetch_object()) {
					echo '<option value="' . $subcategory->category_id . '" ' . ((in_array($subcategory->category_id, $current_categories)) ? 'selected' : null) . '>&rarr;' . $subcategory->name . '</option>'; 

				}
			}
			?>	
		</select>
	</div>

	<div class="form-group">
		<label><?php echo $language->admin_quote_edit->input->author; ?> *</label>
		<select name="author_id" class="form-control selectpicker" data-live-search="true">
			<?php 
			echo '<option value="' . $current_author->author_id . '">Current:' . $current_author->name . '</option>';

			$result = $database->query("SELECT `author_id`, `name` FROM `authors` WHERE `author_id` != {$current_author->author_id} ORDER BY `name` ASC");
			while($author = $result->fetch_object()) {
				echo '<option value="' . $author->author_id . '">' . $author->name . '</option>'; 
			}

			?>	
		</select>
	</div>

	<div class="form-group">
		<label><?php echo $language->admin_quote_edit->input->tags; ?> *</label>
		<p class="help-block"><?php echo $language->admin_quote_edit->input->tags_help; ?></p>
		<input type="texts" name="tags" class="form-control" value="<?php echo implode(',', $current_tags); ?>" />
	</div>

	<div class="form-group">
		<label><?php echo $language->admin_quote_edit->input->image; ?></label><br />
		<img src="user_data/quotes/<?php echo $quote->image; ?>" style="max-width: 800px;" class="img-rounded" alt="Avatar" />
		<p class="help-block"><?php echo $language->admin_quote_edit->input->image_help; ?></p>
		<input type="file" name="image" class="form-control" />
	</div>

	<div class="checkbox">
		<label>
			<?php echo $language->admin_quote_edit->input->active; ?><input type="checkbox" name="active" <?php if($quote->active) echo 'checked'; ?>>
		</label>
	</div>

	<div class="form-group">
		<button type="submit" name="submit" class="btn btn-primary"><?php echo $language->global->submit_button; ?></button>

		<a href="admin/edit-quote/<?php echo $_GET['quote_id']; ?>/delete/<?php echo $token->hash; ?>" data-confirm="<?php echo $language->global->info_message->confirm_delete; ?>">
			<button type="button" class="btn btn-danger"><?php echo $language->global->delete; ?></button>
		</a>
	</div>
</form>