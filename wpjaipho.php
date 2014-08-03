<?php 
/*
Plugin Name: WPJaipho Mobile Gallery
Version: 1.4.5
Plugin URI: http://www.jaipho.com
Description: WPJaipho is a mobile image gallery plugin optimized for iPhone, iPad and Android users. 
Author: tole
*/



set_include_path(implode(PATH_SEPARATOR, array(
	plugin_dir_path( __FILE__).'library',
	get_include_path()
)));

// LOG
require_once 'Xx/Log.php';

define( 'XX_DEBUG_ENABLED', WP_DEBUG);
define( 'XX_LOG_ENABLED', WP_DEBUG && WP_DEBUG_LOG);
Xx_Log::createDefaultLog();
// Xx_Log::createLog( @realpath(ABSPATH.'/../').'/log/', 'jaipho', true);


// ENABLE PLUGIN
if (is_admin())
{
	require_once 'functions.php';
	
	add_action( 'admin_menu', 'jaipho_menu' );
}
else
{
	require_once 'Jph/Wp/JaiphoPlugin.php';
		
	$wpjaipho	=	Jph_Wp_JaiphoPlugin::getInstance();
	
	$enabled	=	$wpjaipho->shouldActivateJaipho();
	$enabled 	= 	apply_filters('jaipho_plugin_enabled_filter', $enabled);

	if ($enabled)
	{
		Xx_Log::logDebug( 'WpJaipho is enabled');
		
		require_once 'functions.php';
			
		add_filter( 'template_include', 'jaipho_template_include_filter');
		
		// NGG
		if ($wpjaipho->isNggEnabled())
		{
			Xx_Log::logDebug( 'Fixing ngg options');
			add_filter( 'option_ngg_options', 'jaipho_ngg_fix_options_filter');
		}
		
		Xx_Log::logDebug( 'Fixing other plugins and themes');
		add_filter( 'option_wpseo_permalinks', 'jaipho_wpseo_fix_options_filter');
		add_filter( 'jp_carousel_maybe_disable', 'jaipho_carousel_disable_filter');
		add_filter( 'option_cyberchimps_options', 'jaipho_parallax_fix_options_filter');
	}
}

function jaipho_carousel_disable_filter()
{ return true; }

// thumbnails for Jaipho - used for media library galleries
add_image_size( 'jaipho-thumbnail', 75, 75, true );

