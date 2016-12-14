<div class="row">
	<div class="col-md-<?php echo (isset($_GET['page']) && (in_array($_GET['page'], $full_width_pages))) ? '12' : '10'; ?>">

		<?php if(isset($_GET['page']) && (!in_array($_GET['page'], $no_panel_pages))) { ?>
		<div class="panel panel-default">
				<div class="panel-body">
		<?php } ?>