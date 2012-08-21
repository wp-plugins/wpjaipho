<?php

require_once 'Jph/Wp/Admin/Validator/IValidator.php';

class Jph_Wp_Admin_Validator_OptionListDefinition implements Jph_Wp_Admin_Validator_IValidator
{
	
	/**
	 * Example value: array ( 'thumbs', 'slider', 'slideshpw')
	 * 
	 * @param string $value
	 * @return Jph_Wp_Admin_Validator_ValidatorResponse
	 */
	public function validate( $value)
	{
		// TODO: impement the functionality
		return new Jph_Wp_Admin_Validator_ValidatorResponse( true);
	}
	
}