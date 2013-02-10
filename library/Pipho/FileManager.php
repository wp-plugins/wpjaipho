<?php
/******************************************************************************
 *	PIPHO, version 1.01.00
 *	(c) 2009 jaipho.com
 *
 *	PIPHO is freely used under the terms of an FreeBSD license.
 *	For details, see the PIPHO web site: http://www.jaipho.com/pipho/
 ******************************************************************************/

require_once 'Pipho/Image.php';
require_once 'Pipho/Gallery.php';
require_once 'Pipho/ImageResizer.php';
require_once 'Pipho/ItemNotExistsException.php';

class Pipho_FileManager
{
	const GALLERY_INFO_FILENAME	=	'_gallery.txt';
	const SLIDE_MARK			=	'-slide-';
	const THUMB_MARK			=	'-thumb-';
	
	/**
	 * Holds values about optimal thumbnails and slide sizes
	 *
	 * @var Pipho_DeviceInfo
	 */
	public $deviceInfo;
		
	/**
	 * Path to source photos folder
	 *
	 * @var string
	 */
	public $photosDir;
	
		
	/**
	 * Path to destination photos folder
	 *
	 * @var string
	 */
	public $photosDest;
	
	/**
	 * If true, causes that thumb and slide images are allways rewriten. 
	 * Otherwise, thumbs and slides are generated only if they are not existing. 
	 *
	 * @var boolean
	 */
	public $forceOverWrite;

	
	public $skipList	=	array( 'entries','repository','cvs','root','.cvsignore', );
	
	/**
	 * Enter description here...
	 *
	 * @param Pipho_DeviceInfo $deviceInfo
	 * @param string $photosDir
	 * @param boolean $forceOverWrite
	 * @param string $photosDest
	 */
	public function __construct( $deviceInfo, $photosDir, $forceOverWrite, $photosDest=null)
	{
		$this->deviceInfo		=	$deviceInfo;
		$this->photosDir		=	$photosDir;
		$this->photosDest		=	$photosDest ? $photosDest : $photosDir;
		$this->forceOverWrite	=	$forceOverWrite;
	}
	
	public function init()
	{
		if (!is_dir($this->photosDir))
			throw new Exception('Can not find photos source folder ['.$this->photosDir.']. Create folder'. 
				' which will be named as defined in PIPHO_PHOTOS_FOLDER constant');
	}
	
	/**
	 * Enter description here...
	 *
	 * @param bool $loadFirstImage should gallery objects have preloaded first image (usable when listing galleries) 
	 * @return array Pipho_Gallery
	 */
	public function getGalleries( $loadFirstImage=false)
	{
		$folders		=	array();
		$handle			=	opendir( $this->photosDir);
	    while (false !== ($file = readdir($handle))) 
	    {
	        if ($file != "." && $file != "..") 
	        {
	        	$folder_path	=	$this->photosDir.'/'.$file;

	        	if (!is_dir( $folder_path))
					continue;
				if (!$this->_isValidFile( $file))
					continue;
					
				$folders[]	=	$file;
	        }
    	}
    	closedir( $handle);		
    	
    	if (PIPHO_SORT_GALLERIES_ENABLED)
    	{
    		if (PIPHO_SORT_GALLERIES_REVERSE)
    			rsort( $folders, PIPHO_SORT_GALLERIES_SORT_TYPE);
    		else
    			sort( $folders, PIPHO_SORT_GALLERIES_SORT_TYPE);	
    	}
    	
    	$galleries		=	array();
    	foreach ($folders as $folder_name)
    	{
    		$gallery = $this->getGallery( $folder_name);
    		if ($loadFirstImage)
    			$this->_loadFirstImage( $gallery);

    		$galleries[] = $gallery;
    	}
		
		return $galleries;
	}
	
	/**
	 * Enter description here...
	 *
	 * @param string $galleryId
	 * @return Pipho_Gallery
	 */
	public function getGallery( $galleryId)
	{
		$folder_path			=	$this->_getGalleryFolder( $galleryId);
		$info_path				=	$folder_path.'/'.self::GALLERY_INFO_FILENAME;
		
		$info					=	$this->_readItemDescription( $info_path);
		$gallery 				=	new Pipho_Gallery();
		$gallery->id			=	$galleryId;
		$gallery->title			=	$info[0] ? $info[0] : $galleryId;
		$gallery->description	=	$info[1];
		
		return $gallery;
	}

	public function _loadFirstImage( Pipho_Gallery $gallery)
	{
		$folder_path	=	$this->_getGalleryFolder( $gallery->id);
		$resized_path	=	$this->_getResizedFolder( $gallery->id);
		
		$files			=	$this->_getGalleryImagesList( $gallery->id);
		
		if( count($files) == 0)
			throw new Pipho_ItemNotExistsException( 'Could not load first image. Gallery ['.$gallery->id.'] has no images.');
		
		foreach ($files as $file)
		{
			$gallery->firstImage	=	$this->_createImage( 0, $file, $folder_path, $resized_path);
			return;
		}
	}

	public function getGalleryImages( $galleryId)
	{
		$images			=	array();
		$folder_path	=	$this->_getGalleryFolder( $galleryId);
		$resized_path	=	$this->_getResizedFolder( $galleryId);
		$files			=	$this->_getGalleryImagesList( $galleryId);
		
	    foreach ($files as $file)
	    {
			$images[]	=	$this->_createImage( count($images), $file, $folder_path, $resized_path);
	    }
		return $images;
	} 
	
