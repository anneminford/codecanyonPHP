<?php
initiate_html_columns();

/* Initiate the quotes list class */
$quotes = new Quotes;

/* Make it so it will display only the active */
$quotes->additional_where("AND `active` = '1'");

/* Try and display the quotes list */
$quotes->display(true);

/* Display any notification if there are any ( ex: no quotes ) */
display_notifications();

/* Display the pagination if there are quotes */
$quotes->display_pagination('quotes');
?>