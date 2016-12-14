<?php
/* Get $author data from the database */
$stmt = $database->prepare("SELECT * FROM `authors` WHERE BINARY `url` = ?");
$stmt->bind_param('s', $_GET['url']);
$stmt->execute();
bind_object($stmt, $author);
$stmt->fetch();
$stmt->close();

$author_exists = ($author !== NULL);

/* Check if category exists and the GET variable is not empty*/
if(empty($_GET['url']) || !$author_exists) User::get_back();
?>