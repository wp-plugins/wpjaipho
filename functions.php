<?php


// ADMIN

function jaipho_menu() 
{
	add_options_page( 'WPJaipho Options', 'WPJaipho', 'manage_options', 'wp-jaipho', 'jaipho_settings_page' );
}


function jaipho_settings_page() 
{
	if (!current_user_can('manage_options'))
	{
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}

	require_once 'Jph/Wp/JaiphoPlugin.php';
	require_once 'Jph/Wp/Admin/OptionsPage.php';
	
	$wp_jaipho	=	Jph_Wp_JaiphoPlugin::getInstance();
	
	$page		=	new Jph_Wp_Admin_OptionsPage( $wp_jaipho->configuration);
	$page->init();

	$page->printHtml();
}


// PUBLIC

function jaipho_template_include_filter( $template)
{
	$wp_jaipho	=	Jph_Wp_JaiphoPlugin::getInstance();
	
	if(jaipho_is_wp_gallery_call() || (jaipho_is_ngg_call() && $wp_jaipho->isNggEnabled()))
	{
 		if (jaipho_is_ngg_call())
			add_filter( 'jaipho_template_api_handler_filter', 'jaipho_ngg_set_template_handler_filter');
		
 		$template	=	dirname(__FILE__) . '/gallery.php';
 		$template 	= 	apply_filters('jaipho_template_file_filter', $template);
		return $template;
	}
	
	return $template;
}

function jaipho_is_wp_gallery_call()
{
	global $post;
	
	$wp_gallery		=	$post->post_type == 'attachment' && strpos( $post->post_mime_type, 'image') !== false;
	
	return $wp_gallery;
}


// NGG
function jaipho_is_ngg_call()
{
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	$pid   			= 	get_query_var('pid');
	$ngg_gallery	=	is_plugin_active( 'nextgen-gallery/nggallery.php') && !empty($pid);

	return $ngg_gallery;
}

/**
 * Forces the image browser mode
 * @param array $optopns
 * @return array
 */
function jaipho_ngg_fix_options_filter( $optopns)
{
	$optopns['galImgBrowser']	=	true;
	return $optopns;
}

/**
 * Sets the NextGEN gallery handler class which will be created and used in template file
 * @param string $handler
 * @return string
 */
function jaipho_ngg_set_template_handler_filter( $handler)
{
	$handler	=	'Jph_Wp_NextGenGalleryTemplate';
	return $handler;
}




