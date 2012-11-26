<?php 

	require_once 'Jph/Wp/JaiphoPlugin.php';
	
	$template	=	Jph_Wp_JaiphoPlugin::getTemplateApi();

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	 
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
	
	<title><?php echo $template->getPageTitle() ?></title>

	<!-- 
		JAIPHO BETA version 0.60.01 - iPhone optimized javascript gallery
		Check on http://www.jaipho.com/ for latest news and source updates 
	 -->
	 
		<?php wp_head() ?>
		
	<script type="text/javascript">
		
<?php echo $template->getJavascriptConfig() ?>
		
	 	if (DEBUG_MODE)
			JphUtil_Console.CreateConsole( DEBUG_LEVELS);
		
	</script>	
	
</head>

<body onload="init_jaipho()">
	
	<!-- 
		Important! 
		Do not remove elements with html attribute id set to some value. Those elements are required by javascript application.
		All other can be customized as required by project needs.
	 -->
	
	<!-- SPLASH SCREEN -->
	<!-- 
		Splash screen actually is not part of the Jaipho library. It is only part of a template.
	-->
	<table id="splash-screen" class="splash-screen">
	<tr>
		<td class="text">
		<?php echo $template->getLoadingTitle() ?>
		</td>
	</tr>
	<tr>
		<td class="image">
		<?php echo $template->getSplashscreenHtml() ?>
		&nbsp;
		</td>
	</tr>
	</table>
	
    <script type="text/javascript">

    function is_iphone_safari()
    {
    	var iphone	=	navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPod/i);
    	var safari	=	navigator.userAgent.match(/Safari/i);
    	var chrome	=	navigator.userAgent.match(/CriOS/i);
    	
    	if(iphone && safari && !chrome)
    		return true;
    	return false;
    };
    
    // iPhone fix to force full screen height even when there is not enough content on page
	function iphone_enforce_height()
	{
		var start	=	window.innerHeight;	
		var max		=	start * 2;
		document.body.style.height	= max + 'px';

		// enlarge to fit screen
		window.scrollTo(0,0); 
		document.body.style.height	= window.innerHeight + 'px';
	}
	
    </script>
    
    <script type="text/javascript">

    if (is_iphone_safari())
		iphone_enforce_height();
    else
		document.body.style.height	= document.documentElement.clientHeight + 'px'
    
	function init_jaipho()
	{
		var splash	=	document.getElementById( 'splash-screen');
		if (SPLASH_SCREEN_DURATION > 0)
			splash.style.display	=	'table';
		
		setTimeout('_init_jaipho()', SPLASH_SCREEN_DURATION);
	}
    
    </script>
	<!-- SPLASH SCREEN - END -->
	
	
	
	<!-- JAIPHO PRELOAD IMAGES -->
	<div id="css-preloader">
	</div>
		
	<!-- THUMBNAILS -->
	<div class="toolbar" id="thumbs-toolbar-top">
	
		<table cellpadding="0" cellspacing="0">
		<tr>
			<td class="wing">
				<a class="button" href="<?php echo $template->getThumbsBackLink() ?>">
					<?php echo $template->getThumbsBackTitle() ?>
				</a> 
			</td>
			<td class="center">
				<?php echo $template->getPageTitle() ?>
			</td>
			<td class="wing"></td>
		</tr>
		</table>
		
	</div>
	
    <div id="thumbs-container">
    	<?php echo $template->getThumbnailsHtml() ?>
    	
    	<div id="thumbs-images-container">
		</div>	
		<div id="thumbs-count-text">
		</div>
    </div>

	
	<!-- SLIDER -->

    <div id="slider-container">

		<div class="toolbar" id="slider-toolbar-top">
			<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="wing">
					<a class="button" href="<?php echo $template->getSliderBackLink() ?>">
						<?php echo $template->getSliderBackTitle() ?>
					</a> 
				</td>
				<td class="center" id="navi-info">
				</td>
				<td class="wing">&nbsp;</td>
			</tr>
			</table>
		</div>
	
		<div class="toolbar" id="slider-toolbar-bottom">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
				<td>
					<a class="navi-button" id="navi-prev" href="javascript: void(0);">
					</a> 
				</td>
				<td style="width: 80px;">
					<a class="navi-button" id="navi-play" href="javascript: void(0);">
					</a>
					<a class="navi-button" id="navi-pause" href="javascript: void(0);">
					</a>
				</td>
				<td>
					<a class="navi-button" id="navi-next" href="javascript: void(0);">
					</a>
				</td>
			</tr>
			</table>
		</div>
    </div>
    

	<script type="text/javascript">

		// APPLICATION INIT BLOCK 
		// v 0.53
		
		 // load images
		var dao	=	new Jph_Dao();

		<?php echo $template->getJavascriptLoad(); ?>

		var jaipho;
		
		function _init_jaipho()
		{
			// remove splash screen
			var splash	=	document.getElementById( 'splash-screen');
			splash.style.display	=	'none';

			// start jaipho
			jaipho	=	new Jph_Application( dao);
			jaipho.Init();
			jaipho.Run();

			// wpjaipho selection
			jaipho.mrSlider.mrSlidesComponent.SelectSlide( <?php echo $template->getSelectedIndex() ?>);
		}
		
	</script>
	
</body>
</html>