<?php

require_once 'Jph/Wp/Admin/Validator/ValidatorResponse.php';

interface Jph_Wp_Admin_Validator_IValidator
{
	/**
	* Validates the given input annd returns response containing error state (boolean) and error message.
	* 
	* @param string $value
	* @return Jph_Wp_Admin_Validator_ValidatorResponse
	*/
	public function validate( $value);
}