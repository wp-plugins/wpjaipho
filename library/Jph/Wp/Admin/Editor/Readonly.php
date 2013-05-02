<?php

require_once 'Jph/Wp/Admin/Editor/IEditor.php';

class Jph_Wp_Admin_Editor_Readonly implements Jph_Wp_Admin_Editor_IEditor
{
	public function __construct()
	{
	}

	public function printHtml( $name, $value)
	{
		?>
		<b><?php echo $value; ?></b>
		<input type="hidden" name="<?php echo $name; ?>" value="<?php echo htmlentities( $value); ?>">
		<?php
	}
}