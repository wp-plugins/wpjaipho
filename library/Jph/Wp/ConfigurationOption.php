<?php 

class Jph_Wp_ConfigurationOption
{
	public $label, $optionName, $default;
	
	/**
	 * Enter description here ...
	 * @var Jph_Wp_Admin_Validator_IValidator
	 */
	public $validator;
	
	/**
	 * Enter description here ...
	 * @var Jph_Wp_Admin_Validator_IEditor
	 */
	public $editor;
	
	public function __construct( $label, $optionName, $default, $editor, $validator=null)
	{
		$this->label		=	$label;
		$this->optionName	=	$optionName;
		$this->default		=	$default;
		$this->validator	=	$validator;
		$this->editor		=	$editor;
	}
	
	
	public function getValue()
	{
		return get_option( $this->optionName, $this->default);
	}
	
	
}