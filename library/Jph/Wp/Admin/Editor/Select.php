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
		?>
		<select name="<?php echo $name; ?>">
		<?php
			foreach ($this->options as $option)
			{
				echo '<option'.($option == $value ? ' selected' : '').'>'. $option . '</option>';
			}
		?>
		</select>
		<?php
	}
}