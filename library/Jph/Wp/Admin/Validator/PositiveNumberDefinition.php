<?php

require_once 'Jph/Wp/Admin/Validator/IValidator.php';

class Jph_Wp_Admin_Validator_PositiveNumberDefinition implements Jph_Wp_Admin_Validator_IValidator
{
	
	/**
	 * Enter description here ...
	 * @param string $value
	 * @return Jph_Wp_Admin_Validator_ValidatorResponse
	 */
	public function validate( $value)
	{
		$message	=	'Value should be a positive number';
		
		$valid		=	is_numeric( $value) && $value > 0;
		
		return new Jph_Wp_Admin_Validator_ValidatorResponse( $valid, $message);
	}
	
}