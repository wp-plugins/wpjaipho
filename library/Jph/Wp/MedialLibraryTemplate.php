<?php

require_once 'Pipho/DeviceInfo.php';

require_once 'Jph/Wp/AbstractTemplateApi.php';

class Jph_Wp_MediaLibraryTemplate  extends Jph_Wp_AbstractTemplateApi
{
	
	public function __construct( Jph_Wp_JaiphoPlugin $plugin)
	{
		parent::__construct($plugin);
	}
	
	public function init()
	{
		parent::init();
		
		global $post;
		$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 
				'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
		$i=0;
		
		$this->selectedIndex	=	0;
		$this->javascriptLoad	=	'';
		
		foreach ( $attachments as $k => $attachment ) 
		{
			if ( $attachment->ID == $post->ID )
				$this->selectedIndex = $i;
			
			// THUMBNAIL
			list($thumb) = wp_get_attachment_image_src( $attachment->ID, 'jaipho-thumbnail');
			
			// FULL SIZE
			list($slide) = wp_get_attachment_image_src( $attachment->ID, 9999);
			
			$this->javascriptLoad	.=	"
						dao.ReadImage( ".$i.",'".$slide."','".$thumb."','".$this->getSlideTitle( get_the_title( $attachment->ID))."','".$this->getSlideDesciption( $attachment->post_content)."');
					";
			$i++;
		}
	}
}




