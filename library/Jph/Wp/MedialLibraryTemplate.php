<?php

require_once 'Pipho/DeviceInfo.php';

require_once 'Jph/Wp/ITemplateApi.php';

class Jph_Wp_MediaLibraryTemplate implements Jph_Wp_ITemplateApi
{
	
	/**
	 * Enter description here ...
	 * @var Jph_Wp_JaiphoPlugin
	 */
	public $plugin;
	
	protected $javascriptLoad	=	'';
	protected $selectedIndex	=	0;
	
	public function __construct( Jph_Wp_JaiphoPlugin $plugin)
	{
		$this->plugin	=	$plugin;
	}
	
	
	public function init()
	{
		global $post;
		$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
		$i=0;
		
		$this->selectedIndex	=	0;
		$this->javascriptLoad	=	'';
		
		foreach ( $attachments as $k => $attachment ) {
			if ( $attachment->ID == $post->ID )
				$this->selectedIndex = $i;
			// 	break;
			list($src, $width, $height) = wp_get_attachment_image_src( $attachment->ID, 9999);
			$this->javascriptLoad	.=	"
						dao.ReadImage( ".$i.",'".$src."','','".get_the_title( $attachment->ID)."','');
					";
			$i++;
		}
		
		
		wp_enqueue_script( 'jaipho-preload', plugins_url( 'wpjaipho/jaipho/jaipho-0.55.00-preload-src.js'));
		wp_enqueue_script( 'jaipho-main', plugins_url( 'wpjaipho/jaipho/jaipho-0.55.00-main-src.js'));
		wp_enqueue_style( 'jaipho-default', plugins_url( 'wpjaipho/jaipho/Themes/Default/jaipho.css'));
		
		if ($this->isIpad())
			wp_enqueue_style( 'jaipho-default-ipad', plugins_url( 'wpjaipho/jaipho/Themes/Default/jaipho-ipad.css'));
		
		
	}
	
	
	public function getPageTitle()
	{
		global $post;
		return get_the_title( $post);
	}
	
	/**
	* Return should gallery be displayed to an iPad user.
	* This method calcualetes in the possible admin wp options too.
	* @return boolean
	*/
	public function isIpad()
	{
		return Pipho_DeviceInfo::isIpad() && !$this->plugin->getOptionValue( 'jaipho_disable_ipad');
	}
	
	
	/**
	* Dumps the Jaipho javascript configuration block. Further info about Jaipho configuration http://www.jaipho.com/content/jaipho-configuration
	* @return string
	*/
	public function getJavascriptConfig()
	{
		$str		=	'';
		$options	=	$this->plugin->configuration->getJaiphoOptions();
	
		foreach ($options as $option)
		{
			$str 	.=	$option->javascript();
			$str 	.=	"\n";
		}
	
		return $str;
	}
	
	public function getJavascriptLoad()
	{
		return $this->javascriptLoad;
	}
	
	public function getSelectedIndex()
	{
		return $this->selectedIndex;
	}
	
	
	public function getLoadingTitle()
	{
		return $this->plugin->getOptionValue( 'jaipho_loading_title');
	}
	
	public function getPostTitle()
	{
		global $post;
		return get_the_title( $post->post_parent );
	}
	
	public function getPostPermalink()
	{
		global $post;
		return get_permalink( $post->post_parent );
	}
}




