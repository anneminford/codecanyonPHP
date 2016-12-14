<?php
/* Get $category data from the database */
$stmt = $database->prepare("SELECT * FROM `quotes` WHERE `quote_id` = ?");
$stmt->bind_param('s', $_GET['quote_id']);
$stmt->execute();
bind_object($stmt, $quote);
$stmt->fetch();
$stmt->close();

$quote_exists = ($quote !== NULL);
if($quote_exists) $_SESSION['quote_id'] = $quote->quote_id;

/* Check if server exists and the GET variables are not empty */
if(empty($_GET['quote_id']) || !$quote_exists) {
	$_SESSION['error'][] = $language->quote->error_message->invalid_quote;
} else 
/* Check if quote is active */
if(!$quote->active) {
	$_SESSION['error'][] = $language->quote->error_message->not_active;
}

if(!empty($_SESSION['error'])) User::get_back();
?>