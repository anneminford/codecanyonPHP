<?php

initiate_html_columns();

/* Initiate the quotes list class */
$quotes = new Quotes;

/* Add aditional join conditions so we show only the specific country quotes */
$quotes->additional_join("
		INNER JOIN `authors` ON `quotes` . `author_id` = `authors` . `author_id`
		AND `authors` . `country_code` = '{$_GET['country_code']}'
	");

/* Make it so it will display only the active and the quotes which are not private */
$quotes->additional_where("AND `quotes` . `active` = '1'");

/* Try and display the quotes list */
$quotes->display(true);

/* Display any notification if there are any ( no quotes ) */
display_notifications();

/* Display the pagination if there are quotes */
$quotes->display_pagination('country/' . $_GET['country_code']);
?>
