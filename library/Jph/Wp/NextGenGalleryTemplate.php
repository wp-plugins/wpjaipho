<?php

require_once 'Jph/Wp/AbstractTemplateApi.php';

class Jph_Wp_NextGenGalleryTemplate extends Jph_Wp_AbstractTemplateApi
{
	protected $galleryTitle;
	
	protected $galleryAttributes	=	array();
	
	
	
	public function __construct( Jph_Wp_JaiphoPlugin $plugin)
	{
		parent::__construct( $plugin);
	}
	
	public function nggGalleryShortcode( $attr)
	{
		$this->galleryAttributes	=	$attr;
	}
	
	public function init()
	{
		parent::init();
		
		if ( have_posts() )
		{ 
			remove_shortcode( 'nggallery');
			add_shortcode( 'nggallery', array( $this, 'nggGalleryShortcode'));
			
			the_post();
			
			// copy&paste from the_content()
			$content = get_the_content($more_link_text, $stripteaser);
			$content = apply_filters('the_content', $content);
			$content = str_replace(']]>', ']]&gt;', $content);
		}
		else
		{
			throw new Exception('No post found?');
		}

	    global $wpdb;
	    $pid     		= 	get_query_var('pid');
	    $gallery_id     = 	$this->_getGalleryIdFromPicture( $pid);
	    
	    $ngg_options 	= 	nggGallery::get_option('ngg_options');
	    
	    //Set sort order value, if not used (upgrade issue)
	    $ngg_options['galSort'] = ($ngg_options['galSort']) ? $ngg_options['galSort'] : 'pid';
	    $ngg_options['galSortDir'] = ($ngg_options['galSortDir'] == 'DESC') ? 'DESC' : 'ASC';
	    
	    Xx_Log::logDebug( 'Fetching galleries ['.$gallery_id.']['.$pid.']');
	    
	    // get the pictures
	    $picturelist = nggdb::get_gallery( $gallery_id, $ngg_options['galSort'], $ngg_options['galSortDir']);
	    
		$i=0;
		
		$this->selectedIndex	=	0;
		$this->javascriptLoad	=	'';
		
		foreach ( $picturelist as $picture ) {
			if ( $picture->pid == $pid )
				$this->selectedIndex = $i;
			
			$this->galleryTitle	=	$picture->title;
			
			$this->javascriptLoad	.=	"
						dao.ReadImage( ".$i.",'".$picture->imageURL."','".$picture->thumbURL."','".
							$this->getSlideTitle( $picture->alttext)."','".$this->getSlideDesciption( $picture->description)."');
					";
			$i++;
		}
	}
	
	private function _getGalleryIdFromPicture( $pid)
	{
		$image	=	nggdb::find_image( $pid);
		
		if ($image === false)
			throw new Exception( 'Gallery for image ['.$pid.'] not found');
		
		return $image->galleryid;
	}
	
	public function getGalleryTitle()
	{
		return $this->galleryTitle;
	}
}




