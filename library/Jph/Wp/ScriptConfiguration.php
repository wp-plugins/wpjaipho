<?php

require_once 'Jph/Wp/ScriptConfigurationOption.php';
require_once 'Jph/Wp/ConfigurationSection.php';
require_once 'Jph/Wp/ConfigurationOption.php';

require_once 'Jph/Wp/Admin/Editor/Input.php';
require_once 'Jph/Wp/Admin/Editor/Select.php';
require_once 'Jph/Wp/Admin/Editor/Checkbox.php';

require_once 'Jph/Wp/Admin/Validator/OptionCssTimeDefinition.php';
require_once 'Jph/Wp/Admin/Validator/OptionIntListDefinition.php';
require_once 'Jph/Wp/Admin/Validator/OptionIntPositiveDefinition.php';
require_once 'Jph/Wp/Admin/Validator/OptionListDefinition.php';


class Jph_Wp_ScriptConfiguration
{
	const GROUP_BASIC		=	'basic';
	const GROUP_ADVANCED	=	'advanced';
	const GROUP_DEBUG		=	'debug';
	
	public $sections	=	array();
	
	public function __construct()
	{}
	
	
	public function init()
	{
		$startupp_modes	=	array ( 'thumbs', 'slider', 'slideshpw');
		$truefalses		=	array ( 'true', 'false');
		
		
		$this->sections[]	=	new Jph_Wp_ConfigurationSection( 'plugin-parameters', 'Plugin options',
			array(
			
				new Jph_Wp_ConfigurationOption( 'Splashscreen loading text', 'jaipho_loading_title', 'JAIPHO',
						new Jph_Wp_Admin_Editor_Input()),
		
				new Jph_Wp_ConfigurationOption( 'Disable iPad support', 'jaipho_disable_ipad', 0,
						new Jph_Wp_Admin_Editor_Checkbox( '1')),
					));
		
		
		// CONFIGURATION BLOCK
		// Jaipho v 0.55
		
		
		// basic parameters
		$this->sections[]	=	new Jph_Wp_ConfigurationSection( 'basic-parameters', 'Jaipho Javascript - Basic parameters', 
			array(
				new Jph_Wp_ScriptConfigurationOption( 
						'TOOLBARS_HIDE_TIMEOUT', 'Toolbars hide timout (ms):', 'jaipho_js_toolbars_hide_timeout', 5000,
						new Jph_Wp_Admin_Editor_Input(),
						new Jph_Wp_Admin_Validator_OptionIntPositiveDefinition()),
				new Jph_Wp_ScriptConfigurationOption( 
						'SLIDESHOW_ROLL_TIMEOUT', 'Slideshow roll timout (ms):', 'jaipho_js_slideshow_roll_timeout', 4000,
						new Jph_Wp_Admin_Editor_Input(),
						new Jph_Wp_Admin_Validator_OptionIntPositiveDefinition()),
				new Jph_Wp_ScriptConfigurationOption( 
						'SLIDE_SCROLL_DURATION', 'Slide scroll duration', 'jaipho_js_slide_scroll_duration', '0.4s',
						new Jph_Wp_Admin_Editor_Input(),
						new Jph_Wp_Admin_Validator_OptionCssTimeDefinition()),
				new Jph_Wp_ScriptConfigurationOption( 
						'SLIDE_PRELOAD_TIMEOUT', 'Slide preload timeout', 'jaipho_js_slide_preload_timeout', 1100,
						new Jph_Wp_Admin_Editor_Input(), 
						new Jph_Wp_Admin_Validator_OptionIntPositiveDefinition()),
				new Jph_Wp_ScriptConfigurationOption( 
						'SLIDE_PRELOAD_SEQUENCE', 'Slide preload sequence', 'jaipho_js_slide_preload_sequence', '1,-1,2',
						new Jph_Wp_Admin_Editor_Input(),
						new Jph_Wp_Admin_Validator_OptionIntListDefinition()),
				new Jph_Wp_ScriptConfigurationOption( 
						'SPLASH_SCREEN_DURATION', 'Splash screen duration', 'jaipho_js_splash_screen_duration', 1000,
						new Jph_Wp_Admin_Editor_Input(), 
						new Jph_Wp_Admin_Validator_OptionIntPositiveDefinition()),
				new Jph_Wp_ScriptConfigurationOption( 
						'DEFAULT_STARTUP_MODE', 'Default startup mode', 'jaipho_js_default_startup_mode', 'slider',
						new Jph_Wp_Admin_Editor_Select( $startupp_modes),
						new Jph_Wp_Admin_Validator_OptionListDefinition( $startupp_modes)),
				new Jph_Wp_ScriptConfigurationOption( 
						'SLIDE_SPACE_WIDTH', 'Slide space width', 'jaipho_js_slide_space_width', 40,
						new Jph_Wp_Admin_Editor_Input(), 
						new Jph_Wp_Admin_Validator_OptionIntPositiveDefinition())
			));
		
		
		
		
		
		// advanced parameters
		$this->sections[]	=	new Jph_Wp_ConfigurationSection( 'advanced-parameters', 'Jaipho Javascript - Advanced parameters',
			array(
				new Jph_Wp_ScriptConfigurationOption(
						'ENABLE_SAFARI_HISTORY_PATCH', 'Enable Safari history patch', 'jaipho_js_enable_safari_history_patch', 'true',
						new Jph_Wp_Admin_Editor_Select( $truefalses),
						new Jph_Wp_Admin_Validator_OptionListDefinition( $truefalses)),
				new Jph_Wp_ScriptConfigurationOption(
						'MAX_CONCURENT_LOADING_THUMBNAILS', 'Max concurent thumbnails to load', 'jaipho_js_max_concurent_loading_thumbnails', 4,
						new Jph_Wp_Admin_Editor_Input(),
						new Jph_Wp_Admin_Validator_OptionIntPositiveDefinition()),		
				new Jph_Wp_ScriptConfigurationOption(
						'MAX_CONCURENT_LOADING_SLIDE', 'Max concurent slides to load', 'jaipho_js_max_concurent_loading_slide', 1,
						new Jph_Wp_Admin_Editor_Input()),		
				new Jph_Wp_ScriptConfigurationOption(
						'MIN_DISTANCE_TO_BE_A_DRAG', 'Min distance to be a drag action', 'jaipho_js_min_distance_to_be_a_drag', 70,
						new Jph_Wp_Admin_Editor_Input()),		
				new Jph_Wp_ScriptConfigurationOption(
						'MAX_DISTANCE_TO_BE_A_TOUCH', 'Max distance to be a touch', 'jaipho_js_max_distance_to_be_a_touch', 5,
						new Jph_Wp_Admin_Editor_Input()),		
				new Jph_Wp_ScriptConfigurationOption(
						'CHECK_ORIENTATION_INTERVAL', 'Check Orientation interval', 'jaipho_js_check_orientation_interval', 1000,
						new Jph_Wp_Admin_Editor_Input()),		
				new Jph_Wp_ScriptConfigurationOption(
						'BLOCK_VERTICAL_SCROLL', 'Block vertical scroll', 'jaipho_js_block_vertical_scroll', 'true',
						new Jph_Wp_Admin_Editor_Input()),		
				new Jph_Wp_ScriptConfigurationOption(
						'BASE_URL', 'Base url', 'jaipho_js_base_url', 'jaipho/',
						new Jph_Wp_Admin_Editor_Input()),		
				new Jph_Wp_ScriptConfigurationOption(
						'SLIDE_MAX_IMAGE_ELEMENS', 'Max image elemenst for slider', 'jaipho_js_slide_max_image_elemens', 50,
						new Jph_Wp_Admin_Editor_Input()),		
		));
		
		
		// debug parameters
		$this->sections[]	=	new Jph_Wp_ConfigurationSection( 'debug-parameters', 'Jaipho Javascript - Debug parameters',
			array(
				new Jph_Wp_ScriptConfigurationOption(
						'DEBUG_MODE', 'Debug mode', 'jaipho_js_debug_mode', 'false',
						new Jph_Wp_Admin_Editor_Select( $truefalses),
						new Jph_Wp_Admin_Validator_OptionListDefinition( $truefalses)),
				new Jph_Wp_ScriptConfigurationOption(
						'DEBUG_LEVELS', 'Debug levels', 'jaipho_js_debug_levels', '',
						new Jph_Wp_Admin_Editor_Input()),		
		));


		
	}
	
	
	/**
	 * Enter description here ...
	 * @param string $group
	 * @return Jph_Wp_ConfigurationSection 
	 */
	public function getSection( $group)
	{
		// @var $section Jph_Wp_ConfigurationSection
		
		foreach ($this->sections as $section)
		{
			if ($section->id == $group)
				return $section;
		}
		
		throw new Exception( 'Section ['.$group.'] not found');
	}
	
	/**
	 * Enter description here ...
	 * @param string $optionName
	 * @throws Exception
	 * @return Jph_Wp_ConfigurationSectionOption
	 */
	public function getOption( $optionName)
	{
		// @var $section Jph_Wp_ConfigurationSection
		// @var $option Jph_Wp_ConfigurationSectionOption
		
		foreach ($this->sections as $section)
		{
			foreach ($section->options as $option)
			{
				if ($option->optionName == $optionName)
					return $option;
			}
		}
		
		throw new Exception( 'Group ['.$group.'] not found');
	}
	
	public function getJaiphoOptions()
	{
		// @var $section Jph_Wp_ConfigurationSection
		// @var $option Jph_Wp_ConfigurationSectionOption
		$options	=	array();
		foreach ($this->sections as $section)
		{
			foreach ($section->options as $option)
			{
				if ($option instanceof Jph_Wp_ScriptConfigurationOption)
					$options[]	=	$option;
			}
		}
		
		return $options;
	}
	
	public function html()
	{
		// @var $section Jph_Wp_ConfigurationSection
		
		$str	=	'';
		foreach ($this->sections as $section)
		{
			$str .=	$section->html();
			$str .=	"\n";
		}
		
		return $str;
	}
}

