<?php

require_once 'Jph/Wp/Admin/Adapter/IValueAdapter.php';

class Jph_Wp_Admin_Adapter_DefaultAdapter implements Jph_Wp_Admin_Adapter_IValueAdapter
{
	
	public function getRealValue( $displayValue)
	{
		return $displayValue;
	}
	
	public function getDisplayValue( $realValue)
	{
		return $realValue;
	}
	
}