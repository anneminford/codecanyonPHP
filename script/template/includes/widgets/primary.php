<?php if(!isset($_GET['page']) && User::logged_in() == false) { ?> 
<div class="jumbotron main">
	<h1><?php echo $languages['headers']['welcome']; ?></h1>
	<p class="lead shadow"><?php echo $languages['descriptions']['primary_box']; ?></p>
	<p><a class="btn btn-lg btn-success" href="register"><?php echo $languages['menu']['register']; ?></a></p>
</div>
<?php } ?>