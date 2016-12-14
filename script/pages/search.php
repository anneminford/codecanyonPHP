<?php

initiate_html_columns();

/* Process the search terms */
$raw_search = $database->escape_string(filter_var($_POST['search'], FILTER_SANITIZE_STRING));
$search_terms = preg_split('#\s+#', $raw_search);

/* Check if the terms are too small */
if(strlen($raw_search) < 3) {
	$_SESSION['error'] = $language->search->error_message->small_query;
	User::get_back();
}

/* Displaying the current searched query */
echo '<ol class="breadcrumb"><li class="active" style="word-break: break-all;">' . sprintf($language->search->searching_for, string_resize($raw_search, 64)) . '</li></ol>';

/* Build the additional query */
$where = '`authors` . `name` LIKE \'%' . implode('%\' OR `authors` . `name` LIKE \'%', $search_terms) . '%\'' . ' OR `quotes` . `content` LIKE \'%' . preg_replace('#\s+#', ' ', $raw_search) . '%\'';

/* Initiate the quotes list class */
$quotes = new Quotes;

/* Set a custom no quotes message */
$quotes->no_quotes = $language->search->info_message->no_quotes;

/* Make it so it will display only the active and the quotes which are not private */
$quotes->additional_where("AND `quotes` . `active` = '1'");

/* Add aditional join conditions so we show only the specific country quotes */
$quotes->additional_join("
		INNER JOIN `authors` ON `quotes` . `author_id` = `authors` . `author_id`
		AND ({$where})
	");

/* Try and display the quotes list */
$quotes->display();

/* Display any notification if there are any ( no quotes ) */
display_notifications();

/* Display the pagination if there are quotes */
$quotes->display_pagination('search/' . $raw_search);
?>
