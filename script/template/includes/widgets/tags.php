<div class="list-group">
	<?php

	$result = $database->query("SELECT `name`, `url`, `tag_id` FROM `tags` ORDER BY `tag_id` DESC LIMIT {$settings->sidebar_maximum_tags}");

	if($result->num_rows) {

		echo '<h4>' . $language->list->sidebar->tags . '</h4>';

		while($tags = $result->fetch_object()) {

			/* Determine the active author */
			$active = (isset($tag) && $tag->tag_id == $tags->tag_id);

			/* Display tags */
			echo '<a href="tag/' . $tags->url . '" class="list-group-item ' . ($active ? "active" : null) . '">' . $tags->name . '</a>';


		}

	}
	?>

</div>