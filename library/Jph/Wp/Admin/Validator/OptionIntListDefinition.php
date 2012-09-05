<?php

require_once 'Jph/Wp/Admin/Validator/IValidator.php';

class Jph_Wp_Admin_Validator_OptionIntListDefinition implements Jph_Wp_Admin_Validator_IValidator
{
	
	/**
	 * Example value: '1,-1,2'
	 * 
	 * @param string $value
	 * @return Jph_Wp_Admin_Validator_ValidatorResponse
	 */
	public function validate( $value)
	{
		$values	=	split( ',', $value);
		$ret	=	true;
		foreach ($values as $item)
		{
			if (!is_numeric($item) || ((float)$item != $item))
				$ret	=	false;
		}
		
		return new Jph_Wp_Admin_Validator_ValidatorResponse( $ret, 'Use int values separated with a comma');
	}
	
}