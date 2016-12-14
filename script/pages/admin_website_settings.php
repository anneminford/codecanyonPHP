<?php
User::check_permission(2);

if(!empty($_POST)) {
	/* Define some variables */
	$_POST['title']				 		= filter_var($_POST['title'], FILTER_SANITIZE_STRING);
	$_POST['meta_description']	 		= filter_var($_POST['meta_description'], FILTER_SANITIZE_STRING);
	$_POST['analytics_code']	 		= filter_var($_POST['analytics_code'], FILTER_SANITIZE_STRING);
	$_POST['banned_words']		 		= filter_var($_POST['banned_words'], FILTER_SANITIZE_STRING);
	$_POST['public_key']				= filter_var($_POST['public_key'], FILTER_SANITIZE_STRING);
	$_POST['private_key']				= filter_var($_POST['private_key'], FILTER_SANITIZE_STRING);
	$_POST['contact_email']				= filter_var($_POST['contact_email'], FILTER_SANITIZE_STRING);
	$_POST['quotes_pagination']	 		= (int) $_POST['quotes_pagination'];
	$_POST['authors_pagination']	 	= (int) $_POST['authors_pagination'];
	$_POST['sidebar_maximum_authors']	= (int) $_POST['sidebar_maximum_authors'];
	$_POST['sidebar_maximum_countries']	= (int) $_POST['sidebar_maximum_countries'];
	$_POST['sidebar_maximum_tags']		= (int) $_POST['sidebar_maximum_tags'];
	$_POST['quote_maximum_categories']	= (int) $_POST['quote_maximum_categories'];
	$_POST['quote_maximum_tags']		= (int) $_POST['quote_maximum_tags'];
	$_POST['avatar_max_size']	 		= (int) $_POST['avatar_max_size'];
	$_POST['cover_max_size']	 		= (int) $_POST['cover_max_size'];
	$_POST['email_confirmation']	 	= (isset($_POST['email_confirmation'])) ? 1 : 0;
	$_POST['new_quotes_visibility'] 	= (isset($_POST['new_quotes_visibility'])) ? 1 : 0;
	$_POST['guest_submit']				= (isset($_POST['guest_submit'])) ? 1 : 0;
	$_POST['recaptcha']					= (isset($_POST['recaptcha'])) ? 1 : 0;
	$_POST['time_zone']					= filter_var($_POST['time_zone'], FILTER_SANITIZE_STRING);
	$_POST['facebook']					= filter_var($_POST['facebook'], FILTER_SANITIZE_STRING);
	$_POST['twitter']					= filter_var($_POST['twitter'], FILTER_SANITIZE_STRING);
	$_POST['googleplus']				= filter_var($_POST['googleplus'], FILTER_SANITIZE_STRING);

	/* Prepare the statement and execute query */
	$stmt = $database->prepare("UPDATE `settings` SET `time_zone` = ?, `recaptcha` = ?, `sidebar_maximum_tags` = ?, `quote_maximum_categories` = ?, `quote_maximum_tags` = ?, `guest_submit` = ?, `title` = ?, `meta_description` = ?, `analytics_code` = ?, `banned_words` = ?, `email_confirmation` = ?, `quotes_pagination` = ?, `authors_pagination` = ?, `sidebar_maximum_authors` = ?, `sidebar_maximum_countries` = ?, `avatar_max_size` = ?, `cover_max_size` = ?, `contact_email` = ?, `new_quotes_visibility` = ?, `top_ads` = ?, `bottom_ads` = ?, `side_ads` = ?, `public_key` = ?, `private_key` = ?, `facebook` = ?, `twitter` = ?, `googleplus` = ?  WHERE `id` = 1");
	$stmt->bind_param('sssssssssssssssssssssssssss', $_POST['time_zone'], $_POST['recaptcha'], $_POST['sidebar_maximum_tags'], $_POST['quote_maximum_categories'], $_POST['quote_maximum_tags'], $_POST['guest_submit'], $_POST['title'], $_POST['meta_description'], $_POST['analytics_code'], $_POST['banned_words'], $_POST['email_confirmation'], $_POST['quotes_pagination'], $_POST['authors_pagination'], $_POST['sidebar_maximum_authors'], $_POST['sidebar_maximum_countries'], $_POST['avatar_max_size'], $_POST['cover_max_size'], $_POST['contact_email'], $_POST['new_quotes_visibility'], $_POST['top_ads'], $_POST['bottom_ads'], $_POST['side_ads'], $_POST['public_key'], $_POST['private_key'], $_POST['facebook'], $_POST['twitter'], $_POST['googleplus']);
	$stmt->execute(); 
	$stmt->close();

	/* Set message & Redirect */
	$_SESSION['success'][] = $language->global->success_message->basic;
	redirect("admin/website-settings");
	
}

