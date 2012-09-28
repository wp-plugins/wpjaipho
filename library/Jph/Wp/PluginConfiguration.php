<?php

require_once 'Jph/Wp/ConfigurationSection.php';
require_once 'Jph/Wp/ConfigurationOption.php';
require_once 'Jph/Wp/JaiphoConfigurationOption.php';

require_once 'Jph/Wp/Admin/Adapter/MilisecondsToSecondsAdapter.php';

require_once 'Jph/Wp/Admin/Editor/Input.php';
require_once 'Jph/Wp/Admin/Editor/Select.php';
require_once 'Jph/Wp/Admin/Editor/Checkbox.php';

require_once 'Jph/Wp/Admin/Validator/OptionCssTimeDefinition.php';
require_once 'Jph/Wp/Admin/Validator/OptionIntListDefinition.php';
require_once 'Jph/Wp/Admin/Validator/OptionIntPositiveDefinition.php';
require_once 'Jph/Wp/Admin/Validator/OptionListDefinition.php';
require_once 'Jph/Wp/Admin/Validator/PositiveNumberDefinition.php';


/**
 * Contains plugin configuration. Here is definition of all posible configuration options, their default values, 
 * admin represention and behavior.
 * TODO: We should have only option names and default values. Editrs, validators and etc should be defined somwhere in admin section (package)
 * 
 * @author tole
 *
 */
class Jph_Wp_PluginConfiguration
{
	const GROUP_BASIC		=	'basic';
	const GROUP_ADVANCED	=	'advanced';
	const GROUP_DEBUG		=	'debug';
	
