<?php

/* Initiate captcha */
include_once 'core/classes/Captcha.php';
$captcha = new Captcha($settings->recaptcha, $settings->public_key, $settings->private_key);

include 'template/includes/modals/comment.php';
include 'template/includes/modals/report.php';

/* Get category information for the quote */
$category_result = $database->query("SELECT `name`, `url` FROM `categories` LEFT JOIN `associations` ON `categories` . `category_id` = `associations` . `target_id` WHERE `associations` . `type` = 1 AND `associations` . `quote_id` = {$quote->quote_id}");

/* Get author information for the quote */
$author_result = $database->query("SELECT * FROM `authors` WHERE `author_id` = {$quote->author_id}");
$author = $author_result->fetch_object();

/* Get the tags information for the quote */
$tags_result = $database->query("SELECT DISTINCT `name`, `url`  FROM `tags` LEFT JOIN `associations` ON `tags`.`tag_id` = `associations`.`target_id` WHERE `associations`.`type` = 0 AND `associations`.`quote_id` = {$quote->quote_id}");

/* Generate the current url for the share buttons */
$share_url = urlencode($settings->url . 'quote/' . $quote->quote_id);

initiate_html_columns();

?>

<div id="response" style="display:none;"><?php output_success($language->global->success_message->basic); ?></div>

<div class="row">
	<div class="col-md-8">
		<div class="panel panel-default">

			<?php if(!empty($quote->image)) { ?>
			<div class="panel-heading quote-heading center">
				<img class="img-responsive" src="user_data/quotes/<?php echo $quote->image; ?>" style="display:inline;" />
			</div>
			<?php } ?>

			<div class="panel-body quote-body">
				<?php echo $quote->content; ?>
			</div>

			<?php if($tags_result->num_rows) { ?>
			<div class="panel-footer">
				<?php while($tag = $tags_result->fetch_object()) echo '<a href="tag/' . $tag->url . '" class="label label-default" >' . $tag->name . '</a>&nbsp;'; ?>
			</div>
			<?php } ?>

		</div>
	</div>

	<div class="col-md-4">
		<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" target="_blank" class="no-underline"><button type="button" class="share-button" style="background: #3B579D;">Facebook</button></a>
		<a href="https://twitter.com/intent/tweet?url=<?php echo $share_url; ?>" target="_blank" class="no-underline"><button type="button" class="share-button" style="background: #1AB2E8;">Twitter</button></a>
		<a href="https://plus.google.com/share?url=<?php echo $share_url; ?>" target="_blank" class="no-underline"><button type="button" class="share-button" style="background: #DD4B39;">Google+</button></a>
	</div>

</div>

<!-- Comments -->
<div class="panel panel-default">
	<div class="panel-body">
		<h3>
			<?php echo $language->quote->header; ?>
		</h3>

		<div id="comments"></div>

	</div>
</div>

<!-- Recaptcha base -->
<div id="recaptcha_base">
	<div id="recaptcha" style="display:none;"><?php echo $captcha->display(); ?></div>
</div>

<script>
$(document).ready(function() {

	/* Initialize the success message variable */
	var SuccessMessage = $('#response').html();

	/* Load the first comments results */
	showMore(0, 'processing/comments_show_more.php', '#comments', '#showMoreComments');

	/* Delete system */
	$('#comments, #blog_posts').on('click', '.delete', function() {
		/* selector = div to be removed */
		var answer = confirm("<?php echo $language->global->info_message->confirm_delete; ?>");
		
		if(answer) {
			$('html, body').animate({scrollTop:0},'slow');

			var $div = $(this).closest('.media');
			var reported_id = $(this).attr('data-id');
			var type = $(this).attr('data-type');

			/* Post and get response */
			$.post("processing/process_comments.php", "delete=true&reported_id="+reported_id+"&type="+type, function(data) {

				if(data == "success") {
					$("#response").html(SuccessMessage).fadeIn('slow');
					$div.fadeOut('slow');
				} else {
					$("#response").html(data).fadeIn('slow');
				}
				setTimeout(function() {
					$("#response").fadeOut('slow');
				}, 5000);
			});
		}
	});


});
</script>