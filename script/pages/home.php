<?php
initiate_html_columns();

echo '<h3>' . $language->home->header . '</h3>';

/* Initiate the quotes list class */
$quotes = new Quotes;

/* Make it so it will display only the active */
$quotes->additional_where("AND `active` = '1'");

/* Display only a few random quotes */
$quotes->remove_pagination('LIMIT ' . $settings->quotes_pagination);

/* Try and display the server list */
$quotes->display();

/* Display any notification if there are any ( ex: no quotes ) */
display_notifications();
?>