<?php

class Jph_Wp_Admin_Validator_ValidatorResponse
{
	const DEFAULT_ERROR_MESSAGE	=	'Please enter correct value';
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
		if (is_null($this->message))
			return self::DEFAULT_ERROR_MESSAGE;
		return $this->message;
	}
}