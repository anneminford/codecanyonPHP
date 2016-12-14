<?php
User::check_permission(1);

initiate_html_columns();

echo '<h3>' . $language->admin_quotes_management->header . '</h3>';

/* Initiate the quotes list class */
$quotes = new Quotes;

/* Set a custom no quotes message */
$quotes->no_quotes = $language->admin_quotes_management->info_message->no_quotes;

/* Make it so it will display only the active and the quotes which are not private */
$quotes->additional_where("AND `active` = '0'");

/* Try and display the author list */
$quotes->display();

/* Display any notification if there are any ( no quotes ) */
display_notifications();

/* Display the pagination if there are quotes */
$quotes->display_pagination('admin/quotes-management');
?>
