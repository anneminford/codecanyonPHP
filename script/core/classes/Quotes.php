<?php

class Quotes {
	private $order_by;
	private $additional_join = null;
	private $where;

	public $pagination;
	public $quote_results;
	public $affix;
	public $country_options;
	public $no_quotes;


	public function __construct() {
		global $database;
		global $settings;
		global $language;

		/* Initiate the affix and start generating it */
		$this->affix = '';

		/* Order by system */
		$order_by_options = array('random', 'quote_id');
		$order_by_column = (isset($_GET['order_by']) && in_array(strtolower($_GET['order_by']), $order_by_options)) ? strtolower($_GET['order_by']) : false;
	
		if($order_by_column !== false) {
			if($order_by_column == 'random') {
				$this->order_by = 'ORDER BY RAND()';
			}
			else {
				$this->order_by = 'ORDER BY `quotes` . `' . $order_by_column . '` DESC';
			}
		} else {
			if($_GET['page'] == 'index') {
				$this->order_by = 'ORDER BY `quotes` . `quote_id` DESC';
			} else {
				$this->order_by = 'ORDER BY `quotes` . `favorites` DESC';
			}
		}

		$this->affix .= ($order_by_column !== false) ? '&order_by=' . $order_by_column : '';

		/* If affix isn't empty prepend the ? sign so it can be processed */
		$this->affix = (!empty($this->affix)) ? '?' . $this->affix : null;

		/* Create the maine $where variable */
		$this->where = "WHERE 1=1 ";

		/* Generate pagination */
		$this->pagination = new Pagination($settings->quotes_pagination, $this->where);

		/* Set the default no quotes message */
		$this->no_quotes = $language->list->info_message->no_quotes;
	}	


	public function additional_join($join) {
		global $settings; 
		
		/* This is mainly so we can gather the data based on the favorite quotes */
		$this->additional_join = $join;

		/* Remake the pagination with the true condition so it counts the quotes correctly */
		$this->pagination = new Pagination($settings->quotes_pagination, $this->where, $this->additional_join);

	}

	public function additional_where($where) {
		global $settings;

		/* Remake the where with the additional condition */
		$this->where = $this->where . ' ' . $where;

		/* Remake the pagination */
		$this->pagination = new Pagination($settings->quotes_pagination, $this->where, $this->additional_join);

	}


	public function remove_pagination($limit = null) {

		/* Make the pagination null */
		$this->pagination->limit = $limit;

	}

	public function display($columns = false) {
		global $database;
		global $language;
		global $account_user_id;
		global $settings;

		/* Retrieve quotes information  */
		$result = $database->query("SELECT * FROM `quotes` {$this->additional_join} {$this->where} {$this->order_by} {$this->pagination->limit}");

		/* Check if there is any result */
		$this->quote_results = $result->num_rows;
		if($this->quote_results < 1) $_SESSION['info'][] = $this->no_quotes;

		/* Define a counter and the half of the results needed to fix the columns */
		$i = 1;
		$half = ceil($result->num_rows / 2);

		/* Display the servers */
		while($quote = $result->fetch_object()) {

		/* Get category information for the quote */
		$category_result = $database->query("SELECT `name`, `url` FROM `categories` LEFT JOIN `associations` ON `categories`.`category_id` = `associations`.`target_id` WHERE `associations`.`type` = 1 AND `associations`.`quote_id` = {$quote->quote_id}");
		$categories = array();
		while($category = $category_result->fetch_object()) $categories[] = '<a href="category/' . $category->url . '">' . $category->name . '</a>';

		/* Get author information for the quote */
		$author_result = $database->query("SELECT `name`, `url` FROM `authors` WHERE `author_id` = {$quote->author_id}");
		$author = $author_result->fetch_object();

		/* Check if there is any image uploaded, if not, display default */
		$quote->image = (empty($quote->image)) ? '' : $quote->image;
		
		/* Generate the current url for the share buttons */
		$share_url = urlencode($settings->url . 'quote/' . $quote->quote_id);

		echo ($columns && ($i == 1 || $i == $half+1)) ? '<div class="col-md-6">' : '';

		?>
		<div class="panel panel-default">

			<?php if($columns && !empty($quote->image)) { ?>
			<div class="panel-heading quote-heading">
				<img class="img-responsive" src="user_data/quotes/<?php echo $quote->image; ?>" />
			</div>
			<?php } ?>

			<div class="panel-body quote-body">

				<a href="quote/<?php echo $quote->quote_id; ?>">
					<?php echo $quote->content; ?>

					<a href="https://twitter.com/intent/tweet?url=<?php echo $share_url; ?>" target="_blank" class="no-underline"><button type="button" class="small-share-button opc" style="background: #1AB2E8;">T</button></a>
					<a href="https://plus.google.com/share?url=<?php echo $share_url; ?>" target="_blank" class="no-underline"><button type="button" class="small-share-button opc" style="background: #DD4B39;">G+</button></a>

				</a>

			</div>

			<div class="panel-footer quote-footer">
				
				<?php printf($language->list->footer, '<a href="author/' . $author->url . '">' . $author->name . '</a>', implode(', ', $categories)); ?>

				<?php
				if(User::logged_in()) {

					echo '<span class="pull-right quote-icons">';

					if(User::is_admin($account_user_id)) {
						echo '<a href="admin/edit-quote/' . $quote->quote_id . '" data-toggle="tooltip" title="' . $language->list->tooltip->edit . '" class="tooltipz"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;';
					}

					$query_favorite = $database->query("SELECT `id` FROM `favorites` WHERE `user_id` = {$account_user_id} AND `quote_id` = {$quote->quote_id}");

					if($query_favorite->num_rows)
						echo '<a data-id="' . $quote->quote_id . '" data-toggle="tooltip" title="' . $language->list->tooltip->unfavorite . '" class="clickable favorite tooltipz"><span class="glyphicon glyphicon-heart red"></span></a>';
					else
						echo '<a data-id="' . $quote->quote_id . '" data-toggle="tooltip" title="' . $language->list->tooltip->favorite . '" class="clickable favorite tooltipz"><span class="glyphicon glyphicon-heart-empty red"></span></a>';
					 
					echo '</span>';

				}
				?>

			</div>

		</div>

		<?php

		echo ($columns && ($i == $half || $i == $result->num_rows)) ? '</div>' : '';

		$i++;
		}

		?>

		<script>
		$(document).ready(function() {

			/* Favorite handler */
			$('.quote-footer').on('click', '.favorite', function() {
				var $div = $(this);
				var quote_id = $div.data('id');

				/* Post and get reponse */
				$.post('processing/process_favorites.php', 'quote_id='+quote_id, function(data) {
					$div.fadeOut('fast');

					setTimeout(function() {
						if(data == 'favorited') {
							$div.attr('data-original-title', '<?php echo $language->list->tooltip->unfavorite; ?>');
							$div.children('.glyphicon').removeClass('glyphicon-heart-empty').addClass('glyphicon-heart');
						} else 
						if(data == 'unfavorited') {
							$div.attr('data-original-title', '<?php echo $language->list->tooltip->favorite; ?>');
							$div.children('.glyphicon').removeClass('glyphicon-heart').addClass('glyphicon-heart-empty');
						}
						$div.fadeIn('fast');
					}, 1500);
					
				});

			});

		});
		</script>

	<?php
	}

