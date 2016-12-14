<?php 

if($_GET['page'] == 'quotes' || ($_GET['page'] == 'category' && $category_exists) || ($_GET['page'] == 'author' && $author_exists) || ($_GET['page'] == 'country' && $country_exists)) {
	include 'widgets/quotes_filter.php';
}

if($_GET['page'] !== 'quote') {
	include 'widgets/authors.php';
	include 'widgets/countries.php';
	include 'widgets/categories.php';
	include 'widgets/tags.php';
} else {
	include 'widgets/quote.php';
}


if(!empty($settings->side_ads)) echo $settings->side_ads;


?>