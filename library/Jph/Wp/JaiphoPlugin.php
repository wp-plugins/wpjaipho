<?php

require_once 'Pipho/DeviceInfo.php';

require_once 'Jph/Wp/PluginConfiguration.php';
require_once 'Jph/Wp/MedialLibraryTemplate.php';
require_once 'Jph/Wp/NextGenGalleryTemplate.php';

class Jph_Wp_JaiphoPlugin
{
	
	/**
	* @var Jph_Wp_JaiphoPlugin
	*/
	private static $_instance;
	
	
	/**
	 * @var Jph_Wp_PluginConfiguration
	 */
	public $configuration;
	
	
	/**
	 * There is no need to have more than one instance - singleton
	* @return Jph_Wp_JaiphoPlugin
	*/
	public static function getInstance()
	{
		if (!isset(self::$_instance))
		{
			self::$_instance	=	new Jph_Wp_JaiphoPlugin();
			self::$_instance->init();
		}
		
		return self::$_instance;
	}
	
	private function __construct()
	{}
	
	private function init()
	{
		$this->configuration	=	new Jph_Wp_PluginConfiguration();
		$this->configuration->init();
	}
	
	
	/**
	 * Factory method which creates gallery template api object. This method and Jph_Wp_ITemplateApi concept
	 * enables us to run Jaipho on different image gallery mechanisms (wp native, nextgen gallery ...)
	 * @return Jph_Wp_ITemplateApi
	 */
	public static function getTemplateApi()
	{
		$handler	=	'Jph_Wp_MediaLibraryTemplate';
		$handler 	= 	apply_filters('jaipho_template_api_handler_filter', $handler);
		
		$api		=	new $handler( self::getInstance());
		$api->init();
		
		return $api;
	}
	
	
	/**
	* Returns should Jaipho be enabled for current user (User-Agent header).
	* This method calcualetes in the possible admin wp options too.
	* @return boolean
	*/
	public function shouldActivateJaipho()
	{
		$user_agents	=	$this->getOptionValue( 'jaipho_custom_user_agents');

		if ($user_agents)
		{
			$subject		=	$_SERVER['HTTP_USER_AGENT'];
			$user_agents	=	split( ',', $user_agents);
			foreach ( $user_agents as $user_agent)
			{
				if (stristr( $subject, trim( $user_agent)))
					return true;
			}
		}
		
		
		
		if (!Pipho_DeviceInfo::isSupported())
			return false;
		
		if (Pipho_DeviceInfo::isIpad() && $this->getOptionValue( 'jaipho_disable_ipad'))
			return false;
		
		if (Pipho_DeviceInfo::isAndroid() && $this->getOptionValue( 'jaipho_disable_android'))
			return false;
		
		return true;
	}
	
	public function isNggEnabled()
	{
		return !$this->getOptionBoolValue( 'jaipho_disable_ngg');
	}
	
	
	// UTIL
	/**
	* Helper for getting option value from an Jph_Wp_ConfigurationOption object.
	* @param string $optionName
	*/
	public function getOptionValue( $optionName)
	{
		return $this->configuration->getOption($optionName)->getValue();
	}
	
	public function getOptionBoolValue( $optionName)
	{
		return filter_var( $this->configuration->getOption($optionName)->getValue(), FILTER_VALIDATE_BOOLEAN);
	}
	
	
}










	