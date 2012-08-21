<?php
/******************************************************************************
 *	PIPHO, version 1.01.00
 *	(c) 2009 jaipho.com
 *
 *	PIPHO is freely used under the terms of an FreeBSD license.
 *	For details, see the PIPHO web site: http://www.jaipho.com/pipho/
 ******************************************************************************/

class Pipho_Image
{
	public $index;
	public $urlThumb;
	public $urlSlide;
	public $urlFull;
	public $title;
	public $description;
	
	public function __construct( $filename)
	{
		$this->urlFull		=	$filename;
	}
	
	public function getJsReadImage()
	{
		// dao.ReadImage( 0, 'test-image-1.jpg', 'test-image-1-thumb.jpg', '', '');
		$arguments		=	array();
		$arguments[]	=	$this->index;	
		$arguments[]	=	"'".$this->urlSlide."'";	
		$arguments[]	=	"'".$this->urlThumb."'";	
		$arguments[]	=	"'".addslashes( $this->title)."'";	
		$arguments[]	=	"'".addslashes( $this->description)."'";

		return '
			dao.ReadImage( '.join(',', $arguments).');';
	}
}
?>