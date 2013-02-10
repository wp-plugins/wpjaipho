<?php

interface Jph_Wp_Admin_Adapter_IValueAdapter
{
	
	public function getRealValue( $displayValue);
	
	public function getDisplayValue( $realValue);
}