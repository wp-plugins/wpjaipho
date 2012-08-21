<?php 

require_once 'Jph/Wp/ConfigurationOption.php';



class Jph_Wp_ScriptConfigurationOption extends Jph_Wp_ConfigurationOption
{
	public $jaiphoName;

	public function __construct( $jaiphoName, $label, $optionName, $default, $editor, $validator=null)
	{
		parent::__construct( $label, $optionName, $default, $editor, $validator);
		$this->jaiphoName	=	$jaiphoName;
	}
	
	/**
	 * Returns the javascript configuration block, latter injected in some <script> tag.
	 * This method should be moved to a different kind of an object.
	 * @return string
	 */
	public function javascript()
	{
		$value		=	$this->getValue();
		$str_value	=	$value;
		
		if (empty( $value))
			$str_value	=	'\'\'';
		else if (strtolower( $value) == 'true' || strtolower( $value) == 'false')
			$str_value	=	$value;
		else if (!is_numeric( $value))
			$str_value	=	'\''.$value.'\'';
		
		
		return 'var '.$this->jaiphoName.'	=	'.$str_value.';';
	}
	
}