	/**
	 * Enter description here...
	 *
	 * @param int $index
	 * @param string $file
	 * @param string $folderPath
	 * @param string $resizedPath
	 * @return Pipho_Image
	 */
	private function _createImage( $index, $file, $folderPath, $resizedPath)
	{
		$image_path			=	$folderPath.'/'.$file;
		
		$image_info			=	pathinfo( $image_path);
		
        //for php < 5.2 by arsen
        if(!isset($image_info['filename']))
        {
        	$image_info['filename'] = substr($image_info['basename'], 0, strrpos($image_info['basename'], '.'));
		}
               
		$text_path			=	$folderPath.'/'.@$image_info['filename'].'.txt';
		
		$image 				=	new Pipho_Image( $image_path);
		$image->index		=	$index;
		
		$image->urlThumb	=	$this->_getThumbnailImage( $image_path, $resizedPath, @$image_info['filename'], @$image_info['extension']);
		$image->urlSlide	=	$this->_getSlideImage( $image_path, $resizedPath, @$image_info['filename'], @$image_info['extension']);
		
		$description		=	$this->_readItemDescription( $text_path);
		
		$image->title		=	$description[0];
		$image->description	=	$description[1];
		
		return $image;
	}

	private function _getGalleryImagesList( $galleryId)
	{
		$images			=	array();
		$folder_path	=	$this->_getGalleryFolder( $galleryId);
		$handle			=	opendir( $folder_path);
		
	    while (false !== ($file = readdir($handle))) 
	    {	
	        if ($file != "." && $file != "..") 
	        {
				$image_path			=	$folder_path.'/'.$file;
				
				if (is_dir( $image_path))
					continue;
				if (stripos( $file, '.txt') !== false)
					continue;
				if ($file == "." || $file == "..") 
	    			continue;
				if (!$this->_isValidFile( $file))
	    			continue;
				$images[]	=	$file;
	        }
    	}
    	closedir( $handle);		
		
	    if (PIPHO_SORT_IMAGES_ENABLED)
    	{
    		if (PIPHO_SORT_IMAGES_REVERSE)
    			rsort( $images, PIPHO_SORT_IMAGES_SORT_TYPE);
    		else
    			sort( $images, PIPHO_SORT_IMAGES_SORT_TYPE);	
    	}
    	
		return $images;
	} 
	
	protected function _isValidFile( $filename)
	{
		if (in_array( strtolower( $filename), $this->skipList))
			return false;
			
		return true;
	}
	
	/**
	 * Enter description here...
	 *
	 * @param string $galleryId
	 * @param int $index
	 * @return Pipho_Image
	 */
	public function getImageByIndex( $galleryId, $index)
	{
		$images		=	$this->getGalleryImages( $galleryId);
		if (!isset($images[$index]))
			throw new Exception('Image with index ['.$index.'] not defined in gallery ['.$galleryId.']');
			
		return $images[$index];
	}
	
	// UTIL
	protected function _readItemDescription( $file)
	{
		$title			=	'';
		$description	=	'';
		
		if (file_exists( $file))
		{
			$lines 			= 	file( $file, FILE_IGNORE_NEW_LINES);

			foreach ($lines as $line_num => $line) 
			{
				if ($line_num == 0)
					$title			=	rtrim( $line);
				else
					$description	.=	rtrim( nl2br( $line));
			}
		}
		return array( $title, $description);	
	}
	
	protected function _getThumbnailImage( $imagePath, $folder, $filename, $extension)
	{
		$thumb_path		=	$folder.'/'.$filename.self::THUMB_MARK.$this->deviceInfo->thumbsSize.'.'.$extension;
		
		if (!file_exists( $thumb_path) || $this->forceOverWrite)
		{
			$image = new Pipho_ImageResizer( $imagePath);
			$image->init();
			$image->cropToSize( $this->deviceInfo->thumbsSize);
			$image->save( $thumb_path);
		}
		return $thumb_path;		
	}
	
	protected function _getSlideImage( $imagePath, $folder, $filename, $extension)
	{
		if (!PIPHO_SLIDE_RESIZE_ENABLED)
			return $imagePath;
		
		$slide_path		=	$folder.'/'.$filename.self::SLIDE_MARK.$this->deviceInfo->slideMaxSize.'.'.$extension;
		
		if (!file_exists( $slide_path) || $this->forceOverWrite)
		{
			$image = new Pipho_ImageResizer( $imagePath);
			$image->init();
			$image->resizeToMax( $this->deviceInfo->slideMaxSize);
			$image->save( $slide_path);
		}
		return $slide_path;	
	}
	
	protected function _getGalleryFolder( $galleryId)
	{
		$folder_path		=	$this->photosDir.'/'.$galleryId;
		
		if (!is_dir( $folder_path))
			throw new Exception(
				'Requested source gllery folder ['.$folder_path.'] for gallery_id ['.$galleryId.'] does not exists. 
				Your galleries should be organized in folders, and all together placed inside photos main folder
				which is defined in PIPHO_PHOTOS_FOLDER constant.' 
			);
			
		return $folder_path;
	} 	

	protected function _getResizedFolder( $galleryId)
	{
		if ($this->photosDir == $this->photosDest)
			$gallery_path		=	$this->photosDest.'/'.$galleryId.'/resized';
		else
			$gallery_path		=	$this->photosDest.'/'.$galleryId;
		
		if (!is_dir( $gallery_path))
		{
			if (false === mkdir( $gallery_path, 0777, true))
				throw new Exception(
					'Folder ['.$gallery_path.'] could not be created. If you are using Linux, make sure that your 
					gallery folders are writable by PHP by setting ownership (chown) to appache, or setting write permision (chmod) to all'
				);
		}
		
		return $gallery_path;
	} 	
}
?>