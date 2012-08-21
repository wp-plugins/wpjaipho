<?php

require_once 'Jph/Wp/Admin/Editor/IEditor.php';

class Jph_Wp_Admin_Editor_Checkbox implements Jph_Wp_Admin_Editor_IEditor
{
	private $value;
	
	public function __construct( $value)
	{
		$this->value	=	$value;
	}

	public function printHtml( $name, $value)
	{
		$checked	=	$this->value == $value;
		?>
		<input type="checkbox" name="<?php echo $name; ?>" value="<?php echo $this->value; ?>" <?php echo $checked ? 'checked' : '' ?>>
		<?php
	}
}