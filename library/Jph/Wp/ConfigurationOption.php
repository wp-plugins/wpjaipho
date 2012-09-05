<?php 

require_once 'Jph/Wp/Admin/Adapter/DefaultAdapter.php';

class Jph_Wp_ConfigurationOption
{
	public $label, $optionName, $default, $description;
	
	public $adapter;
	
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
	
	public function __construct( $label, $optionName, $default, $description, $editor, $validator=null, $adapter=null)
	{
		$this->label		=	$label;
		$this->optionName	=	$optionName;
		$this->default		=	$default;
		$this->description	=	$description;
		$this->validator	=	$validator;
		$this->editor		=	$editor;
		$this->adapter		=	$adapter == null ? new Jph_Wp_Admin_Adapter_DefaultAdapter() : $adapter;
	}
	
	
	/**
	 * Returns the wordpress option value. This method consideres the default values.
	 * @return string
	 */
	public function getValue()
	{
		return get_option( $this->optionName, $this->default);
	}
	
	
}