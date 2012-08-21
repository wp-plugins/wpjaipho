<?php 

class Jph_Wp_ConfigurationSection
{
	public $id;
	public $title;
	public $options	=	array();
	
	public function __construct( $id, $title, $options)
	{
		$this->id		=	$id;
		$this->title	=	$title;
		$this->options	=	$options;
	}
	
	
}