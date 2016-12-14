<?php
if(!$settings->guest_submit && !User::logged_in()) {
	$_SESSION['info'][] = $language->global->error_message->page_access_denied;
	User::get_back();
}

/* Initiate captcha */
include_once 'core/classes/Captcha.php';
$captcha = new Captcha($settings->recaptcha, $settings->public_key, $settings->private_key);

$content = $unprocessed_tags = null;

if(!empty($_POST)) {

	/* Define some variables */
	$_POST['categories'] = (!isset($_POST['categories'])) ? null : $_POST['categories'];
	$content = filter_var($_POST['content'], FILTER_SANITIZE_STRING);
	$date = new DateTime();
	$date = $date->format('Y-m-d H:i:s');
	$active = ($settings->new_quotes_visibility) ? '1' : '0';
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
			$_SESSION['error'][] = $language->submit->error_message->small_image;
		}
	}

	/* More checks */
	if(!$captcha->is_valid()) {
		$_SESSION['error'][] = $language->global->error_message->invalid_captcha;
	}
	if(strlen($content) > 1024) {
		$_SESSION['error'][] = $language->submit->error_message->long_content;
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
		$_SESSION['error'][] = $language->submit->error_message->invalid_author;
	}
	if(count($_POST['categories']) > $settings->quote_maximum_categories) {
		$_SESSION['error'][] = $language->admin_quote_edit->error_message->many_categories;
	}
	if(count($tags_array) > $settings->quote_maximum_tags) {
		$_SESSION['error'][] = $language->admin_quote_edit->error_message->many_tags;
	}

	/* If there are no errors, add the server to the database */
	if(empty($_SESSION['error'])) {

		/* Banner process */
		if($image == true) {

			/* Generate new name for image */
			$image_new_name = md5(time().rand()) . '.' . $image_file_extension;

			/* Resize if needed and upload */
			if($image_width > 650 || $image_height > 600) {
				resize($image_file_temp, 'user_data/quotes/' . $image_new_name, '650', '500');
			} else {			
				move_uploaded_file($image_file_temp, 'user_data/quotes/' . $image_new_name);	
			}

		}

		$image_name = ($image == true) ? $image_new_name : '';
		$user_id = (User::logged_in()) ? $account_user_id : '0';

		/* Add the server to the database as private */
		$stmt = $database->prepare("INSERT INTO `quotes` (`user_id`, `author_id`, `content`, `image`, `active`, `date_added`) VALUES (?, ?, ?, ?, ?, ?)");
		$stmt->bind_param('ssssss',  $user_id, $_POST['author_id'], $content, $image_name, $active, $date);
		$stmt->execute();
		$stmt->close();

		$quote_id = $database->insert_id;

		/* Tags processing */
		foreach($tags_array as $name) {
			$slug = generateSlug($name);

			$stmt = $database->query("SELECT `tag_id` FROM `tags` WHERE `url` = '{$slug}'");
			$tag = $stmt->fetch_object();
			
			if($stmt->num_rows) {
				$database->query("INSERT INTO `associations` (`type`, `quote_id`, `target_id`) VALUES (0, {$quote_id}, {$tag->tag_id})");
			}
			else {
				$result = $database->query("INSERT INTO `tags` (`name`, `url`) VALUES ('{$name}', '{$slug}')");
				$database->query("INSERT INTO `associations` (`type`, `quote_id`, `target_id`) VALUES (0, {$quote_id}, {$database->insert_id})");
			}
		}

		/* Categories processing */
		foreach($_POST['categories'] as $category_id) $database->query("INSERT INTO `associations` (`type`, `quote_id`, `target_id`) VALUES (1, {$quote_id}, {$category_id})");

		/* Set the success message and redirect */
		$_SESSION['success'][] = $language->global->success_message->basic;
		if(User::logged_in()) redirect('my-quotes'); else redirect('index');
	}

display_notifications();

}


initiate_html_columns();

?>

<h3><?php echo $language->submit->header; ?></h3>

<form action="" method="post" role="form" enctype="multipart/form-data">

	<div class="form-group">
		<label><?php echo $language->submit->input->content; ?></label>
		<textarea name="content" class="form-control" rows="6"><?php echo $content; ?></textarea>
	</div>

	<div class="form-group">
		<label><?php echo $language->submit->input->category; ?> *</label>
		<select name="categories[]" class="form-control selectpicker" multiple  data-max-options="<?php echo $settings->quote_maximum_categories; ?>" data-live-search="true">
			<?php 
			$result = $database->query("SELECT `category_id`, `name` FROM `categories` WHERE `parent_id` = '0'  ORDER BY `name` ASC");
			while($category = $result->fetch_object()) {
				echo '<option value="' . $category->category_id . '">' . $category->name . '</option>'; 

				$subcategory_result = $database->query("SELECT `category_id`, `name` FROM `categories` WHERE `parent_id` = {$category->category_id} ORDER BY `name` ASC");
				while($subcategory = $subcategory_result->fetch_object()) {
					echo '<option value="' . $subcategory->category_id . '">&rarr;' . $subcategory->name . '</option>'; 

				}
			}
			?>	
		</select>
	</div>

	<div class="form-group">
		<label><?php echo $language->submit->input->author; ?> *</label>
		<select name="author_id" class="form-control selectpicker" data-live-search="true">
			<?php 

			$result = $database->query("SELECT `author_id`, `name` FROM `authors` ORDER BY `name` ASC");
			while($author = $result->fetch_object()) {
				echo '<option value="' . $author->author_id . '">' . $author->name . '</option>'; 
			}

			?>	
		</select>
	</div>

	<div class="form-group">
		<label><?php echo $language->submit->input->tags; ?> *</label>
		<p class="help-block"><?php echo $language->submit->input->tags_help; ?></p>
		<input type="texts" name="tags" class="form-control" value="<?php echo $unprocessed_tags; ?>" />
	</div>

	<div class="form-group">
		<label><?php echo $language->submit->input->image; ?></label><br />
		<p class="help-block"><?php echo $language->submit->input->image_help; ?></p>
		<input type="file" name="image" class="form-control" />
	</div>

	<div class="form-group">
		<?php $captcha->display(); ?>
	</div>

	<div class="form-group">
		<button type="submit" name="submit" class="btn btn-primary col-lg-4"><?php echo $language->global->submit_button; ?></button><br /><br />
	</div>

</form>