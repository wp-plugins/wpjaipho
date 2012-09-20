<?php

require_once 'Jph/Wp/Admin/Editor/IEditor.php';

class Jph_Wp_Admin_Editor_Input implements Jph_Wp_Admin_Editor_IEditor
{

	public function printHtml( $name, $value)
	{
		?>
		<input type="text" name="<?php echo $name; ?>" value="<?php echo $value; ?>" size="20">
		<?php
	}
}