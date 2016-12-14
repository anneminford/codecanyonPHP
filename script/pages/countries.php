<?php

initiate_html_columns();

$result = $database->query("SELECT DISTINCT `country_code` FROM `authors` ORDER BY `name` ASC");
while($data = $result->fetch_object()) {

	/* Display data */
	echo '<a href="country/' . $data->country_code . '" class="list-group-item">' . country_check(2, $data->country_code) . '</a>';

}
?>