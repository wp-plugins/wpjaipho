<?php


// ADMIN

function jaipho_menu() {
	add_options_page( 'WPJaipho Options', 'WPJaipho', 'manage_options', 'jaipho', 'jaipho_settings_page' );
}


function jaipho_settings_page() {

	if (!current_user_can('manage_options'))
	{
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}

	require_once 'Jph/Wp/JaiphoPlugin.php';
	require_once 'Jph/Wp/Admin/OptionsPage.php';
	
	$wp_jaipho	=	Jph_Wp_JaiphoPlugin::createInstance();
	
	$page		=	new Jph_Wp_Admin_OptionsPage( $wp_jaipho->configuration);
	$page->init();

	$page->printHtml();
}


// PUBLIC

function jaiphp_template_include_filter( $template)
{
	global $post;

	if($post->post_type == 'attachment')
	{
		if (strpos( $post->post_mime_type, 'image') !== false)
		{
			return dirname(__FILE__) . '/gallery.php';
		}
	}
	return $template;
}

