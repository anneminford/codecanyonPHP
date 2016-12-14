<?php

$language = (object) array(

	/* Global */
	'global' => (object) array(

		'submit_button'					=> 'Submit',
		'delete'						=> 'Delete',
		'edit'							=> 'Edit',
		'enable'						=> 'Enable',
		'disable'						=> 'Disable',
		'show_more'						=> 'Show More',
		'language'						=> 'Language',
		'close'							=> 'Close',

		'message_type' => (object) array(
			'error'						=> 'Error !',
			'info'						=> 'Info !',
			'success'					=> 'Success !',
		),

		'info_message' => (object) array(
			'confirm_delete'			=> 'Are you sure you want to delete this ?'
		),

		'success_message' => (object) array(
			'basic'						=> 'Your requested command was performed successfully !'
		),

		'error_message' => (object) array(
			'empty_fields'				=> 'You must fill all the fields !',
			'invalid_captcha'			=> 'The captcha code is not valid !',
			'page_not_found'			=> 'We\'re sorry but we couldn\'t find the page you searched for...',
			'invalid_file_type'			=> 'Invalid file type !',
			'invalid_image_size'		=> 'You\'re uploaded image is exceeding the size limit ( %s ) !',
			'invalid_token'				=> 'We\'re sorry but we couldn\'t perform the action because of the invalid token, please try again !',
			'command_denied'			=> 'You don\'t have access to this command !',
			'page_access_denied'		=> 'You can\'t access this page !'
		),

		'menu' => (object) array(
			'home'						=> 'Quotes',
			'logout'					=> 'Logout',
			'account'					=> 'Account',
			'settings'					=> 'Settings',
			'admin'						=> 'Admin'
		)
		
	),


	/* Quotes List */
	'list' => (object) array(

		'sidebar' => (object) array(
			'authors'					=> 'Authors',
			'categories'				=> 'Categories',
			'countries'					=> 'Countries',
			'tags'						=> 'Tags',
			'view_all'					=> 'View All',
			'reset_filters'				=> 'Reset Filters',
			'order_by'					=> 'Order by',
			'order_by_random'			=> 'Random',
			'order_by_favorites'		=> 'Favorites',
			'order_by_latest'			=> 'Latest'
		),

		'tooltip' => (object) array(
			'unfavorite'				=> 'Unfavorite',
			'favorite'					=> 'Favorite',
			'edit'						=> 'Edit quote'
		),

		'info_message' => (object) array(
			'no_quotes'					=> 'Currently there are no quotes in the database !'
		),

		'footer'						=> 'Quote by <strong>%s</strong> published in <strong>%s</strong>'

	),


	/* Quote */
	'quote' => (object) array(

		'header' 						=> 'Comments',

		'error_message' => (object) array(
			'invalid_quote'				=> 'That quote doesn\'t exist !',
			'not_active'				=> 'This quote is not yet approved !'
		),

		'info_message' => (object) array(
			'no_comments'				=> 'Currently there are no comments !'
		),

		'sidebar' => (object) array(
			'options'					=> 'Quote Options',
			'comment'					=> 'Add comment',
			'favorite'					=> 'Favorite',
			'unfavorite'				=> 'Unfavorite',
			'report'					=> 'Report Quote',
			'details'					=> 'Quote Details',
			'category'					=> 'Published in ',
			'author'					=> 'Written by %s',
			'country'					=> 'Country %s',
			'author_birth_year'			=> 'Author Birth Year: %s',
			'author_death_year'			=> 'Author Death Year: %s'
		),
		
	),


	/* Process Reports */
	'process_reports' => (object) array(

		'modal' => (object) array(
			'header'					=> 'Report Quote',
			'input'						=> 'Report Reason'
		),

		'error_message' => (object) array(
			'long_message'				=> 'Your message is too long !',
			'short_message'				=> 'Your message is too short !'
		)

	),


	/* Process Comments */
	'process_comments' => (object) array(

		'modal' => (object) array(
			'header'					=> 'Submit Comment'
		),

		'error_message' => (object) array(
			'long_message'				=> 'Your comment is too big !',
			'short_message'				=> 'Your comment is too short !'
		)

	),


	/* Submit */
	'submit' => (object) array(

		'title'							=> 'Submit Quote',
		'menu'							=> 'Submit',
		'header'						=> 'Submit Quote',

		'input' => (object) array(
			'content'					=> 'Quote Content',
			'category'					=> 'Category',
			'author'					=> 'Author',
			'tags'						=> 'Tags',
			'tags_help'					=> 'Separate tags with a comma ","',
			'image'						=> 'Quote Image',
			'image_help'				=> 'You can only add JPG, JPEG & GIF type of images.You can let it blank if you have no image !'
		),

		'error_message' => (object) array(
			'invalid_category'			=> 'That category doesn\'t exist !',
			'invalid_author'			=> 'That author doesn\'t exist !',
			'invalid_email'				=> 'You entered an invalid email !',
			'long_content'				=> 'The quote you entered has too many characters !',
			'small_image'				=> 'The quote image you added is too small !',
			'many_categories'			=> 'You chose too many categories !',
			'many_tags'					=> 'You chose too many tags !'
		),

	),


	/* Profile Settings */
	'profile_settings' => (object) array(

		'title'							=> 'Profile Settings',
		'menu'							=> 'Profile Settings',
		'header'						=> 'Profile Settings',
		'header2'						=> 'Social Information',
		'header2_help'					=> 'Enter only the id of your profile !',

		'input' => (object) array(
			'name'						=> 'Name',
			'email'						=> 'Email',
			'website'					=> 'Website',
			'location'					=> 'Location',
			'about'						=> 'About',
			'facebook'					=> 'Facebook',
			'twitter'					=> 'Twitter',
			'googleplus'				=> 'Google Plus',
			'private'					=> 'Private Profile'
		),

		'error_message' => (object) array(
			'name_length'				=> 'Name must be between 3 and 32 characters !',
			'email_exists'				=> 'We\'re sorry, but that email is already used !',
			'invalid_email'				=> 'You entered an invalid email !',
			'long_about'				=> 'We are sorry but your description is too big !',
			'long_location'				=> 'We are sorry but your location name is too big !'
		),

		'success_message' => (object) array(
			'profile_updated'			=> 'Your settings have been updated !'
		)

	),

	
	/* Change Password */
	'change_password' => (object) array(

		'title'							=> 'Change Password',
		'menu'							=> 'Change Password',
		'header'						=> 'Change Password',
		
		'input' => (object) array(
			'current_password'			=> 'Current Password',
			'new_password'				=> 'New Password',
			'repeat_password'			=> 'Repeat Password'
		),

		'error_message' => (object) array(
			'invalid_current_password'	=> 'Your current password is not valid !',
			'short_password'			=> 'Your password is too short, you must have at least 6 characters !',
			'passwords_not_matching'	=> 'Your entered passwords do not match'
		),

		'success_message' => (object) array(
			'password_updated'			=> 'Your password has been updated !'
		)

	),


	/* Reset Password */
	'reset_password' => (object) array(

		'title'							=> 'Reset Password',
		'header'						=> 'Reset Password',
		
		'input' => (object) array(
			'new_password'				=> 'New Password',
			'repeat_password'			=> 'Repeat Password'
		),

		'error_message' => (object) array(
			'short_password'			=> 'Your password is too short, you must have at least 6 characters !',
			'passwords_not_matching'	=> 'Your entered passwords do not match'
		),

		'success_message' => (object) array(
			'password_updated'			=> 'Your password has been updated !'
		)

	),

	
	/* Design Settings */
	'design_settings' => (object) array(

		'menu'							=> 'Design',
		'title'							=> 'Design Settings',
		'header'						=> 'Design Settings',

		'input'	=> (object) array(
			'avatar'					=> 'Avatar',
			'cover'						=> 'Cover',
			'avatar_help'				=> 'Only JPG, JPEG formats allowed !',
			'cover_help'				=> 'Only JPG, JPEG formats allowed !'
		),

		'error_message' => (object) array(
			'small_avatar'				=> 'Your avatar is too small, minimum 80x80 !',
			'small_cover'				=> 'Your cover is too small, minimum 1200x150 !'
		),

		'success_message' => (object) array(
			'design_settings_updated'	=> 'Your design settings have been updated !'
		)

	),

	/* Login */
	'login'	=> (object) array(

		'menu'							=> 'Login',
		'title'							=> 'Login',
		'header'						=> 'Login',

		'input' => (object) array(
			'username' 					=> 'Username',
			'password'					=> 'Password',
			'remember_me'				=> 'Remember me'
		),

		'button' => (object) array(
			'login'						=> 'Sign In',
			'lost_password'				=> 'Lost Password',
			'resend_activation'			=> 'Resend Activation'
		),

		'info_message' => (object) array(
			'logged_in'					=> 'Welcome back, you are now logged in !'
		),

		'error_message' => (object) array(
			'wrong_login_credentials'	=> 'Your username - password combination is not valid !',
			'user_not_active'			=> 'Your account is not confirmed or banned !'
		
		)

	),

	/* Lost Password */
	'lost_password'	=> (object) array(

		'title'							=> 'Lost Password',
		'header'						=> 'Lost Password',

		'input' => (object) array(
			'email' 					=> 'Email',
		),

		'notice_message' => (object) array(
			'success'					=> 'We sent an email to that accounts address if an account is registered with it !',
		),

		'email' => (object) array(
			'title'						=> 'Lost Password',
			'content' 					=> 'We generated a reset password link for your account, dont access the following url if it wasn\'t you! %sreset-password/%s/%s'
		)

	),

	/* Resend Activation */
	'resend_activation'	=> (object) array(

		'title'							=> 'Resend Activation',
		'header'						=> 'Resend Activation Email',

		'input' => (object) array(
			'email' 					=> 'Email',
		),

		'notice_message' => (object) array(
			'success'					=> 'We sent an email to that accounts address if an account is registered with it !'
		),

		'email'	=> (object) array(
			'title'						=> 'Activate your account',
			'content'					=> 'You need to activate your account by accessing the following link: %sactivate/%s/%s'
		)

	),


	/* Register */
	'register' => (object) array(

		'title'							=> 'Register',
		'menu'							=> 'Register',
		'header'						=> 'Register',

		'input' => (object) array(
			'username' 					=> 'Username',
			'name'						=> 'Name',
			'email'						=> 'Email',
			'password'					=> 'Password',
			'repeat_password'			=> 'Repeat Password'
		),

		'error_message' => (object) array(
			'username_length'			=> 'Username must be between 3 and 32 characters !',
			'name_length'				=> 'Name must be between 3 and 32 characters !',
			'user_exists'				=> 'We\'re sorry, but the <strong>%s</strong> username is already taken !',
			'email_exists'				=> 'We\'re sorry, but that email is already used !',
			'invalid_email'				=> 'You entered an invalid email !',
			'short_password'			=> 'Your password is too short, you must have at least 6 characters !',
			'passwords_not_matching'	=> 'Your entered passwords do not match'
		),

		'email'	=> (object) array(
			'title'						=> 'Activate your account',
			'content'					=> 'You need to activate your account by accessing the following link: %sactivate/%s/%s'
		),

		'success_message' => (object) array(
			'registration'				=> 'Your account has been created, check your email for the activation link !'
		)

	),
	

	/* Home */
	'home' => (object) array(
		'title'							=> 'Home',
		'header'						=> 'Latest Quotes'
	),


	/* Authors */
	'authors' => (object) array(
		'title'							=> 'Authors'
	),


	/* Countries */
	'countries' => (object) array(
		'title'							=> 'Countries List',
	),


	/* Search */
	'search' => (object) array(
		'title'							=> 'Search',
		'menu'							=> 'Write and hit enter..',
		'searching_for'					=> 'Currently searching for <strong>%s</strong>',
		
		'info_message' => (object) array(
			'no_quotes'					=> 'Sorry, but we couldn\'t find any quotes that are related to your search terms !'
		),

		'error_message' => (object) array(
			'small_query'				=> 'One of your search terms is too small !'
		)
	),


	/* My Quotes */
	'my_quotes' => (object) array(
		'title'							=> 'My Submitted Quotes',
		'menu'							=> 'My Submitted Quotes',

		'info_message' => (object) array(
			'no_quotes'						=> 'You didn\'t submitted any quote'
		)
	),


	/* My Favorite Quotes */
	'my_favorites' => (object) array(
		'title'							=> 'My Favorite Quotes',
		'menu'							=> 'My Favorite Quotes',

		'info_message' => (object) array(
			'no_quotes'						=> 'You don\'t have any favorite quotes'
		)
	),


	/* My Quotes */
	'quotes' => (object) array(
		'title'							=> 'Quotes'
	),

	/* My Profile */
	'profile' => (object) array(
		'menu'							=> 'My Profile',

		'error_message' => (object) array(
			'invalid_account'				=> 'Invalid Account',
			'private_profile'				=> 'This profile is currently private !'
		),

		'info_message' => (object) array(
			'no_quotes'						=> 'This user didn\'t submit any quote'
		)
		
	),


	/* ADMIN Categories Management */
	'admin_categories_management' => (object) array(

		'title'							=> 'Categories Management',
		'menu'							=> 'Categories Management',
		'header'						=> 'Add Category',

		'table'	=> (object) array(
			'name'						=> 'Name',
			'url'						=> 'Url',
			'actions'					=> 'Actions'
		),

		'info_message' => (object) array(
			'confirm_delete'			=> 'By deleting the category, all the quotes within the category will be deleted !',
		),

		'input' => (object) array(
			'name'						=> 'Name',
			'description'				=> 'Description',
			'description_help'			=> 'The description will be used for meta-description tag as well !',
			'url'						=> 'Url',
			'url_help'					=> 'The link that will be in the url.Example: /category/<strong>test-category-name</strong>',
			'parent'					=> 'Parent Category'
		)
			
	),


	/* ADMIN Authors Management */
	'admin_authors_management' => (object) array(

		'title'							=> 'Authors Management',
		'menu'							=> 'Authors Management',
		'header'						=> 'Add Author',

		'table'	=> (object) array(
			'name'						=> 'Name',
			'url'						=> 'Url',
			'birth_year'				=> 'Birth Year',
			'death_year'				=> 'Death Year',
			'country'					=> 'Country',
			'actions'					=> 'Actions'
		),

		'info_message' => (object) array(
			'confirm_delete'			=> 'By deleting this author, all the quotes added by that specific author be deleted !',
		),

		'input' => (object) array(
			'name'						=> 'Name',
			'url'						=> 'Url',
			'url_help'					=> 'The link that will be in the url.Example: /author/<strong>test-author-name</strong>',
			'birth_year'				=> 'Birth Year',
			'death_year'				=> 'Death Year',
			'country'					=> 'Country'
		)
			
	),

	/* ADMIN Users Management */
	'admin_users_management' => (object) array(

		'title'							=> 'Users Management',
		'menu'							=> 'Users Management',

		'table'	=> (object) array(
			'username'					=> 'Username',
			'name'						=> 'Name',
			'email'						=> 'Email',
			'ip'						=> 'IP',
			'registration_date'			=> 'Reg. Date',
			'actions'					=> 'Actions'
		),

		'tooltip' => (object) array(
			'admin'						=> 'Admin',
			'owner'						=> 'Owner'
		),

		'info_message' => (object) array(
			'confirm_delete'			=> 'By deleting this author, all the quotes added by that specific author be deleted !',
		),

		'error_message' => (object) array(
			'self_delete'				=> 'You can\'t delete yourself !',
			'self_status'				=> 'You can\'t change your own status !'
		)
			
	),


	/* ADMIN Reports Management */
	'admin_reports_management' => (object) array(

		'title'							=> 'Reports Management',
		'menu'							=> 'Reports Management',

		'table'	=> (object) array(
			'type'						=> 'Type',
			'username'					=> 'User',
			'reported_id'				=> 'Reported Id',
			'date'						=> 'Date',
			'actions'					=> 'View report'
		),

		'tooltip' => (object) array(
			'admin'						=> 'Admin',
			'owner'						=> 'Owner'
		),

		'info_message' => (object) array(
			'confirm_delete'			=> 'By deleting this author, all the quotes added by that specific author be deleted !',
		),

		'error_message' => (object) array(
			'self_delete'				=> 'You can\'t delete yourself !',
			'self_status'				=> 'You can\'t change your own status !'
		)
			
	),


	/* ADMIN Report Edit */
	'admin_report_edit' => (object) array(

		'title'							=> 'View Report',
		'header'						=> 'View Report',

		'input'	=> (object) array(
			'user_profile'				=> 'User Profile',
			'date'						=> 'Date',
			'type'						=> 'Type',
			'reported_id'				=> 'Reported Id',
			'reported'					=> 'Reported %s',
			'reason'					=> 'Reason of reporting',
		),

		'error_message'	=> (object) array(
			'invalid_report'			=> 'That report wasn\'t found !'
		),

		'button' => (object) array(
			'delete'			=> 'Delete %s'
		)
	
	),


	/* ADMIN Quote Edit */
	'admin_quote_edit' => (object) array(

		'title'							=> 'Edit Quote',
		'header'						=> 'Edit Quote',

		'input' => (object) array(
			'content'					=> 'Quote Content',
			'category'					=> 'Category',
			'author'					=> 'Author',
			'tags'						=> 'Tags',
			'tags_help'					=> 'Separate tags with a comma ","',
			'image'						=> 'Quote Image',
			'image_help'				=> 'You can only add JPG, JPEG & GIF type of images.You can let it blank if you have no image !',
			'active'					=> 'Active'
		),

		'error_message' => (object) array(
			'invalid_quote'				=> 'That quote doesn\'t exist !',
			'invalid_category'			=> 'That category doesn\'t exist !',
			'invalid_author'			=> 'That author doesn\'t exist !',
			'long_content'				=> 'The quote you entered has too many characters !',
			'small_image'				=> 'The quote image you added is too small !',
			'many_categories'			=> 'You chose too many categories !',
			'many_tags'					=> 'You chose too many tags !'
		)

	),
	

	/* ADMIN Author Edit */
	'admin_author_edit' => (object) array(

		'title'							=> 'Edit Author',
		'header'						=> 'Edit Author',

		'input' => (object) array(
			'name'						=> 'Name',
			'url'						=> 'Url',
			'url_help'					=> 'The link that will be in the url.Example: /author/<strong>test-author-name</strong>',
			'birth_year'				=> 'Birth Year',
			'death_year'				=> 'Death Year',
			'country'					=> 'Country'
		),

		'error_message'	=> (object) array(
			'invalid_author'			=> 'That author wasn\'t found !'
		),
			
	),


	/* ADMIN Category Edit */
	'admin_category_edit' => (object) array(

		'title'							=> 'Edit Category',
		'header'						=> 'Edit Category',

		'input' => (object) array(
			'name'						=> 'Name',
			'description'				=> 'Description',
			'description_help'			=> 'The description will be used for meta-description tag as well !',
			'url'						=> 'Url',
			'url_help'					=> 'The link that will be in the url.Example: /category/<strong>test-category-name</strong>',
			'parent'					=> 'Parent Category'
		),

		'error_message'	=> (object) array(
			'invalid_category'			=> 'That category wasn\'t found !'
		),
			
	),


	/* ADMIN User Edit */
	'admin_user_edit' => (object) array(

		'title'							=> 'Edit User',
		'header'						=> 'Edit User',
		'header2'						=> 'Edit Social Data',
		'header2_help'					=> 'Enter only the id of your profile !',
		'header3'						=> 'Change Password',
		'header3_help'					=> 'Leave it empty if you don\'t want to change the password !',

		'input' => (object) array(
			'username'					=> 'Username',
			'name'						=> 'Name',
			'email'						=> 'Email',
			'website'					=> 'Website',
			'location'					=> 'Location',
			'about'						=> 'About',
			'type'						=> 'Account Type',
			'type_help'					=> '0->Normal User; 1->Admin; 2->Owner',
			'new_password'				=> 'New Password',
			'repeat_password'			=> 'Repeat Password',
			'facebook'					=> 'Facebook',
			'twitter'					=> 'Twitter',
			'googleplus'				=> 'Google Plus'
		),
		
		'error_message'	=> (object) array(
			'name_length'				=> 'Name must be between 3 and 32 characters !',
			'invalid_account'			=> 'That account wasn\'t found !',
			'email_exists'				=> 'We\'re sorry, but that email is already used !',
			'invalid_email'				=> 'You entered an invalid email !',
			'short_password'			=> 'Your password is too short, you must have at least 6 characters !',
			'passwords_not_matching'	=> 'Your entered passwords do not match',
			'long_about'				=> 'We are sorry but your description is too big !',
			'long_location'				=> 'We are sorry but your location name is too big !'
		)
			
	),

	/* ADMIN Website Settings */
	'admin_website_settings' => (object) array(

		'title'							=> 'Website Settings',
		'menu'							=> 'Website Settings',
		'header'						=> 'Website Settings',

		'input' => (object) array(
			'title'						=> 'Website title',
			'meta_description'			=> 'Meta Description',
			'analytics_code'			=> 'Analytics Tracking Code',
			'analytics_code_help'		=> 'Example: UA-22222222-33 ( Leave empty if you dont have Googele Analytics )',
			'banned_words'				=> 'Banned Words',
			'banned_words_help'			=> 'separated by a comma, filter active on: comments and blog posts',
			'avatar_max_size'			=> 'Avatar Maximum Upload Size (bytes)',
			'cover_max_size'			=> 'Profile Cover / Quotes Image (bytes)',
			'contact_email'				=> 'Contact Email',
			'contact_email_help'		=> 'The email that the users get the email from / the \'reply-to\' email ( registration / email activation / lost password )',
			'email_confirmation'		=> 'Register - Confirmation email',
			'quotes_pagination'			=> 'Quotes Pagination',
			'quotes_pagination_help'	=> 'How many quotes will be displayed on a single page',
			'authors_pagination'		=> 'Authors Pagination',
			'authors_pagination_help'	=> 'How many authors will be displayed on the authors page',
			'sidebar_maximum_authors'			=> 'Sidebar Maximum Authors',
			'sidebar_maximum_authors_help'		=> 'How many authors will be displayed on the sidebar',
			'sidebar_maximum_countries'			=> 'Sidebar Maximum Countries',
			'sidebar_maximum_countries_help'	=> 'How many countries will be displayed on the sidebar',
			'sidebar_maximum_tags'		=> 'Sidebar Maximum tags',
			'sidebar_maximum_tags_help'	=> 'How many tags will be displayed on the sidebar',
			'quote_maximum_categories'	=> 'Quote Maximum Categories',
			'quote_maximum_tags'		=> 'Quote Maximum Tags',
			'new_quotes_visibility'		=> 'Make New Quotes: Public (unchecked: Private)',
			'time_zone'					=> 'Time Zone',
			'top_ads'					=> 'Top Ads Code',
			'bottom_ads'				=> 'Bottom Ads Code',
			'side_ads'					=> 'Side Ads Code',
			'recaptcha'					=> 'Enable ReCaptcha',
			'recaptcha_help'			=> 'If enabled, you must add the public and private key from Google Recaptcha API',
			'public_key'				=> 'Public Key',
			'private_key'				=> 'Private Key',
			'social_help'				=> 'Enter only the id / name of the page',
			'facebook'					=> 'Facebook',
			'twitter'					=> 'Twitter',
			'googleplus'				=> 'Google Plus',
			'guest_submit'				=> 'Guest Submit'
		),
		
		'tab' => (object) array(
			'main'						=> 'Main',
			'quotes'					=> 'Quotes',
			'ads'						=> 'Ads',
			'captcha'					=> 'Captcha',
			'social'					=> 'Social'
		)
			
	),


	/* ADMIN Website statistics */
	'admin_website_statistics' => (object) array(

		'title'							=> 'Website Statistics',
		'menu'							=> 'Website Statistics',
		'header'						=> 'Total Statistics',
		'header2'						=> 'This month statistics',
		'header3'						=> 'User Statistics',
		'header4'						=> 'Quotes Statistics',


		'table' => (object) array(
			'categories'				=> 'Categories:',
			'comments'					=> 'Comments:',
			'reports'					=> 'Reports:',
			'quotes'					=> 'Quotes:',
			'accounts'					=> 'Users:',
			'new_accounts_today'		=> 'New Accounts Today:',
			'online_accounts'			=> 'Online Users;',
			'active_accounts_today'		=> 'Active Users Today:',
			'owners'					=> 'Owners:',
			'admins'					=> 'Admins:',
			'private_accounts'			=> 'Private Accounts:',
			'confirmed_accounts'		=> 'Confirmed Accounts:',
			'unconfirmed_accounts'		=> 'Unconfirmed Accounts:',
			'new_quotes_today'			=> 'New Quotes Today:',
			'no_image_quotes'			=> 'Quotes without image:',
			'image_quotes'				=> 'Quotes with image:',
			'active_quotes'				=> 'Active Quotes:',
			'inactive_quotes'			=> 'Inactive Quotes:'
		),
			
	),


	/* ADMIN Quotes Management */
	'admin_quotes_management' => (object) array(

		'title'							=> 'Quotes Management',
		'menu'							=> 'Quotes Management',
		'header'						=> 'Pending Approval Quotes',

		'info_message' => (object) array(
			'no_quotes'					=> 'Currently there are no quotes that are pending for approval !'
		)
			
	)


);

?>