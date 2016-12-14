<?php
$page_title = $language->home->title;

if(isset($_GET['page'])) {
	switch($_GET['page']) {
		case 'design_settings'				:	$page_title = $language->design_settings->title;				break;
		case 'login' 						:	$page_title = $language->login->title;	 						break;
		case 'resend_activation'			:	$page_title = $language->resend_activation->title;				break;
		case 'lost_password'				:	$page_title = $language->lost_password->title;					break;
		case 'reset_password'				:	$page_title = $language->reset_password->title;					break;
		case 'register'						:	$page_title = $language->register->title; 						break;
		case 'not_found' 					:	$page_title = $language->not_found->title;						break;
		case 'change_password'				:	$page_title = $language->change_password->title;				break;
		case 'profile_settings'				:	$page_title = $language->profile_settings->title;				break;
		case 'admin_users_management'		:	$page_title = $language->admin_users_management->title;			break;
		case 'admin_user_edit'				:	$page_title = $language->admin_user_edit->title;				break;
		case 'admin_categories_management'	:	$page_title = $language->admin_categories_management->title;	break;
		case 'admin_authors_management'		:	$page_title = $language->admin_authors_management->title;		break;
		case 'admin_reports_management'		:	$page_title = $language->admin_reports_management->title;		break;
		case 'admin_report_edit'			:	$page_title = $language->admin_report_edit->title;				break;
		case 'admin_quote_edit'				:	$page_title = $language->admin_quote_edit->title;				break;
		case 'admin_category_edit'			:	$page_title = $language->admin_category_edit->title;			break;
		case 'admin_website_settings'		:	$page_title = $language->admin_website_settings->title;			break;
		case 'admin_website_statistics'		:	$page_title = $language->admin_website_statistics->title;		break;
		case 'quotes'						:	$page_title = $language->quotes->title;							break;
		case 'my_quotes'					:	$page_title = $language->my_quotes->title;						break;
		case 'my_favorites'					:	$page_title = $language->my_favorites->title;					break;
		case 'submit'						:	$page_title = $language->submit->title;							break;
		case 'authors'						:	$page_title = $language->authors->title;						break;
		case 'countries'					:	$page_title = $language->countries->title;						break;
		case 'quote'						:	$page_title = string_resize($quote->content, 42);				break;
		case 'category'						:	$page_title = empty($category->title) ? $category->name : $category->title;		break;
		case 'profile'  					:	$page_title = $profile_account->name; 							break;
		case 'author'  						:	$page_title = $author->name;									break;
		case 'country'  					:	$page_title = country_check(2, $_GET['country_code']); 			break;
		case 'tag'							:	$page_title = $tag->name;
	}
}

$page_title .= ' - ' . $settings->title;
?>