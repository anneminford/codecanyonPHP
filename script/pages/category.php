<?php

initiate_html_columns();

/* Initiate the quotes list class  */
$quotes = new Quotes();

/* Add aditional join conditions */
$quotes->additional_join("
		LEFT JOIN `associations` ON `quotes` . `quote_id` = `associations` . `quote_id`
		AND `associations` . `type` = 1
		AND `associations` . `target_id` = {$category->category_id}
	");
/* Make it so it will display only the active and the quotes which are not private */
$quotes->additional_where("AND `quotes` . `active` = '1' AND `associations` . `target_id` = {$category->category_id} ");

/* Try and display the server list */
$quotes->display(true);

/* Display any notification if there are any ( no quotes ) */
display_notifications();

/* Display the pagination if there are quotes */
$quotes->display_pagination('category/' . $category->url);
?>
