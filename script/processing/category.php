<?php
/* Get $category data from the database */
$stmt = $database->prepare("SELECT * FROM `categories` WHERE BINARY `url` = ?");
$stmt->bind_param('s', $_GET['url']);
$stmt->execute();
bind_object($stmt, $category);
$stmt->fetch();
$stmt->close();

$category_exists = ($category !== NULL);

/* Check if category exists and the GET variable is not empty*/
if(empty($_GET['url']) || !$category_exists) User::get_back();
?>