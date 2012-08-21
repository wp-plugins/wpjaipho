<?php

class Jph_Wp_Admin_Validator_ValidatorResponse
{
	private $valid, $message;
	
	public function __construct( $valid, $message=null)
	{
		$this->valid	=	$valid;
		$this->message	=	$message;
	}
	
	public function isValid()
	{
		return $this->valid;
	}
	
	public function getMessage()
	{
		return $this->message;
	}
}