<?php

$random_quote = $database->query("SELECT `quotes` . `content`, `authors` . `name`, `authors` . `url` FROM `quotes` INNER JOIN `authors` ON `quotes` . `author_id` = `authors` . `author_id` ORDER BY RAND() LIMIT 1");
$random_quote = $random_quote->fetch_object();
?>

<div class="index-container">
	<div class="center">

		<div class="index-title"><?php echo $settings->title; ?></div>

		<div class="index-text"><?php if($random_quote) echo '"' . $random_quote->content . '" - <a href="author/' . $random_quote->url . '" class="no-underline black">' . $random_quote->name . '</a>'; ?></div>

	</div>
</div>

