<?php

initiate_html_columns();

/* Initiate the pagination */
$pagination = new Pagination($settings->authors_pagination, null, null, 'authors');

$pagination->set_current_page_link('authors');

$result = $database->query("SELECT `name`, `url`, `birth_year`, `death_year` FROM `authors` ORDER BY `name` ASC {$pagination->limit}");

if($result->num_rows) {

	while($data = $result->fetch_object()) {

		/* Display data */
		echo '<a href="author/' . $data->url . '" class="list-group-item"><span class="badge">' . $data->birth_year  . ((!$data->death_year) ? null : ' - ' . $data->death_year) . '</span>' . $data->name . '</a>';

	}

	echo '<br />';

	/* Display the pagination */
	$pagination->display();

}


?>