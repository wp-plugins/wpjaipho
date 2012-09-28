<?php

require_once 'Jph/Wp/Admin/Validator/IValidator.php';

class Jph_Wp_Admin_Validator_PositiveNumberDefinition implements Jph_Wp_Admin_Validator_IValidator
{
	
	protected $allowZero;
	
	public function __construct( $allowZero=false)
	{
		$this->allowZero	=	$allowZero;	
	}
	
	
	/**
	 * Enter description here ...
	 * @param string $value
	 * @return Jph_Wp_Admin_Validator_ValidatorResponse
	 */
	public function validate( $value)
	{
		$message	=	'Value should be a positive number';
		
		if (!is_numeric( $value))
			$valid = false;
		else if ($this->allowZero && $value == 0)
			$valid = true;
		else
			$valid		=	$value > 0;
		
		return new Jph_Wp_Admin_Validator_ValidatorResponse( $valid, $message);
	}
	
}