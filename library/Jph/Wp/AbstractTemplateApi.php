<?php

require_once 'Pipho/DeviceInfo.php';

require_once 'Jph/Wp/ITemplateApi.php';

abstract class Jph_Wp_AbstractTemplateApi implements Jph_Wp_ITemplateApi
{
	
	/**
	 * Enter description here ...
	 * @var Jph_Wp_JaiphoPlugin
	 */
	public $plugin;
	
	protected $javascriptLoad	=	'';
	protected $selectedIndex	=	0;
	
	public function __construct( Jph_Wp_JaiphoPlugin $plugin)
	{
		$this->plugin	=	$plugin;
	}
	
	
	public function init()
	{
		$theme			=	$this->plugin->getOptionValue( 'jaipho_skin');
		$theme_folder	=	plugins_url( 'wpjaipho/jaipho/Themes/'.$theme);
	
 		$theme_folder 	= 	apply_filters('jaipho_theme_folder_filter', $theme_folder);
		
		// JS & CSS
		wp_enqueue_script( 'jaipho-preload', plugins_url( 'wpjaipho/jaipho/jaipho-0.60.01.js'));
		wp_enqueue_style( 'jaipho-default', $theme_folder.'/jaipho.css');
		
	}
	
	
	public function getPageTitle()
	{
		return $this->getPostTitle();
	}
	
	protected function getSlideDesciption( $description)
	{
		if ($this->plugin->getOptionBoolValue( 'jaipho_disable_slide_title'))
			return '';
			
		return $this->sanitizeJavascriptString( $description);
	}
	
	protected function getSlideTitle( $title)
	{
		if ($this->plugin->getOptionBoolValue( 'jaipho_disable_slide_description'))
			return '';
		
		return $this->sanitizeJavascriptString( $title);
	}
	
	protected function sanitizeJavascriptString( $text)
	{
		$text	=	addslashes( $text);
		$text	=	nl2br( $text);
		$text	=	str_replace( "\n", "", $text);
		$text	=	str_replace( "\r", "", $text);
		
		return $text;
	}
	
	
	
	
	/**
	* Dumps the Jaipho javascript configuration block. Further info about Jaipho configuration http://www.jaipho.com/content/jaipho-configuration
	* @return string
	*/
	public function getJavascriptConfig()
	{
		$str		=	'';
		$options	=	$this->plugin->configuration->getJaiphoOptions();
	
		foreach ($options as $option)
		{
			$str 	.=	$option->javascript();
			$str 	.=	"\n";
		}
	
		return $str;
	}
	
	public function getJavascriptLoad()
	{
		return $this->javascriptLoad;
	}
	
	public function getSelectedIndex()
	{
		return $this->selectedIndex;
	}
	
	
	public function getLoadingTitle()
	{
		return $this->plugin->getOptionValue( 'jaipho_loading_title');
	}
	
	public function getSplashscreenHtml()
	{
		return stripslashes( $this->plugin->getOptionValue( 'jaipho_splashscreen_html'));
	}
	
	public function getThumbnailsHtml()
	{
		return stripslashes( $this->plugin->getOptionValue( 'jaipho_thumbnails_html'));
	}
	
	public function getLoadingImage()
	{
		return $this->plugin->getOptionValue( 'jaipho_loading_title');
	}
	
	
	public function getGalleryTitle()
	{
		return $this->getPostTitle();
	}
	
	protected function getPostTitle()
	{
		global $post;
		return get_the_title( $post->post_parent );
	}
	
	protected function getPostPermalink()
	{
		global $post;
		return get_permalink( $post->post_parent );
	}
	
	// SLIDER BACK BUTTON
	public function getSliderBackTitle()
	{
		if ($this->plugin->getOptionBoolValue('jaipho_enable_thumbnails'))
			return $this->plugin->getOptionValue( 'jaipho_thumbnails_button_text');
		return $this->getPostTitle();
	}
	
	public function getSliderBackLink()
	{
		if ($this->plugin->getOptionBoolValue('jaipho_enable_thumbnails'))
			return 'javascript: jaipho.ShowThumbsAction();';
		return $this->getPostPermalink();
	}
	
	// THUMBNAILS BACK BUTTON
	public function getThumbsBackTitle()
	{
		return $this->getPostTitle();
	}
	
	public function getThumbsBackLink()
	{
		return $this->getPostPermalink();
	}
}




