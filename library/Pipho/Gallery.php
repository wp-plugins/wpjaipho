<?php
/******************************************************************************
*	PIPHO, version 1.03.00
*	(c) 2012 jaipho.com
*
*	PIPHO is freely used under the terms of an FreeBSD license.
*	For details, see the PIPHO web site: http://www.jaipho.com/pipho/
******************************************************************************/

class Pipho_Gallery
{
	public $id;
	public $title;
	public $description;
	
	/**
	 * Enter description here ...
	 * @var boolean
	 */
	public $container;
	
	/**
	 * Optional preloaded image.
	 *
	 * @var Pipho_Image
	 */
	public $firstImage;
}
?>