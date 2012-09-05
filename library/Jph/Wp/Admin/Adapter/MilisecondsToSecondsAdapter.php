<?php

require_once 'Jph/Wp/Admin/Adapter/IValueAdapter.php';

class Jph_Wp_Admin_Adapter_MilisecondsToSecondsAdapter implements Jph_Wp_Admin_Adapter_IValueAdapter
{
	
	public function getRealValue( $displayValue)
	{
		return $displayValue*1000;
	}
	
	public function getDisplayValue( $realValue)
	{
		return $realValue/1000;
	}
	
}