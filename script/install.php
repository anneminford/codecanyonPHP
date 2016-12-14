<?php
ob_start();
session_start();
error_reporting(0);
$errors = array();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Installation</title>
		    <meta charset="UTF-8">
			<link href="template/css/bootstrap.min.css" rel="stylesheet" media="screen">
			<script src="template/js/jquery.js"></script>
		    <script src="template/js/bootstrap.min.js"></script>
	</head>
	<body>
		<div class="container">
			<h2>Welcome !</h2>

			<div class="panel panel-default">
				<div class="panel-body">
					<?php
					if(!empty($_POST)) {
						/* Define some variables */
						$database_server 	= $_POST['database_server'];
						$database_username	= $_POST['database_username'];
						$database_password  = $_POST['database_password'];
						$database_name		= $_POST['database_name'];

						$database = new mysqli($database_server, $database_username, $database_password, $database_name);
						$connect_file = "core/database/connect.php";

						/* Check for any errors */
						if($database->connect_error) {
							$errors[] = 'We couldn\'t connect to the database !';
						}
						if(!is_readable($connect_file) || !is_writable($connect_file)) {
							$errors[] = '<u><strong>core/database/connect.php</strong></u> doesn\'t have CHMOD 777';
						}
						if(filter_var($_POST['settings_url'], FILTER_VALIDATE_URL) == false) {
							$errors[] = 'Your website url is not valid !';
						}

						if(empty($errors)) {
							/* add "/" if the user didnt added it */
							if(substr($_POST['settings_url'], -1) !== "/") {
								$_POST['settings_url'] .= "/";
							}

							/* Define the connect.php content */
							$connect_content = <<<PHP
<?php
/* Connection parameters */
\$database_connection = new StdClass();

\$database_connection->server = '$database_server';
\$database_connection->username = '$database_username';
\$database_connection->password = '$database_password';
\$database_connection->name = '$database_name';

/* Establishing the connection */
\$database = new mysqli(\$database_connection->server, \$database_connection->username, \$database_connection->password, \$database_connection->name);

/* Debugging */
if(\$database->connect_error) {
	die('The connection to the database failed ! Please read the documentation !');
}
?>

PHP;
							/* open, write and close */
							$command = fopen($connect_file, w);
							fwrite($command, $connect_content);
							fclose($command);

							/* Add the tables to the database */
							$database->query("
								CREATE TABLE IF NOT EXISTS `authors` (
								  `author_id` int(11) NOT NULL AUTO_INCREMENT,
								  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
								  `url` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
								  `country_code` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
								  `birth_year` int(11) NOT NULL,
								  `death_year` int(11) NOT NULL,
								  PRIMARY KEY (`author_id`)
								) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
							");

							$database->query("
								CREATE TABLE IF NOT EXISTS `categories` (
								  `category_id` int(11) NOT NULL AUTO_INCREMENT,
								  `parent_id` int(11) NOT NULL,
								  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
								  `description` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
								  `url` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
								  PRIMARY KEY (`category_id`)
								) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
							");

							$database->query("
								CREATE TABLE IF NOT EXISTS `comments` (
								  `id` int(11) NOT NULL AUTO_INCREMENT,
								  `quote_id` int(11) NOT NULL,
								  `user_id` int(11) NOT NULL,
								  `comment` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
								  `date_added` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
								  PRIMARY KEY (`id`)
								) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
							");

							$database->query("
								CREATE TABLE IF NOT EXISTS `favorites` (
								  `id` int(11) NOT NULL AUTO_INCREMENT,
								  `user_id` int(11) NOT NULL,
								  `quote_id` int(11) NOT NULL,
								  PRIMARY KEY (`id`)
								) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
							");

							$database->query("
								CREATE TABLE IF NOT EXISTS `quotes` (
								  `quote_id` int(11) NOT NULL AUTO_INCREMENT,
								  `user_id` int(11) NOT NULL,
								  `category_id` int(11) NOT NULL,
								  `author_id` int(11) NOT NULL,
								  `content` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
								  `image` varchar(38) COLLATE utf8_unicode_ci NOT NULL,
								  `active` int(11) NOT NULL,
								  `date_added` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
								  `favorites` int(11) NOT NULL,
								  PRIMARY KEY (`quote_id`)
								) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
							");
							$database->query("
								CREATE TABLE IF NOT EXISTS `reports` (
								  `id` int(11) NOT NULL AUTO_INCREMENT,
								  `user_id` int(11) NOT NULL,
								  `type` int(11) NOT NULL,
								  `reported_id` int(11) NOT NULL,
								  `message` varchar(512) CHARACTER SET latin1 NOT NULL,
								  `date` varchar(32) CHARACTER SET latin1 NOT NULL,
								  PRIMARY KEY (`id`)
								) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
							");

							$database->query("
								CREATE TABLE IF NOT EXISTS `settings` (
								  `id` int(11) NOT NULL,
								  `title` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
								  `url` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
								  `time_zone` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'America/New_York',
								  `meta_description` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
								  `banned_words` varchar(2560) COLLATE utf8_unicode_ci NOT NULL,
								  `analytics_code` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
								  `email_confirmation` int(11) NOT NULL DEFAULT '0',
								  `quotes_pagination` int(11) NOT NULL DEFAULT '20',
								  `authors_pagination` int(11) NOT NULL DEFAULT '50',
								  `sidebar_maximum_authors` int(11) NOT NULL DEFAULT '5',
								  `sidebar_maximum_countries` int(11) NOT NULL DEFAULT '5',
								  `sidebar_maximum_tags` int(11) NOT NULL DEFAULT '5',
								  `quote_maximum_categories` int(11) NOT NULL DEFAULT '5',
								  `quote_maximum_tags` int(11) NOT NULL DEFAULT '5',
								  `new_quotes_visibility` int(11) NOT NULL DEFAULT '0',
								  `guest_submit` int(11) NOT NULL DEFAULT '0',
								  `avatar_max_size` int(11) NOT NULL DEFAULT '250000',
								  `cover_max_size` int(11) NOT NULL DEFAULT '300000',
								  `contact_email` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
								  `top_ads` varchar(2560) COLLATE utf8_unicode_ci NOT NULL,
								  `bottom_ads` varchar(2560) COLLATE utf8_unicode_ci NOT NULL,
								  `side_ads` varchar(2560) COLLATE utf8_unicode_ci NOT NULL,
								  `recaptcha` int(11) NOT NULL DEFAULT '1',
								  `public_key` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
								  `private_key` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
								  `facebook` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
								  `twitter` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
								  `googleplus` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
								  PRIMARY KEY (`id`)
								) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
							");
							$database->query("
								CREATE TABLE IF NOT EXISTS `users` (
								  `user_id` int(11) NOT NULL AUTO_INCREMENT,
								  `username` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
								  `password` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
								  `email` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
								  `email_activation_code` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
								  `lost_password_code` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
								  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
								  `about` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
								  `website` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
								  `location` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
								  `avatar` varchar(38) COLLATE utf8_unicode_ci NOT NULL,
								  `cover` varchar(38) COLLATE utf8_unicode_ci NOT NULL,
								  `facebook` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
								  `twitter` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
								  `googleplus` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
								  `type` int(11) NOT NULL DEFAULT '0',
								  `active` int(11) NOT NULL DEFAULT '0',
								  `private` int(11) NOT NULL DEFAULT '0',
								  `ip` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
								  `date` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
								  `last_activity` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
								  PRIMARY KEY (`user_id`)
								) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
							");
							$database->query("
								CREATE TABLE IF NOT EXISTS `associations` (
								  `id` int(11) NOT NULL AUTO_INCREMENT,
								  `type` int(11) NOT NULL,
								  `quote_id` int(11) NOT NULL,
								  `target_id` int(11) NOT NULL,
								  PRIMARY KEY (`id`)
								) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
							");
							$database->query("
								CREATE TABLE IF NOT EXISTS `tags` (
								  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
								  `name` varchar(32) CHARACTER SET utf8_unicode_ci NOT NULL,
								  `url` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
								  PRIMARY KEY (`tag_id`)
								) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
							");

							$database->query("
								INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `email_activation_code`, `lost_password_code`, `name`, `about`, `website`, `location`, `avatar`, `cover`, `facebook`, `twitter`, `googleplus`, `type`, `active`, `private`, `ip`, `date`, `last_activity`) VALUES
								(1, 'admin', '365a4a0e748d76932d03cd46e62e4c3b4ca426c00c87bdf6ca9e692a0dc797224d151c3c9156a57c624e5bef533f0af9b8059726987c7929281a6b7acf7af8d4', 'admin@admin.com', '', '0', 'Admin', '', 'http://grohsfabian.com', '', '', '', 'test', 'test', 'test', 2, 1, 1, '-hax-', '-hax-', '')
							");
							$database->query("
								INSERT INTO `settings` (`id`, `title`, `url`, `time_zone`, `recaptcha`, `meta_description`, `banned_words`, `analytics_code`, `email_confirmation`, `quotes_pagination`, `sidebar_maximum_authors`, `sidebar_maximum_countries`, `new_quotes_visibility`, `guest_submit`, `avatar_max_size`, `cover_max_size`, `contact_email`, `top_ads`, `bottom_ads`, `side_ads`, `public_key`, `private_key`, `facebook`, `twitter`, `googleplus`) VALUES
								(1, '" . $_POST['settings_title'] . "', '" . $_POST['settings_url'] . "', 'America/New_York', '1', '', '', '', 1, 10, 10, 10, 1, 0, 1000000, 1000000, 'no-reply@domain.com', '', '', '', '6Le43tISAAAAADni-XsMzvEaStTluh6vSFmbhpfC', '6Le43tISAAAAANP9dDZb-ConEQRFxdyTpNFo09Q3', '', '', '');			
							");


							$_SESSION['success'][] = 'You can now login with the <strong>admin</strong> username and <strong>admin</strong> password.';
							$_SESSION['success'][] = 'Make sure the <strong>install.php</strong> file is deleted !';
							unlink('install.php');
							header('Location: index');
							//echo '<div class="alert alert-success"><strong>Congratulations !</strong> Now delete the install.php file and you are good to go !</div>';
						} else {

							/* Display all the errors if needed */
							foreach($errors as $nr => $error) {
								echo '<div class="alert alert-warning">' . $error . '</div>';
							}

							echo '<a href="install.php"><button class="btn btn-primary">Go back !</button></a>';
						}
					} else {
					?>
					<div class="alert alert-info">Make sure the <u><strong>core/database/connect.php</strong></u> file has CHMOD 777 before installing !</div>

					<form action="" method="post" role="form">
						<div class="form-group">
							<label>Database Server</label>
							<input type="text" class="form-control" name="database_server" value="localhost" />
						</div>
						<div class="form-group">
							<label>Database User</label>
							<input type="text" class="form-control" name="database_username" />
						</div>
						<div class="form-group">
							<label>Database Password</label>
							<input type="text" class="form-control" name="database_password" />
						</div>
						<div class="form-group">
							<label>Database Name</label>
							<input type="text" class="form-control" name="database_name" />
						</div>

						<div class="form-group">
							<label>URL</label>
							<p class="help-block">e.g: http://domain.com/directory/</p>
							<input type="text" class="form-control" name="settings_url" />
						</div>
						<div class="form-group">
							<label>Site Title</label>
							<input type="text" class="form-control" name="settings_title" />
						</div>

						<div class="form-group">
							<button type="submit" name="submit" class="btn btn-primary col-lg-4">Install</button>
						</div>
					</form>
					<?php } ?>
				</div>

				<div class="panel-footer">
					<span>Created by <a href="http://twitter.com/grohsfabian">Grohs Fabian</a></span>
				</div>

			</div>

		</div>
	</body>
</html>