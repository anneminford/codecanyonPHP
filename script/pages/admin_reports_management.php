<?php
User::check_permission(1);

initiate_html_columns();

?>

<div class="table-responsive">
	<table class="table">
		<thead>
			<tr>
				<th><?php echo $language->admin_reports_management->table->type; ?></th>
				<th><?php echo $language->admin_reports_management->table->username; ?></th>
				<th><?php echo $language->admin_reports_management->table->reported_id; ?></th>
				<th><?php echo $language->admin_reports_management->table->date; ?></th>
				<th><?php echo $language->admin_reports_management->table->actions; ?></th>
			</tr>
		</thead>
		<tbody id="results">
			
		</tbody>
	</table>
</div>

<script>
$(document).ready(function() {
	/* Load first answers */
	showMore(0, 'processing/admin_reports_show_more.php', '#results', '#showMoreReports');
});
</script>