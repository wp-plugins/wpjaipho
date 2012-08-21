<?php
/******************************************************************************
 *	PIPHO, version 1.01.00
 *	(c) 2009 jaipho.com
 *
 *	PIPHO is freely used under the terms of an FreeBSD license.
 *	For details, see the PIPHO web site: http://www.jaipho.com/pipho/
 ******************************************************************************/

class Pipho_ImageResizer {
   
	public $image;
	public $newImage;
   	public $type;
 	public $filename;
 	
 	public function __construct( $filename)
 	{
 		$this->filename	=	$filename;
 	}
 	
	public function init() 
	{
		$image_info 	= 	getimagesize( $this->filename);
      	if ($image_info === false)
      		throw new Exception('Failed to read image size ['.$this->filename.']');
		
      	$this->type 	= 	$image_info[2];
      	
      	if( $this->type == IMAGETYPE_JPEG) 
      	{
			$this->image	=	imagecreatefromjpeg( $this->filename);
      	} 
      	elseif( $this->type == IMAGETYPE_GIF) 
      	{
        	$this->image	=	imagecreatefromgif( $this->filename);
      	} 
      	elseif( $this->type == IMAGETYPE_PNG) 
      	{
        	$this->image	=	imagecreatefrompng( $this->filename);
      	}
      	
      	if ($this->image === false)
      		throw new Exception('Failed to open image ['.$this->filename.']');
	}
   
	public function save( $filename) 
	{
		if (!$this->newImage)
			throw new Exception('Nothing to save for original image ['.$this->filename.']');
			
		if( $this->type == IMAGETYPE_JPEG) 
		{
			$res	=	imagejpeg( $this->newImage, $filename);
      	} 
      	elseif( $this->type == IMAGETYPE_GIF) 
      	{
			$res	=	imagegif( $this->newImage, $filename);         
      	} 
      	elseif( $this->type == IMAGETYPE_PNG) 
      	{
			$res	=	imagepng( $this->newImage, $filename);
		}
		   
		if (!$res)
			throw new Exception('Failed to save image ['.$filename.']['.$this->type.']');
	}

	public function getWidth() 
	{
		return imagesx( $this->image);
   	}
   	
	public function getHeight() 
	{
		return imagesy( $this->image);
	}

  	public function resizeToMax( $maxSize) 
	{
	    $width_orig 	= 	$this->getWidth(); 
	    $height_orig	= 	$this->getHeight(); 
	    
	    if ($width_orig < $maxSize && $height_orig < $maxSize)
	    {
		   $width 	= 	$width_orig;
		   $height	=	$height_orig;
	    }
	    else
	    {
	    	$ratio_orig 	= 	$width_orig / $height_orig;
			if (1 > $ratio_orig) 
			{
			   $width 	= 	$maxSize * $ratio_orig;
			   $height	=	$maxSize;
			} 
			else 
			{
			   $height 	= 	$maxSize / $ratio_orig;
			   $width	=	$maxSize;
			}
	    }

		// Resample
		$this->newImage 	= 	imagecreatetruecolor( $width, $height);
		$res				=	imagecopyresampled( $this->newImage, $this->image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
		   
		if (!$res)
			throw new Exception('Failed to resample image ['.$this->filename.'] to max size ['.$maxSize.']');
	}
	
	public function cropToSize( $size) 
	{
	    $src_width 	= 	$this->getWidth(); 
	    $src_height	= 	$this->getHeight(); 
        
        if($src_width > $src_height)
        { 
            $src_w	=	$src_height; 
            $src_h	=	$src_height; 
        }
        else
        { 
            $src_w	=	$src_width; 
            $src_h	=	$src_width; 
        } 
        
	    $this->newImage		=	imagecreatetruecolor( $size, $size); 
	    $res				=	imagecopyresampled( $this->newImage, $this->image, 0, 0, 0, 0, $size, $size, $src_w, $src_h);
		if (!$res)
			throw new Exception('Failed to crop image ['.$this->filename.'] to max size ['.$size.']'); 
	}
   
}
?>