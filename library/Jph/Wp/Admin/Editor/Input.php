<?php

require_once 'Jph/Wp/Admin/Editor/IEditor.php';

class Jph_Wp_Admin_Editor_Input implements Jph_Wp_Admin_Editor_IEditor
{
	public $size;
	public function __construct( $size=20)
	{
		$this->size	=	$size;
	}

	public function printHtml( $name, $value)
	{
		?>
		<input type="text" name="<?php echo $name; ?>" value="<?php echo htmlentities( $value); ?>" size="<?php echo $this->size; ?>">
		<?php
	}
}