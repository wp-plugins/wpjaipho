<?php


interface Jph_Wp_ITemplateApi
{
	
	/**
	* Return should gallery be displayed to an iPad user.
	* This method calcualetes in the possible admin wp options too.
	* @return boolean
	*/
	public function isIpad();
	
	
	/**
	* Dumps the Jaipho javascript configuration block. Further info about Jaipho configuration http://www.jaipho.com/content/jaipho-configuration
	* @return string
	*/
	public function getJavascriptConfig();
	
	public function getJavascriptLoad();
	
	public function getSelectedIndex();
	
	public function getLoadingTitle();
	
	
	public function getPageTitle();
	
	public function getPostTitle();
	
	public function getPostPermalink();
}




