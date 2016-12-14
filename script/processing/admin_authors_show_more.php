<?php
include '../core/init.php';

$_POST['limit'] = (int) $_POST['limit'];
$results_limit = 25;

$result = $database->query("SELECT * FROM `authors` ORDER BY `author_id` DESC LIMIT {$_POST['limit']}, {$results_limit}");
while($authors_data = $result->fetch_object()) {	
?>
<tr>
	<td><?php echo $authors_data->name; ?></td>
	<td><?php echo $authors_data->url; ?></td>
	<td><?php echo $authors_data->birth_year; ?></td>
	<td><?php echo $authors_data->death_year; ?></td>
	<td><?php echo $authors_data->country_code; ?></td>
	<td>
		<a href="admin/edit-author/<?php echo $authors_data->author_id; ?>" class="no-underline"><span class="label label-info"><?php echo $language->global->edit; ?> <span class="glyphicon glyphicon-wrench white"></span></span></a>
		&nbsp;<a data-confirm="<?php echo $language->admin_authors_management->info_message->confirm_delete; ?>" href="admin/authors-management/delete/<?php echo $authors_data->author_id . '/' . $token->hash; ?>" class="no-underline"><span class="label label-danger"><?php echo $language->global->delete; ?><span class="glyphicon glyphicon-remove white"></span></span></a>&nbsp;
	</td>
</tr>
<?php } ?>

<?php if($result->num_rows == $results_limit) { ?>
<tr id="showMoreAuthors">
	<td colspan="6">
		<div class="center">
			<button id="showMore" class="btn btn-primary" onClick="showMore(<?php echo $_POST['limit'] + $results_limit; ?>, 'processing/admin_authors_show_more.php', '#results', '#showMoreAuthors');"><?php echo $language->global->show_more; ?></button>
		</div>	
	</td>
</tr>
<?php } ?>
