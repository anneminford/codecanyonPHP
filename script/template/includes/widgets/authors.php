<div class="list-group">
	<?php

	$result = $database->query("SELECT `name`, `url`, `author_id` FROM `authors` ORDER BY `name` ASC LIMIT {$settings->sidebar_maximum_authors}");

	if($result->num_rows) {

		echo '<h4>' . $language->list->sidebar->authors . '</h4>';

		while($authors = $result->fetch_object()) {

			/* Determine the active author */
			$active = (isset($author) && $author->author_id == $authors->author_id);

			/* Display authors */
			echo '<a href="author/' . $authors->url . '" class="list-group-item ' . ($active ? "active" : null) . '">' . $authors->name . '</a>';


		}

		echo '<a href="authors" class="list-group-item">' . $language->list->sidebar->view_all .'</a>';

	}
	?>

</div>