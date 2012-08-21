<?php

require_once 'Jph/Wp/Admin/Editor/IEditor.php';

class Jph_Wp_Admin_Editor_Select implements Jph_Wp_Admin_Editor_IEditor
{
	public $options;
	
	public function __construct( $options)
	{
		$this->options	=	$options;
	}

	public function printHtml( $name, $value)
	{
		// TODO: implement
		?>
		<input type="text" name="<?php echo $name; ?>" value="<?php echo $value; ?>" size="20">
		<?php
	}
}