	public function display_pagination($current_page) {
		global $settings;
		
		/* If there are results, display pagination */
		if($this->quote_results > $settings->quotes_pagination) {

			/* Establish the current page link */
			$this->pagination->set_current_page_link($current_page);

			echo '<div class="clearfix"></div>';

			$this->pagination->display($this->affix);
		}
	}


	public function filters_display() {
		global $language;

		if($this->quote_results > 0) { 

			/* Generating the link again for every filter so it doesn't mess the url */
			$order_by = (isset($_GET['order_by'])) ? preg_replace('/&order_by=[A-Za-z0-9_]+/', '', $this->affix) : $this->affix;

			?>

			<ul class="nav nav-pills nav-stacked">

				<?php if(!empty($this->affix)) { ?>
				<li class="dropdown active">
					<a href="<?php echo $this->pagination->link; ?>"><?php echo $language->list->sidebar->reset_filters; ?></a>
				</li>
				<?php } ?>

				<li class="dropdown active">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo $language->list->sidebar->order_by; ?><b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="<?php echo $this->pagination->link . $order_by . '&order_by=random'; ?>"><?php echo $language->list->sidebar->order_by_random; ?></a></li>
						<li><a href="<?php echo $this->pagination->link . $order_by; ?>"><?php echo $language->list->sidebar->order_by_favorites; ?></a></li>
						<li><a href="<?php echo $this->pagination->link . $order_by . '&order_by=quote_id'; ?>"><?php echo $language->list->sidebar->order_by_latest; ?></a></li>
					</ul>
				</li>

			</ul><br />
		<?php
		}
	}

	public static function delete_quote($quote_id) {
		global $database;

		/* Get the current image of the quote so we can delete that too */
		$stmt = $database->prepare("SELECT `image` FROM `quotes` WHERE `quote_id` = ?");
		$stmt->bind_param('s', $quote_id);
		$stmt->execute();
		bind_object($stmt, $quote);
		$stmt->fetch();
		$stmt->close();

		/* Delete the image */
		unlink('user_data/quotes/' . $quote->image);

		/* We need to make sure to delete all the data of the specific quote */
		$database->query("DELETE FROM `quotes` WHERE `quote_id` = {$quote_id}");
		$database->query("DELETE FROM `reports` WHERE `type` = 1 AND `reported_id` = {$quote_id}");
		$database->query("DELETE FROM `favorites` WHERE `quote_id` = {$quote_id}");
		$database->query("DELETE FROM `comments` WHERE `quote_id` = {$quote_id}");
		$database->query("DELETE FROM `associations` WHERE `quote_id` = {$quote_id}");

	}


}
?>