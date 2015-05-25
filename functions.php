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
	
	$wpjaipho	=	Jph_Wp_JaiphoPlugin::getInstance();
	
	$page		=	new Jph_Wp_Admin_OptionsPage( $wpjaipho->configuration);
	$page->init();

	$page->printHtml();
}


// PUBLIC

function jaipho_template_include_filter( $template)
{
	$wpjaipho	=	Jph_Wp_JaiphoPlugin::getInstance();
	
	if(jaipho_is_wp_gallery_call() || (jaipho_is_ngg_call() && $wpjaipho->isNggEnabled()))
	{
 		if (jaipho_is_ngg_call())
			add_filter( 'jaipho_template_api_handler_filter', 'jaipho_ngg_set_template_handler_filter');
		
 		add_action('wp_print_styles', 'pm_remove_all_styles', 100);
 		
 		
 		$template	=	dirname(__FILE__) . '/gallery.php';
 		$template 	= 	apply_filters('jaipho_template_file_filter', $template);
		return $template;
	}
	
	return $template;
}

function pm_remove_all_styles() {
	global $wp_styles;
		
	$fixed	=	array();
	foreach ($wp_styles->queue as $style)
	{
		if (strpos( $style, 'jaipho') !== false)
			$fixed[] = $style;
	}
		
	$wp_styles->queue = $fixed;
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
	
	Xx_Log::logDebug( 'pid ['.$pid.']');
	Xx_Log::logDebug( 'NGG_PLUGIN_DIR ['.defined('NGG_PLUGIN_DIR').'], NGGFOLDER ['.defined('NGGFOLDER').']');
	
	$ngg_gallery	=	( defined('NGG_PLUGIN_DIR') || defined('NGGFOLDER') ) && !empty($pid);

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

// YOST WORDPRESS SEO
/**
 * Forces not to redirect attahments
 * @param array $optopns
 * @return array
 */
function jaipho_wpseo_fix_options_filter( $optopns)
{
	unset( $optopns['redirectattachment']);
	return $optopns;
}

// PARALLAX THEME
function jaipho_parallax_fix_options_filter( $optopns)
{
	$optopns['gallery_lightbox']	=	0;
	return $optopns;
}