	public $sections		=	array();
	
	
	public function init()
	{
		// standard sources for selectboxes
		$startup_modes	=	array ( 'thumbs', 'slider', 'slideshow');
		$truefalses		=	array ( 'true', 'false');
		$skins			=	array ( 'Default', 'iUI');	// TODO: read available skins from disk
		
		
		// basic options
		$this->sections[]	=	new Jph_Wp_ConfigurationSection( 'basic-options', 'Basic options',
			array(
			
				new Jph_Wp_ConfigurationOption( 'Splashscreen loading text', 'jaipho_loading_title', 'JAIPHO',
						'Sets the text displayed when gallery is in loading phase',
						new Jph_Wp_Admin_Editor_Input()),
						
				new Jph_Wp_JaiphoConfigurationOption( 'SPLASH_SCREEN_DURATION', 'Splash screen duration (seconds)', 'jaipho_js_splash_screen_duration', 1000,
						'Minimum time that the splashcreen will be displayed',						
						new Jph_Wp_Admin_Editor_Input(),
						new Jph_Wp_Admin_Validator_PositiveNumberDefinition( true),
						new Jph_Wp_Admin_Adapter_MilisecondsToSecondsAdapter()),
				
				new Jph_Wp_JaiphoConfigurationOption( 
						'TOOLBARS_HIDE_TIMEOUT', 'Toolbars hide timout (seconds):', 'jaipho_js_toolbars_hide_timeout', 5000,
						'In Fullscreen mode, toolbar will autohide after specified time',
						new Jph_Wp_Admin_Editor_Input(),
						new Jph_Wp_Admin_Validator_PositiveNumberDefinition(),
						new Jph_Wp_Admin_Adapter_MilisecondsToSecondsAdapter()),
						
				new Jph_Wp_JaiphoConfigurationOption( 
						'SLIDESHOW_ROLL_TIMEOUT', 'Slideshow roll timout (seconds):', 'jaipho_js_slideshow_roll_timeout', 4000,
						'How much time to elapse for slide to change in Slide Show mode',						
						new Jph_Wp_Admin_Editor_Input(),
						new Jph_Wp_Admin_Validator_PositiveNumberDefinition(),
						new Jph_Wp_Admin_Adapter_MilisecondsToSecondsAdapter()),		
										
				new Jph_Wp_ConfigurationOption( 'Jaipho theme (skin)', 'jaipho_skin', 'Default',
						'Selects the Jaipho theme (skin)',
						new Jph_Wp_Admin_Editor_Select( $skins),
						new Jph_Wp_Admin_Validator_OptionListDefinition( $skins)),
						
				// TODO: implement mechanizam to forbid the thumbnails startup when the thumbnails mode is turned off (jaipho_enable_thumbnails).
				new Jph_Wp_JaiphoConfigurationOption( 'DEFAULT_STARTUP_MODE', 'Default startup mode', 'jaipho_js_default_startup_mode', 'slider',
						'Set one of values [thumbs|slider|slideshow] to set the startup mode when there is no explicit instruction in url (e.g. #thumbs).'.
						' Do not use [thumbs] value when the "Enable thumbnails view" is set to false',						
						new Jph_Wp_Admin_Editor_Select( $startup_modes),
						new Jph_Wp_Admin_Validator_OptionListDefinition( $startup_modes)),
						
				new Jph_Wp_ConfigurationOption( 'Enable thumbnails view', 'jaipho_enable_thumbnails', 0,
						'Should the Jaipho thumbnails view be enabled.',
						new Jph_Wp_Admin_Editor_Checkbox( '1')),
				
				new Jph_Wp_ConfigurationOption( 'Backo to thumbnails button text', 'jaipho_thumbnails_button_text', 'Thumbnails',
						'Considred only when "Enable thumbnails view" is on. It sets the text on button in top toolbar which leads to thumbnails view',
						new Jph_Wp_Admin_Editor_Input()),
				
				new Jph_Wp_ConfigurationOption( 'Disable iPad support', 'jaipho_disable_ipad', 0,
						'Disables the WPJaipho for iPad users.',
						new Jph_Wp_Admin_Editor_Checkbox( '1')),
		
				new Jph_Wp_ConfigurationOption( 'Disable NextGEN Gallery support', 'jaipho_disable_ngg', 0,
						'Disables the WPJaipho when NextGEN gallery should be displayed',
						new Jph_Wp_Admin_Editor_Checkbox( '1')),
						
				new Jph_Wp_ConfigurationOption( 'Disable slide title', 'jaipho_disable_slide_title', 0,
						'Check this option not to show image titles',
						new Jph_Wp_Admin_Editor_Checkbox( '1')),
						
				new Jph_Wp_ConfigurationOption( 'Disable slide description', 'jaipho_disable_slide_description', 0,
						'Check this option not to show image descriptions',
						new Jph_Wp_Admin_Editor_Checkbox( '1')),

			));
		
		
		// CONFIGURATION BLOCK
		// Jaipho v 0.55
		
		
		// basic jaipho parameters
		$this->sections[]	=	new Jph_Wp_ConfigurationSection( 'advanced-options', 'Advanced options', 
			array(
				new Jph_Wp_JaiphoConfigurationOption( 
						'SLIDE_SCROLL_DURATION', 'Slide scroll duration', 'jaipho_js_slide_scroll_duration', '0.4s',
						'Because of Sliding effect, it takes some time for slide to show in completely on screen. Setting it to "0s" it will actually turn off the sliding effect',
						new Jph_Wp_Admin_Editor_Input(),
						new Jph_Wp_Admin_Validator_OptionCssTimeDefinition()),
						
				new Jph_Wp_JaiphoConfigurationOption( 
						'SLIDE_PRELOAD_TIMEOUT', 'Slide preload timeout (ms)', 'jaipho_js_slide_preload_timeout', 1100,
						'Safari on iPhone seems to have performance problem with WebKit animations while there is loading processes in background, so always keep this value slightly bigger than Slide scroll duration',						
						new Jph_Wp_Admin_Editor_Input(), 
						new Jph_Wp_Admin_Validator_OptionIntPositiveDefinition()),
						
				new Jph_Wp_JaiphoConfigurationOption( 
						'SLIDE_PRELOAD_SEQUENCE', 'Slide preload sequence', 'jaipho_js_slide_preload_sequence', '1,-1,2',
						'Determines how much and in which order will other slides be preloaded. Values are relative, assuming that the current slide is at index 0',
						new Jph_Wp_Admin_Editor_Input(),
						new Jph_Wp_Admin_Validator_OptionIntListDefinition()),
						
				new Jph_Wp_JaiphoConfigurationOption( 
						'SLIDE_SPACE_WIDTH', 'Slide space width (px)', 'jaipho_js_slide_space_width', 40,
						'Sets the space between the slides. By default it is set at 40 (pixels). If set at 0, there will be no space between',						
						new Jph_Wp_Admin_Editor_Input(), 
						new Jph_Wp_Admin_Validator_OptionIntPositiveDefinition()),		
								
				new Jph_Wp_JaiphoConfigurationOption(
						'ENABLE_SAFARI_HISTORY_PATCH', 'Enable Safari history patch', 'jaipho_js_enable_safari_history_patch', 'true',
						'It turns off history management. When set to true (default), pressing Back on browser returns user to referring page where he came from. One feature is lost when this value is enabled: users can not send or add as favorite, direct link to particular slide.',						
						new Jph_Wp_Admin_Editor_Select( $truefalses),
						new Jph_Wp_Admin_Validator_OptionListDefinition( $truefalses)),
						
				new Jph_Wp_JaiphoConfigurationOption(
						'MAX_CONCURENT_LOADING_THUMBNAILS', 'Max concurent thumbnails to load', 'jaipho_js_max_concurent_loading_thumbnails', 4,
						'Only noticeable if you have galleries with lot of pictures in it. It can improve user experience especially on slower mobile connections.',						
						new Jph_Wp_Admin_Editor_Input(),
						new Jph_Wp_Admin_Validator_OptionIntPositiveDefinition()),
						
				new Jph_Wp_JaiphoConfigurationOption(
						'MAX_CONCURENT_LOADING_SLIDE', 'Max concurent slides to load', 'jaipho_js_max_concurent_loading_slide', 1,
						'Because of nature of Full screen mode, this should be keep at 1',
						new Jph_Wp_Admin_Editor_Input(),
						new Jph_Wp_Admin_Validator_OptionIntPositiveDefinition()),
						
				new Jph_Wp_JaiphoConfigurationOption(
						'MIN_DISTANCE_TO_BE_A_DRAG', 'Min distance to be a drag action (px)', 'jaipho_js_min_distance_to_be_a_drag', 70,
						'How many pixels user should move finger on screen to be interpreted as dragging. Used in Full screen mode when detecting user next and previous commands done by finger slide.',
						new Jph_Wp_Admin_Editor_Input(),
						new Jph_Wp_Admin_Validator_OptionIntPositiveDefinition()),
						
				new Jph_Wp_JaiphoConfigurationOption(
						'MAX_DISTANCE_TO_BE_A_TOUCH', 'Max distance to be a touch (px)', 'jaipho_js_max_distance_to_be_a_touch', 5,
						'This value is used when the touching is actually "click" on something.',						
						new Jph_Wp_Admin_Editor_Input(),
						new Jph_Wp_Admin_Validator_OptionIntPositiveDefinition()),
						
				new Jph_Wp_JaiphoConfigurationOption(
						'CHECK_ORIENTATION_INTERVAL', 'Check Orientation interval (ms)', 'jaipho_js_check_orientation_interval', 1000,
						'As there is no event in Safari which will trigger that orientation is changed, this is interval to make that check.',						
						new Jph_Wp_Admin_Editor_Input(),
						new Jph_Wp_Admin_Validator_OptionIntPositiveDefinition()),
						
				new Jph_Wp_JaiphoConfigurationOption(
						'BLOCK_VERTICAL_SCROLL', 'Block vertical scroll', 'jaipho_js_block_vertical_scroll', 'true',
						'It prevents not intended vertical screen scroll which would get Slide image out of focus.',						
						new Jph_Wp_Admin_Editor_Select( $truefalses),
						new Jph_Wp_Admin_Validator_OptionListDefinition( $truefalses)),
						
				new Jph_Wp_JaiphoConfigurationOption(
						'BASE_URL', 'Base url', 'jaipho_js_base_url', 'jaipho/',
						'Used to load 1x1 dummy.gif, which is used by Javascript rendering engine.',						
						new Jph_Wp_Admin_Editor_Input()),
						
				new Jph_Wp_JaiphoConfigurationOption(
						'SLIDE_MAX_IMAGE_ELEMENS', 'Max image elemenst for slider', 'jaipho_js_slide_max_image_elemens', 50,
						'Determines how max slides can be preloaded in memory. By default is set to 50.',						
						new Jph_Wp_Admin_Editor_Input(),
						new Jph_Wp_Admin_Validator_OptionIntPositiveDefinition()),
						
				new Jph_Wp_JaiphoConfigurationOption(
						'DEBUG_MODE', 'Debug mode', 'jaipho_js_debug_mode', 'false',
						'Is application in debug mode. In Firefox with Firebug enabled, it will use Firebug console. On all others, including iPhone, console div will be shown over the content.',						
						new Jph_Wp_Admin_Editor_Select( $truefalses),
						new Jph_Wp_Admin_Validator_OptionListDefinition( $truefalses)),
						
				new Jph_Wp_JaiphoConfigurationOption(
						'DEBUG_LEVELS', 'Debug levels', 'jaipho_js_debug_levels', '',
						'To much debug output will not be usable at all, so here you can set list of modules which are enabled for debug.',						
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
	 * 
	 * 
	 * TODO: optimize this method
	 * 
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
				if ($option instanceof Jph_Wp_JaiphoConfigurationOption)
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

