<?php

class Jph_Wp_Admin_OptionsPage
{
	
	private $saved			=	false;
	/**
	 * Enter description here ...
	 * @var Jph_Wp_PluginConfiguration
	 */
	private $configuration;

	private $errors			=	array();
	
	
	public function __construct( Jph_Wp_PluginConfiguration $configuration)
	{
		$this->configuration	=	$configuration;
	}
	
	public function init()
	{
// 		Xx_Log::printR( $_POST);
		
		// PROCESS
		if( $this->isSubmited()) {
			
			Xx_Log::logDebug( 'Form submited. Processing ['.$_POST['process'].'] ...');
			
			if ($_POST['process'] == 'update' )
			{
				// @var $section Jph_Wp_PluginConfiguration
				// @var $option Jph_Wp_JaiphoConfigurationOption
				
				// VALIDATE
				foreach ($this->configuration->sections as $section)
				{
					foreach ( $section->options as $option)
					{
						if (!$option->validator)
							continue;
						
						$response	=	$option->validator->validate( $this->getFormValue( $option->optionName));
						
						if (!$response->isValid())
						{
							Xx_Log::logDebug( 'Error on form: '.$response->getMessage());
							$this->addError( $option->optionName, $response->getMessage());
						}
					}
				}
				
				
				// SAVE - nothing is saved if only single form error occoured
				if (count( $this->errors) == 0)
				{
					foreach ($this->configuration->sections as $section)
					{
						foreach ( $section->options as $option)
						{
							update_option( $option->optionName, $option->adapter->getRealValue( $this->getFormValue( $option->optionName)) );
						}
					}		

					$this->saved	=	true;
				}	
			}
		}
	}
	
	private function addError( $optionName, $message)
	{
		$this->errors[$optionName]	=	$message;
	}
	
	private function getFormValue( $optionName)
	{
		if ($this->isSubmited() && !$this->saved)
		{
			return @$_POST[ $this->getFormName($optionName)];
		}
	
		$option		=	$this->configuration->getOption( $optionName);
		return $option->adapter->getDisplayValue( $option->getValue());
	}
	
	private function getFormName( $optionName)
	{
		return $optionName;
	}

	private function isSubmited()
	{
		return isset($_POST['process']);
	}
	
	
	// HTML
	public function printHtml()
	{
		// MSG
		if ($this->saved)
		{
?>
		<div class="updated"><p><strong><?php _e('settings saved.', 'menu-test' ); ?></strong></p></div>
<?php
		}
		
		if (count($this->errors) > 0)
		{
			?>
				<div class="error"><p><strong>Please enter valid values</strong></p></div>
		<?php
		}		
		
		
		// FORM
?>
		<h2>
			WPJaipho
		</h2>
		<p>
			WpJaipho is a plugin which embeds Jaipho javascript gallery into Wordpress. 

What does it do? 
For iPhone and iPad users, it replaces the standard media library image gallery with Jaipho gallery template. Jaipho itself is an iPhone and iPad optimized javascript image gallery and it tends to look and behave like an native application. More info about Jaipho can be found at <a href="http://jaipho.com/" target="_blank">jaipho.com</a>
			
		</p>
		<form name="form1" method="post" action="">
			<input type="hidden" name="process" value="update">
			
			<div>
<?php 
			$this->printSectionHtml( $this->configuration->getSection( 'basic-options'));
?>
			</div>
			<br/>
			<br/>
			<br/>
			<em>
				The next section is about configuring Jaipho javascript gallery. About possible options and their meanings, 
				you can check on <a href="http://www.jaipho.com/content/jaipho-configuration">http://www.jaipho.com/content/jaipho-configuration</a>
			</em>
			<div style="clear: both;">
			</div>
			<div style="float: left;">
<?php 
			$this->printSectionHtml( $this->configuration->getSection( 'advanced-options'));
?>
			</div>

			
			<div style="clear: both;"></div>
			<hr/>
			<p class="submit">
			<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
			<input type="button" name="Discard" class="button-secondary" value="Discard Changes" 
				onclick="window.location = window.location.href;"/>
			</p>											
		</form>
<?php 
	}
	
	public function printSectionHtml( Jph_Wp_ConfigurationSection $section)
	{
?>
		
		<h3><?php echo $section->title ?></h3>
<!-- 		<p> -->
<!-- 			Description .... -->
<!-- 		</p> -->
		
		<table class="form-table">		
<?php 
		foreach ($section->options as $option)
		{
			// @var $option Jph_Wp_JaiphoConfigurationOption
?>
		<tr>
			<th class="" title="<?php echo htmlentities( $option->description) ?>"><?php echo $option->label; ?>:</th>
			<td>	
				<?php $option->editor->printHtml( $this->getFormName( $option->optionName), $this->getFormValue( $option->optionName)); ?>
				<?php $this->printError( $option->optionName); ?>
			</td>
		</tr>
<?php 
		}
		?>
		</table>
<?php 
	}
	
	private function printError( $optionName)
	{
		if (isset( $this->errors[$optionName]))
		{
?>
		<span style="color: red;"><?php echo $this->errors[$optionName]; ?></span>
<?php 
		}
	}	
	
}

