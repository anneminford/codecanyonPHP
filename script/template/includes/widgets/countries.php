<div class="list-group">
	<?php

	$result = $database->query("SELECT DISTINCT `country_code` FROM `authors` ORDER BY `name` ASC LIMIT {$settings->sidebar_maximum_countries}");

	if($result->num_rows) {

		echo '<h4>' . $language->list->sidebar->countries . '</h4>';

		while($data = $result->fetch_object()) {

			/* Determine the active author */
			$active = (isset($_GET['country_code']) && $_GET['country_code'] == $data->country_code);

			/* Display data */
			echo '<a href="country/' . $data->country_code . '" class="list-group-item ' . ($active ? "active" : null) . '">' . country_check(2, $data->country_code) . '</a>';


		}

		echo '<a href="countries" class="list-group-item">' . $language->list->sidebar->view_all . '</a>';

	}
	?>

</div>