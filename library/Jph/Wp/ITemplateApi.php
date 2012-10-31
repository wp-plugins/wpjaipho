<?php


interface Jph_Wp_ITemplateApi
{
	
	
	/**
	* Dumps the Jaipho javascript configuration block. Further info about Jaipho configuration http://www.jaipho.com/content/jaipho-configuration
	* @return string
	*/
	public function getJavascriptConfig();
	
	public function getJavascriptLoad();
	
	public function getSelectedIndex();
	
	public function getLoadingTitle();
	
	public function getLoadingImage();
	
	public function getSplashscreenHtml();
	
	public function getThumbnailsHtml();
	
	/**
	 * Used for generating page title (<head><title>...)
	* @return string
	*/
	public function getPageTitle();
	
	public function getGalleryTitle();
	
	
	public function getSliderBackTitle();
	
	public function getSliderBackLink();
	
	public function getThumbsBackTitle();
	
	public function getThumbsBackLink();
}




