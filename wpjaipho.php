<?php 
/*
Plugin Name: WPJaipho
Version: 1.2.0
Plugin URI: http://www.jaipho.com
Description: WPJaipho is a mobile image gallery plugin optimized for iPhone and iPad users. 
Author: tole
*/



set_include_path(implode(PATH_SEPARATOR, array(
	plugin_dir_path( __FILE__).'library',
	get_include_path()
)));

// LOG
require_once 'Xx/Log.php';

define( 'XX_LOG_ENABLED', false);
Xx_Log::createLog( realpath(ABSPATH.'/../').'/log/', 'jaipho', true);


// ENABLE PLUGIN
if (is_admin())
{
	require_once 'functions.php';
	
	add_action( 'admin_menu', 'jaipho_menu' );
}
else
{
	require_once 'Pipho/DeviceInfo.php';
	
	if (Pipho_DeviceInfo::isSupported())  // tweak - not to load incldues if jaipho does not supports the visitor's user agent at all
	{
		require_once 'Jph/Wp/JaiphoPlugin.php';
			
		$wp_jaipho	=	Jph_Wp_JaiphoPlugin::getInstance();
		
		$enabled	=	$wp_jaipho->shouldActivateJaipho();
		$enabled 	= 	apply_filters('jaipho_plugin_enabled_filter', $enabled);
		
		if ($enabled)
		{
			require_once 'functions.php';
				
			add_filter( 'template_include', 'jaipho_template_include_filter');
			
			// NGG
			if ($wp_jaipho->isNggEnabled())
			{
				add_filter( 'option_ngg_options', 'jaipho_ngg_fix_options_filter');
			}
		}
	}
}

// thumbnails for Jaipho - used for media library galleries
add_image_size( 'jaipho-thumbnail', 75, 75, true );

