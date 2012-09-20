<?php

require_once 'Jph/Wp/Admin/Validator/IValidator.php';

class Jph_Wp_Admin_Validator_OptionListDefinition implements Jph_Wp_Admin_Validator_IValidator
{
	private $options	=	array();
	
	public function __construct( $options)
	{
		$this->options	=	$options;
	}
	
	/**
	 * Example value: array ( 'thumbs', 'slider', 'slideshpw')
	 * 
	 * @param string $value
	 * @return Jph_Wp_Admin_Validator_ValidatorResponse
	 */
	public function validate( $value)
	{
		$ret	=	false;
		if (in_array( $value, $this->options))
			$ret	=	true;
		return new Jph_Wp_Admin_Validator_ValidatorResponse( $ret);
	}
	
}