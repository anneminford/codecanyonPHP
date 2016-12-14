
<div class="col-md-10">

	<?php
	echo $language->global->language;

	foreach($languages as $language_name) {
		echo ' <a href="index.php?language=' . $language_name . '">' . $language_name . '</a> &nbsp;&nbsp; ';
	}

	?>

	<br />

	<a href="terms-of-service">Terms of Service</a> - <a href="privacy-policy">Privacy Policy</a> - <a href="disclaimer">Disclaimer</a> - <a href="contact">Contact</a>

	<br />

	<?php echo 'Copyright &copy; ' . date("Y") . ' ' . $settings->title . '. All rights reserved. Powered by <a href="http://phpserverslist.com">phpServersList</a>'; ?>

</div>

<div class="col-md-2">
	<p class="navbar-social pull-right hidden-sm">
		<?php 
		if(!empty($settings->facebook))
			echo '<a href="http://facebook.com/' . $settings->facebook . '"><span class="fa-stack"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-facebook fa-stack-1x fa-inverse"></i></span></a>';

		if(!empty($settings->twitter))
			echo '<a href="http://twitter.com/' . $settings->twitter . '"><span class="fa-stack"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-twitter fa-stack-1x fa-inverse"></i></span></a>';
		
		if(!empty($settings->googleplus))
			echo '<a href="http://plus.google.com/' . $settings->googleplus . '"><span class="fa-stack"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-google-plus fa-stack-1x fa-inverse"></i></span></a>';
		
		?>
	</p>
			
</div>