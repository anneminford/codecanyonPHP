<h4><?php echo $language->quote->sidebar->options; ?></h4>

<div class="list-group" id="quote_options">

	<?php
	if(User::logged_in()) {
		if(User::is_admin($account_user_id)) {
			echo '<a href="admin/edit-quote/' . $quote->quote_id . '" class="list-group-item "><span class="glyphicon glyphicon-pencil"></span> ' . $language->list->tooltip->edit . '</a>';
			echo '<a href="admin/edit-quote/' . $_GET['quote_id'] . '/delete/' . $token->hash . '" data-confirm="' . $language->global->info_message->confirm_delete . '" class="list-group-item"><span class="glyphicon glyphicon-pencil"></span> ' . $language->global->delete . '</a>';
		}

		$query_favorite = $database->query("SELECT `id` FROM `favorites` WHERE `user_id` = {$account_user_id} AND `quote_id` = {$quote->quote_id}");

		if($query_favorite->num_rows)
			echo '<a data-id="' . $quote->quote_id . '" class="list-group-item list-group-item-success clickable favorite"><span class="glyphicon glyphicon-heart red"></span> <span class="text">' . $language->quote->sidebar->unfavorite . '<span class="text"></a>';
		else
			echo '<a data-id="' . $quote->quote_id . '"  class="list-group-item clickable favorite"><span class="glyphicon glyphicon-heart-empty red"></span> <span class="text">' . $language->quote->sidebar->favorite . '<span class="text"></a>';
		} 
	?>

	<a class="list-group-item clickable" data-toggle="modal" data-target="#comment">
		<span class="glyphicon glyphicon-plus"></span> <?php echo $language->quote->sidebar->comment; ?>
	</a>


	<a class="list-group-item clickable" onclick="report(<?php echo $quote->quote_id; ?>, 2);">
		<span class="glyphicon glyphicon-exclamation-sign"></span> <?php echo $language->quote->sidebar->report; ?>
	</a>

</div>

<script>
$(document).ready(function() {

	/* Favorite handler */
	$('#quote_options').on('click', '.favorite', function() {
		var $div = $(this);
		var quote_id = $div.data('id');

		/* Post and get reponse */
		$.post('processing/process_favorites.php', 'quote_id='+quote_id, function(data) {
			$div.fadeOut('fast');

			setTimeout(function() {
				if(data == 'favorited') {
					$div.addClass('list-group-item-success');
					$div.children('.text').html('<?php echo $language->list->tooltip->unfavorite; ?>');
					$div.children('.glyphicon').removeClass('glyphicon-heart-empty').addClass('glyphicon-heart');
				} else 
				if(data == 'unfavorited') {
					$div.removeClass('list-group-item-success');
					$div.children('.text').html('<?php echo $language->list->tooltip->favorite; ?>');
					$div.children('.glyphicon').removeClass('glyphicon-heart').addClass('glyphicon-heart-empty');
				}
				$div.fadeIn('fast');
			}, 1500);
			
		});

	});

});
</script>


<h4><?php echo $language->quote->sidebar->details; ?></h4>
<ol class="list-unstyled">
	</li>
	<li><?php printf($language->quote->sidebar->author, '<a href="author/' . $author->url . '">' . $author->name . '</a>'); ?></li>
	<li><?php printf($language->quote->sidebar->country, '<a href="country/' . $author->country_code . '">' . country_check(2, $author->country_code) . '</a>'); ?></li>
	<li><?php if($author->birth_year) printf($language->quote->sidebar->author_birth_year, $author->birth_year); ?></li>
	<li><?php if($author->death_year) printf($language->quote->sidebar->author_death_year, $author->death_year); ?></li>
</ol>

<h4><?php echo $language->quote->sidebar->category; ?></h4>
<ol class="list-unstyled">
	<?php while($category = $category_result->fetch_object()) echo '<li><a href="category/' . $category->url . '">' . $category->name . '</a></li>'; ?>
</ol>
