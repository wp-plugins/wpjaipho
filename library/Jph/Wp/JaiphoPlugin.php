<?php

require_once 'Pipho/DeviceInfo.php';

require_once 'Jph/Wp/ScriptConfiguration.php';
require_once 'Jph/Wp/ScriptConfigurationOption.php';
require_once 'Jph/Wp/MedialLibraryTemplate.php';

class Jph_Wp_JaiphoPlugin
{
	
	/**
	* @var Jph_Wp_JaiphoPlugin
	*/
	private static $_instance;
	
	
	/**
	 * @var Jph_Wp_ScriptConfiguration
	 */
	public $configuration;
	
	/**
	* @return Jph_Wp_JaiphoPlugin
	*/
	public static function createInstance()
	{
		if (isset(self::$_instance))
			throw new Exception( 'Allready created');
		
		self::$_instance	=	new Jph_Wp_JaiphoPlugin();
		self::$_instance->init();
		return self::$_instance;
	}
	
	/**
	* @return Jph_Wp_JaiphoPlugin
	*/
	public static function getInstance()
	{
		if (!isset(self::$_instance))
			throw new Exception( 'Not created yet. Use the createInstance() before this call.');

		return self::$_instance;
	}
	
	private function __construct()
	{}
	
	private function init()
	{
		$this->configuration	=	new Jph_Wp_ScriptConfiguration();
		$this->configuration->init();
		
		
		
	}
	
	
	/**
	 * @return Jph_Wp_ITemplateApi
	 */
	public static function getTemplateApi()
	{
		$api	=	new Jph_Wp_MediaLibraryTemplate( self::getInstance());
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
		if (!Pipho_DeviceInfo::isSupported())
			return false;
		
		if (Pipho_DeviceInfo::isIpad() && $this->getOptionValue( 'jaipho_disable_ipad'))
			return false;
		
		return true;
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
	
	
}










	