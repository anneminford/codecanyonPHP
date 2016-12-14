<?php
/* Get $category data from the database */
$stmt = $database->prepare("SELECT * FROM `tags` WHERE BINARY `url` = ?");
$stmt->bind_param('s', $_GET['url']);
$stmt->execute();
bind_object($stmt, $tag);
$stmt->fetch();
$stmt->close();

$tag_exists = ($tag !== NULL);

/* Check if tag exists and the GET variable is not empty*/
if(empty($_GET['url']) || !$tag_exists) User::get_back();

?>