<?php

initiate_html_columns();

/* Initiate the quotes list class */
$quotes = new Quotes;

/* Make it so it will display only the active and the quotes which are not private */
$quotes->additional_where("AND `active` = '1' AND `author_id` = {$author->author_id}");

/* Try and display the author list */
$quotes->display(true);

/* Display any notification if there are any ( no quotes ) */
display_notifications();

/* Display the pagination if there are quotes */
$quotes->display_pagination('author/' . $author->url);
?>
