<?php

require_once 'Jph/Wp/Admin/Editor/IEditor.php';

class Jph_Wp_Admin_Editor_Textarea implements Jph_Wp_Admin_Editor_IEditor
{
	public $rows;
	public $cols;
	public function __construct( $rows=5, $cols=60)
	{
		$this->rows	=	$rows;
		$this->cols	=	$cols;
	}
	
	public function printHtml( $name, $value)
	{
		?>
		<textarea name="<?php echo $name; ?>" rows="<?php echo $this->rows; ?>" cols="<?php echo $this->cols; ?>"><?php echo htmlentities( $value); ?></textarea>
		<?php
	}
}