initiate_html_columns();

?>
<h3><?php echo $language->admin_website_settings->header; ?></h3>

<ul class="nav nav-pills">
	<li class="active"><a href="#main" data-toggle="tab"><?php echo $language->admin_website_settings->tab->main; ?></a></li>
	<li><a href="#quotes" data-toggle="tab"><?php echo $language->admin_website_settings->tab->quotes; ?></a></li>
	<li><a href="#ads" data-toggle="tab"><?php echo $language->admin_website_settings->tab->ads; ?></a></li>
	<li><a href="#captcha" data-toggle="tab"><?php echo $language->admin_website_settings->tab->captcha; ?></a></li>
	<li><a href="#social" data-toggle="tab"><?php echo $language->admin_website_settings->tab->social; ?></a></li>
</ul>


<form action="" method="post" role="form">
	<div class="tab-content">
 		<div class="tab-pane fade in active" id="main">
			<div class="form-group">
				<label><?php echo $language->admin_website_settings->input->title; ?></label>
				<input type="text" name="title" class="form-control" value="<?php echo $settings->title; ?>" />
			</div>

			<div class="form-group">
				<label><?php echo $language->admin_website_settings->input->meta_description; ?></label>
				<input type="text" name="meta_description" class="form-control" value="<?php echo $settings->meta_description; ?>" />
			</div>

			<div class="form-group">
				<label><?php echo $language->admin_website_settings->input->analytics_code; ?></label>
				<p class="help-block"><?php echo $language->admin_website_settings->input->analytics_code_help; ?></p>
				<input type="text" name="analytics_code" class="form-control" value="<?php echo $settings->analytics_code; ?>" />
			</div>

			<div class="form-group">
				<label><?php echo $language->admin_website_settings->input->banned_words; ?></label>
				<p class="help-block"><?php echo $language->admin_website_settings->input->banned_words_help; ?></p>
				<input type="text" name="banned_words" class="form-control" value="<?php echo $settings->banned_words; ?>" />
			</div>

			<div class="form-group">
				<label><?php echo $language->admin_website_settings->input->avatar_max_size; ?></label>
				<input type="text" name="avatar_max_size" class="form-control" value="<?php echo $settings->avatar_max_size; ?>" />
			</div>

			<div class="form-group">
				<label><?php echo $language->admin_website_settings->input->cover_max_size; ?></label>
				<input type="text" name="cover_max_size" class="form-control" value="<?php echo $settings->cover_max_size; ?>" />
			</div>
			
			<div class="form-group">
				<label><?php echo $language->admin_website_settings->input->contact_email; ?></label>
				<p class="help-block"><?php echo $language->admin_website_settings->input->contact_email_help; ?></p>
				<input type="text" name="contact_email" class="form-control" value="<?php echo $settings->contact_email; ?>" />
			</div>

			<div class="form-group">
				<label><?php echo $language->admin_website_settings->input->time_zone; ?></label>
				<select name="time_zone" class="form-control">
					<?php foreach(DateTimeZone::listIdentifiers() as $time_zone) echo '<option value="' . $time_zone . '" ' . (($settings->time_zone == $time_zone) ? 'selected' : null) . '>' . $time_zone . '</option>'; ?>
				</select>
			</div>

			<div class="checkbox">
				<label>
					<?php echo $language->admin_website_settings->input->email_confirmation; ?><input type="checkbox" name="email_confirmation" <?php if($settings->email_confirmation) echo 'checked'; ?>>
				</label>
			</div>

		</div>

		<div class="tab-pane fade" id="quotes">

			<div class="form-group">
				<label><?php echo $language->admin_website_settings->input->quotes_pagination; ?></label>
				<p class="help-block"><?php echo $language->admin_website_settings->input->quotes_pagination_help; ?></p>
				<input type="text" name="quotes_pagination" class="form-control" value="<?php echo $settings->quotes_pagination; ?>" />
			</div>

			<div class="form-group">
				<label><?php echo $language->admin_website_settings->input->authors_pagination; ?></label>
				<p class="help-block"><?php echo $language->admin_website_settings->input->authors_pagination_help; ?></p>
				<input type="text" name="authors_pagination" class="form-control" value="<?php echo $settings->authors_pagination; ?>" />
			</div>

			<div class="form-group">
				<label><?php echo $language->admin_website_settings->input->sidebar_maximum_authors; ?></label>
				<p class="help-block"><?php echo $language->admin_website_settings->input->sidebar_maximum_authors_help; ?></p>
				<input type="text" name="sidebar_maximum_authors" class="form-control" value="<?php echo $settings->sidebar_maximum_authors; ?>" />
			</div>

			<div class="form-group">
				<label><?php echo $language->admin_website_settings->input->sidebar_maximum_countries; ?></label>
				<p class="help-block"><?php echo $language->admin_website_settings->input->sidebar_maximum_countries_help; ?></p>
				<input type="text" name="sidebar_maximum_countries" class="form-control" value="<?php echo $settings->sidebar_maximum_countries; ?>" />
			</div>

			<div class="form-group">
				<label><?php echo $language->admin_website_settings->input->sidebar_maximum_tags; ?></label>
				<p class="help-block"><?php echo $language->admin_website_settings->input->sidebar_maximum_tags_help; ?></p>
				<input type="text" name="sidebar_maximum_tags" class="form-control" value="<?php echo $settings->sidebar_maximum_tags; ?>" />
			</div>

			<div class="form-group">
				<label><?php echo $language->admin_website_settings->input->quote_maximum_categories; ?></label>
				<input type="text" name="quote_maximum_categories" class="form-control" value="<?php echo $settings->quote_maximum_categories; ?>" />
			</div>

			<div class="form-group">
				<label><?php echo $language->admin_website_settings->input->quote_maximum_tags; ?></label>
				<input type="text" name="quote_maximum_tags" class="form-control" value="<?php echo $settings->quote_maximum_tags; ?>" />
			</div>

			<div class="checkbox">
				<label>
					<?php echo $language->admin_website_settings->input->new_quotes_visibility; ?><input type="checkbox" name="new_quotes_visibility" <?php if($settings->new_quotes_visibility) echo 'checked'; ?>>
				</label>
			</div>

			<div class="checkbox">
				<label>
					<?php echo $language->admin_website_settings->input->guest_submit; ?><input type="checkbox" name="guest_submit" <?php if($settings->guest_submit) echo 'checked'; ?>>
				</label>
			</div>

		</div>

		<div class="tab-pane fade" id="ads">
			<div class="form-group">
				<label><?php echo $language->admin_website_settings->input->top_ads; ?></label>
				<textarea class="form-control" name="top_ads"><?php echo $settings->top_ads; ?></textarea>
			</div>

			<div class="form-group">
				<label><?php echo $language->admin_website_settings->input->bottom_ads; ?></label>
				<textarea class="form-control" name="bottom_ads"><?php echo $settings->bottom_ads; ?></textarea>
			</div>

			<div class="form-group">
				<label><?php echo $language->admin_website_settings->input->side_ads; ?></label>
				<textarea class="form-control" name="side_ads"><?php echo $settings->side_ads; ?></textarea>
			</div>
		</div>

		<div class="tab-pane fade" id="captcha">
			<div class="checkbox">
				<p class="help-block"><?php echo $language->admin_website_settings->input->recaptcha_help; ?></p>
				<label>
					<?php echo $language->admin_website_settings->input->recaptcha; ?><input type="checkbox" name="recaptcha" <?php if($settings->recaptcha) echo 'checked'; ?>>
				</label>
			</div>

			<div class="form-group">
				<label><?php echo $language->admin_website_settings->input->public_key; ?></label>
				<input type="text" name="public_key" class="form-control" value="<?php echo $settings->public_key; ?>" />
			</div>

			<div class="form-group">
				<label><?php echo $language->admin_website_settings->input->private_key; ?></label>
				<input type="text" name="private_key" class="form-control" value="<?php echo $settings->private_key; ?>" />
			</div>
		</div>

		<div class="tab-pane fade" id="social">
			<p class="help-block"><?php echo $language->admin_website_settings->input->social_help; ?></p>

			<div class="form-group">
				<label><?php echo $language->admin_website_settings->input->facebook; ?></label>
				<input type="text" name="facebook" class="form-control" value="<?php echo $settings->facebook; ?>" />
			</div>

			<div class="form-group">
				<label><?php echo $language->admin_website_settings->input->twitter; ?></label>
				<input type="text" name="twitter" class="form-control" value="<?php echo $settings->twitter; ?>" />
			</div>

			<div class="form-group">
				<label><?php echo $language->admin_website_settings->input->googleplus; ?></label>
				<input type="text" name="googleplus" class="form-control" value="<?php echo $settings->googleplus; ?>" />
			</div>

		</div>

		<div class="form-group">
			<button type="submit" name="submit" class="btn btn-primary col-lg-4"><?php echo $language->global->submit_button; ?></button><br /><br />
		</div>
	</div>
</form>
