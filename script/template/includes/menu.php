<div class="navbar navbar-default navbar-static-top navbar-no-margin" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand hidden-sm" href="index"><?php echo $settings->title; ?></a>
		</div>

		<form method="post" action="search" class="navbar-form navbar-left" style="padding: 15px;">
			<div class="form-group">
				<input type="text" name="search" class="form-control" placeholder="<?php echo $language->search->menu ;?>">
			</div>
		</form>

		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li><a href="quotes"><?php echo $language->global->menu->home; ?></a></li>

				<?php if($settings->guest_submit || User::logged_in()) { ?>
				<li><a href="submit"><?php echo $language->submit->menu; ?></a></li>
				<?php } ?>

				<?php if(User::logged_in() == false) { ?>
				<li><a href="login"><?php echo $language->login->menu; ?></a></li>
				<li><a href="register"><?php echo $language->register->menu; ?></a></li>
				<?php } else { ?>
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo $language->global->menu->account; ?> <span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li><a href="my-quotes"><?php echo $language->my_quotes->menu; ?></a></li>				
						<li><a href="my-favorites"><?php echo $language->my_favorites->menu; ?></a></li>
						<li><a href="profile/<?php echo $account->username; ?>"><?php echo $language->profile->menu; ?></a></li>
					</ul>
				</li>
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo $language->global->menu->settings; ?> <span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li><a href="settings/profile"><?php echo $language->profile_settings->menu; ?></a></li>
						<li><a href="settings/design"><?php echo $language->design_settings->menu; ?></a></li>
						<li><a href="settings/password"><?php echo $language->change_password->menu; ?></a></li>
					</ul>
				</li>

				<?php if(User::is_admin($account_user_id)) { ?>
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo $language->global->menu->admin; ?> <span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li><a href="admin/users-management"><?php echo $language->admin_users_management->menu; ?></a></li>
						<li><a href="admin/categories-management"><?php echo $language->admin_categories_management->menu; ?></a></li>
						<li><a href="admin/authors-management"><?php echo $language->admin_authors_management->menu; ?></a></li>
						<li><a href="admin/reports-management"><?php echo $language->admin_reports_management->menu; ?></a></li>
						<li><a href="admin/quotes-management"><?php echo $language->admin_quotes_management->menu; ?></a></li>
						<?php if(User::get_type($account_user_id) > 1) { ?>
						<li><a href="admin/website-settings"><?php echo $language->admin_website_settings->menu; ?></a></li>
						<?php } ?>
						<li><a href="admin/website-statistics"><?php echo $language->admin_website_statistics->menu; ?></a></li>
					</ul>
				</li>
				<?php } ?>

				<li><a href="logout"><?php echo $language->global->menu->logout; ?></a></li>
				<?php } ?>
			</ul>

			
		</div>
	</div>
</div>


