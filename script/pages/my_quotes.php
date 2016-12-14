<?php
User::check_permission(0);

initiate_html_columns();

/* Initiate the quotes list class */
$quotes = new Quotes;

/* Set a custom no quotes message */
$quotes->no_quotes = $language->my_quotes->info_message->no_quotes;

/* Make it so it will display only the active */
$quotes->additional_where("AND `active` = '1' AND `user_id` = {$account_user_id}");

/* Try and display the server list */
$quotes->display();

/* Display any notification if there are any ( ex: no quotes ) */
display_notifications();

/* Display the pagination if there are quotes */
$quotes->display_pagination('my-quotes');


?>