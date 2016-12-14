				<?php include 'includes/close_main_column.php'; ?>

				<!-- START Sidebar -->
				<?php if(!in_array(@$_GET['page'], $full_width_pages)) { ?>
					<div class="col-md-2">
						<?php include 'includes/sidebar.php'; ?>
					</div>
				<?php } ?>
				<!-- END Sidebar -->

			</div><!-- END ROW -->

			<?php include 'includes/widgets/bottom_ads.php'; ?>

		</div><!-- END Container -->

		<div class="sticky-footer">
			<div class="container">
				<?php include 'includes/footer.php'; ?>
			</div>
		</div>
	</body>
</html>