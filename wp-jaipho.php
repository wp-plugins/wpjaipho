<?php 
/*
Plugin Name: WPJaipho
Version: 1.1.0
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
Xx_Log::createLog( realpath(ABSPATH.'/../').'/log/', 'jaipho', false);


// ENABLE PLUGIN

if (is_admin())
{
	require_once 'functions.php';
	
	add_action( 'admin_menu', 'jaipho_menu' );
}
else
{
	require_once 'Pipho/DeviceInfo.php';
	
	if (Pipho_DeviceInfo::isSupported())  // tweak - not to load incldues if jaipho does not supports the visitor's user agent
	{
		require_once 'Jph/Wp/JaiphoPlugin.php';
			
		$wp_jaipho	=	Jph_Wp_JaiphoPlugin::createInstance();
		
		if ($wp_jaipho->shouldActivateJaipho())
		{
			require_once 'functions.php';
				
			add_filter( 'template_include', 'jaiphp_template_include_filter');
		}
	}

}


