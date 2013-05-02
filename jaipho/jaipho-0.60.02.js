/******************************************************************************
 *	JAIPHO BETA, version 0.60.02 array traversing fix
 *
 *	JAIPHO BETA is freely used under the terms of an LGPL license.
 *	For details, see the JAIPHO web site: http://www.jaipho.com/
 ******************************************************************************/
				
 function JphUtil_WebkitAnimation( elem)
{
	this.mhElement	=	elem;
	this.mUse2d		=	false;
}

JphUtil_WebkitAnimation.prototype.Init	=	function()
{
	this.mUse2d		=	this._ShouldUse2d();
};

JphUtil_WebkitAnimation.prototype._ShouldUse2d = function()
{
	var android	=	navigator.userAgent.match(/Android/i);
	
	if(android)
		return true;
	return false;
};

JphUtil_WebkitAnimation.prototype.SlideTo	=	function( position)
{
	this.mhElement.style.WebkitTransition	=	"-webkit-transform "+SLIDE_SCROLL_DURATION+" ease-out";
	this._Transform( position);
};

JphUtil_WebkitAnimation.prototype.SetTo		=	function( position)
{
	this.mhElement.style.WebkitTransition	=	"";
	this._Transform( position);
};

JphUtil_WebkitAnimation.prototype._Transform		=	function( position)
{
	if (this.mUse2d)
		this.mhElement.style.WebkitTransform	=	"translate( "+ position + "px,0)";
	else
		this.mhElement.style.WebkitTransform	=	"translate3d( "+ position + "px,0,0)";	
};
				
function JphUtil_Touches( container, prventDefaults)
 {
 	this.mhContainer		=	container;
	this.mPreventDefaults	=	prventDefaults && BLOCK_VERTICAL_SCROLL;
	
	implement_events( this);
 }

 JphUtil_Touches.prototype.Init	=	function()
 {
	if (this._IsTouchable())
	{
	 	attach_method( this.mhContainer, 'ontouchstart', this, '_OnTouchStart');	
	 	attach_method( this.mhContainer, 'ontouchend', this, '_OnTouchEnd');
	 	attach_method( this.mhContainer, 'ontouchmove', this, '_OnTouchMove');
	 	attach_method( this.mhContainer, 'ontouchcancel', this, '_OnTouchCancel');				
	}
	else
	{
		attach_method( this.mhContainer, 'onclick', this, '_OnTouchEnd');
	}

	
	this.mMoving		=	false;
	this.mTouching 		= 	false;
	
	this.mOriginalX		=	null;
	this.mCurrentX		=	null;
	this.mScrollX		=	null;
	
	this.mOriginalY		=	null;
	this.mCurrentY		=	null;
	this.mScrollY		=	null;
	
 }
 
 JphUtil_Touches.prototype._IsTouchable = function()
 {
	try
	{
		document.createEvent('TouchEvent');
	}
	catch (e)
	{
		return false;
	}
	
	return true;
 }
 
  
// EVENT HANDLERS
 JphUtil_Touches.prototype._OnTouchStart		=	function( e)
 {
	
	if (e.touches.length == 1) 
	{
		this.mMoving		=	false;
		this.mTouching 		= 	true;
		var touch 			= 	e.touches[0];
		// If they user tries clicking on a link
		if(touch.target.onclick) 
		{
			touch.target.onclick();
		}
		// The originating X-coord (point where finger first touched the screen)
		this.mOriginalX = touch.pageX;
		this.mCurrentX 	= this.mOriginalX;
		this.mScrollX 	= 0;
		
		this.mOriginalY = touch.pageY;
		this.mCurrentY 	= this.mOriginalY;
		this.mScrollY 	= 0;		
		
		this.FireEvent( 'TouchStart', e);
		
	}
	
 }


 JphUtil_Touches.prototype._OnTouchMove		=	function( e)
 {
	if (this.mPreventDefaults)
		e.preventDefault();

	if (e.touches.length == 1) 
	{
		var touch 		=	e.touches[0];
		this.mCurrentX 	= 	touch.pageX;
		this.mCurrentY 	= 	touch.pageY;
		this.mMoving	=	true;
		this.mTouching	=	false;
		
		var scroll		=	this.mScrollX;
	 	this.mScrollX 	= 	this.mOriginalX - this.mCurrentX;
		this.mScrollY 	= 	this.mOriginalY - this.mCurrentY;	

		scroll			=	scroll - this.mScrollX;
		
		var event	=	new JphUtil_Event( 'Moving', this, e);
		event.mScrollX	=	this.mScrollX;
		event.mScroll	=	scroll;
		
		//debug('move: this.mScrollX['+this.mScrollX+'], scroll['+scroll+']');
		if (scroll)
			this.FireEvent( event);
	}

 }
 
 JphUtil_Touches.prototype._OnTouchEnd			=	function( e)
 {

 	if (this.mMoving) 
	{

		if (this.mOriginalX > this.mCurrentX) 
		{
			this.mScrollX 	= 	this.mOriginalX-this.mCurrentX;
			if (this.mScrollX > MIN_DISTANCE_TO_BE_A_DRAG) 
			{
				this.FireEvent( 'MovedLeft', e);
			}
		} 
		else 
		{
			this.mScrollX 	= 	this.mCurrentX-this.mOriginalX;
			if (this.mScrollX > MIN_DISTANCE_TO_BE_A_DRAG) 
			{
				this.FireEvent( 'MovedRight', e);
			}
		}
		
		if (this.mOriginalY > this.mCurrentY) 
		{
			this.mScrollY 	= 	this.mOriginalY-this.mCurrentY;
			if (this.mScrollY > MIN_DISTANCE_TO_BE_A_DRAG) 
			{
				this.FireEvent( 'MovedUp', e);
			}
		} 
		else 
		{
			this.mScrollY 	= 	this.mCurrentX-this.mOriginalY;
			if (this.mScrollY > MIN_DISTANCE_TO_BE_A_DRAG) 
			{
				this.FireEvent( 'MovedDown', e);
			}
		}
		
		this.FireEvent( 'MoveCancel', e);
	}
	else 
	{
		this.FireEvent('TouchEnd', e);
	}

	this.mMoving		=	false;
 	this.mTouching 		= 	false;		
 }
  
 JphUtil_Touches.prototype._OnTouchCancel		=	function( e)
 {
 	this._OnTouchEnd( e)
 }			
 function JphUtil_PreloaderItem( imageElement, src)
 {
	this.mhImage	=	imageElement;
	this.mSrc		=	src;
 }
 
 
 JphUtil_PreloaderItem.prototype.LoadImage	=	function()
 {
 	this.mhImage.src	=	this.mSrc;
 };
 
 JphUtil_PreloaderItem.prototype.toString	=	function()
 {
 	return 'JphUtil_PreloaderItem ['+this.mSrc+']['+this.mhImage.getAttribute("src")+']';
 };
				
function JphUtil_Preloader( maxActiveCount)
 {
 	this.mMaxActiveCount	=	maxActiveCount;
 	
 	this.mActiveCount		=	0;
 	this.mPaused			=	false;
	this.maAllImages		=	[];
	this.maQueue			=	new Array();
 }
 
 JphUtil_Preloader.prototype.Play		=	function()
 {
	this.mPaused			=	false;
	this._LoadNext();
 }
 
 JphUtil_Preloader.prototype.Pause		=	function()
 {
	this.mPaused			=	true;
 }


 JphUtil_Preloader.prototype.Load	=	function( img, src)
 {
 	
 	if (this.maAllImages[src] != undefined)
 	{
 		img.src	=	src;
 		this._ImageLoaded( src);
 		return;
 	}
 	
	var item				=	new JphUtil_PreloaderItem( img, src);
	this.maAllImages[src]	=	item;
	
	if (this.mActiveCount < this.mMaxActiveCount)
	{
		this._LoadItem( item);
	}
	else
	{
		this.maQueue[this.maQueue.length]	=	item;
	}
 }
   
  
 JphUtil_Preloader.prototype._LoadItem	=	function( item)
 {
 	
 	this.mActiveCount++;
	attach_method( item.mhImage, 'onload', this, '_ImageLoaded');
	attach_method( item.mhImage, 'onerror', this, '_ImageError');
	item.LoadImage();
 }
 
 JphUtil_Preloader.prototype._ImageError	=	function( e)
 {
 	if (!e) 
 		var e = window.event;
 	var target	=	e.target ? e.target : e.srcElement;
 	this.mActiveCount--;
 	delete this.maAllImages[target.src];
 	if (!this.mPaused)
 		this._LoadNext();
 }
 
 JphUtil_Preloader.prototype._ImageLoaded	=	function( e)
 {
 	if (typeof(e) == 'string')
 	{
 		var src	=	e;
 	}
 	else
 	{
	 	if (!e) 
	 		var e = window.event;
	 	var target	=	e.target ? e.target : e.srcElement;
	 	var src		=	target.src;
 	}
	this.mActiveCount--;
	if (!this.mPaused)
		this._LoadNext();
 }
 
 JphUtil_Preloader.prototype._LoadNext	=	function()
 {

	if (this.maQueue.length)
	{
		this._LoadItem( this.maQueue.shift());
	}
 }
 
 JphUtil_Preloader.prototype.toString	=	function()
 {
 	return '[JphUtil_Preloader [queue length='+this.maQueue.length+'][active count='+this.mActiveCount+']]';
 }
				
 var ORIENTATION_MODE_PORTRAIT		=	'portrait';
 var ORIENTATION_MODE_LANDSCAPE		=	'landscape';
 var ORIENTATION_MODE_HTML_ATTRIBUTE	=	'orient';

function JphUtil_OrientationManager( container)
{
	this.maListeners	=	new Array();
	
	this.mMode			=	null;
	this._mLastWidth	=	0;
	this.mrContainer	=	container;
	
	implement_events( this);
	
	this.mForceMode;
}
 
JphUtil_OrientationManager.prototype.ForceMode = function( mode)
{
	this.mForceMode	=	mode;
};


JphUtil_OrientationManager.prototype.Init = function()
{
	if (this.mForceMode)
	{
		this._SetMode( this.mForceMode);
		return;
	}
	
	this._Recheck();	
	set_interval(this, '_Recheck', '', CHECK_ORIENTATION_INTERVAL);
};
 
 // INTERVAL
JphUtil_OrientationManager.prototype._Recheck	=	function()
{
	var width	=	this.mrContainer.GetWidth();
	if (this._mLastWidth != width)
	{
		this._mLastWidth 	= 	width;
		var height			=	this.mrContainer.GetHeight();
		
		var mode 			=	(width <= height ? ORIENTATION_MODE_PORTRAIT : ORIENTATION_MODE_LANDSCAPE);
		
		this._SetMode( mode);
	}
};

 // ACCESSORS
JphUtil_OrientationManager.prototype.IsPortrait	=	function()
{
	if (this.mMode == ORIENTATION_MODE_PORTRAIT)
		return true;
};

JphUtil_OrientationManager.prototype._SetMode	=	function( mode)
{
	this.mMode 			=	mode;
	
	document.body.setAttribute( ORIENTATION_MODE_HTML_ATTRIBUTE, this.mMode);
	
	this.FireEvent( 'OrientationChanged');
};







function JphUtil_JaiphoContainerFactory()
{
}
 
JphUtil_JaiphoContainerFactory.prototype.GetContainer = function()
{
	if (this._IsIphoneSafari())
		var container	=	new JphUtil_IphoneSafariContainer();
	else
		var container	=	new JphUtil_DisplayContainer();
	
	container.Init();
	
	return container;
};

JphUtil_JaiphoContainerFactory.prototype._IsIphoneSafari = function()
{
	var iphone	=	navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPod/i);
	var safari	=	navigator.userAgent.match(/Safari/i);
	var chrome	=	navigator.userAgent.match(/CriOS/i);
	
	if(iphone && safari && !chrome)
		return true;
	return false;
};





function JphUtil_IphoneSafariContainer()
{
	
	this.RESIZE_TIMEOUT	=	300; // miliseconds
	
	this.mWidth		=	0;
	this.mHeight	=	0;
	
	this.mResizeTimeout	=	null;
	
//	this.mhContainer	=	document;
	
	implement_events( this);
}
 
JphUtil_IphoneSafariContainer.prototype.Init = function()
{
	attach_method( window, 'onresize', this, '_OnResize');
	this._OnResize( null);
};


JphUtil_IphoneSafariContainer.prototype._OnResize = function( e)
{
	
	if (this.mResizeTimeout)
		clearTimeout( this.mResizeTimeout);
	
	this.mResizeTimeout	=	set_timeout( this, '_OnDelayedResize', null, this.RESIZE_TIMEOUT);
	
};

JphUtil_IphoneSafariContainer.prototype._OnDelayedResize = function()
{
	this.mResizeTimeout	=	null;
	
	var width		=	document.documentElement.clientWidth;
	
	if (width != this.mWidth)
		var height		=	this._GetForcedHeight();
	else
		var height		=	window.innerHeight;
	
	var changed		=	 width != this.mWidth || height != this.mHeight;
	
	this.mWidth		=	width;
	this.mHeight	=	height;
	
	if (changed)
	{
		this.FireEvent( 'ContainerResized');
	}
};

JphUtil_IphoneSafariContainer.prototype._GetForcedHeight = function()
{
//	var height		=	document.documentElement.clientHeight;
	
	var start	=	window.innerHeight;	
	var max		=	start * 2;

	document.body.style.height	= max + 'px';
	scrollTo(0,0);
	var height	=	window.innerHeight;
//	document.body.style.height	= null;
	document.body.style.height	= height + 'px';
	document.body.style.minHeight	= height + 'px';
	
	return height;
};

JphUtil_IphoneSafariContainer.prototype.GetWidth = function()
{
	return this.mWidth;
};

JphUtil_IphoneSafariContainer.prototype.GetHeight = function()
{
	return this.mHeight;
};


function debug_container_info()
{
//	document.body.style.height	=	'416px';
	
		
}

				
function JphUtil_Event( name, source, e)
 {
	this.mName	=	name;
 	this.mrSource	=	source;
 	this.mrEvent	=	e;
 }
 
 function implement_events( obj)
 {
	obj.maListeners		=	new Array();
	
	obj.AttachListener	=	function( name, o, method, argument)
	{
		if (o == null || o == undefined)
			throw new Error('Empty object passed');
			
		var arr					= 	this.maListeners[name] || (this.maListeners[name] = []);
		var index				=	arr.length;
		arr[index]				=	new Array();
		arr[index]['master']	=	o;
		arr[index]['method']	=	method;
		arr[index]['argument']	=	argument;
	}
	
	obj.FireEvent			=	function( name, originalEvent)
	{
		if (typeof name == 'string') {
			var e		=	new JphUtil_Event( name, obj, originalEvent);
		} else {
			var e		=	name;
		}
		
		name		=	e.mName;
		
		var arr		= 	this.maListeners[name] || (this.maListeners[name] = []);
		
		
		for (var i=0; i<arr.length; i++)
		{
			var master		=	arr[i]['master'];
			var method		=	arr[i]['method'];
			var argument	=	arr[i]['argument'];

			var ret = master[method](e, argument);
			
			if (ret == undefined)
				ret = null;

			if (ret)
				return;
		}
	}
 }



function JphUtil_DisplayContainer()
{
	
	this.mWidth		=	0;
	this.mHeight	=	0;
	
	implement_events( this);
}
 
JphUtil_DisplayContainer.prototype.Init = function()
{
	attach_method( window, 'onresize', this, '_OnResize');
	this._OnResize( null);
};


JphUtil_DisplayContainer.prototype._OnResize = function( e)
{
	
	var width		=	document.documentElement.clientWidth;
	var height		=	document.documentElement.clientHeight;
	
	var changed		=	 width != this.mWidth || height != this.mHeight;
	
	this.mWidth		=	width;
	this.mHeight	=	height;
	
	if (changed)
	{
		this.FireEvent( 'ContainerResized');
	}
};

JphUtil_DisplayContainer.prototype.GetWidth = function()
{
	return this.mWidth;
};

JphUtil_DisplayContainer.prototype.GetHeight = function()
{
	return this.mHeight;
};

function JphUtil_DefaultAnimation( elem)
{
	this.mhElement	=	elem;
}

JphUtil_DefaultAnimation.prototype.Init	=	function()
{
};

JphUtil_DefaultAnimation.prototype.SlideTo	=	function( position)
{
	this.SetTo( position);
};

JphUtil_DefaultAnimation.prototype.SetTo		=	function( position)
{
	this.mhElement.style.marginLeft	=	position + 'px';
};

			
function debug( str) 
 {
	if (!(window.DEBUG_MODE || window.DEBUG_AJAX_URL))
		return;

	try
	{
		JphUtil_Console.GetInstance().Debug( str);
	}
	catch (err)
	{
		throw new Error( 'Failed to debug :: ' + err.message + ' :: [' + debug.caller);
	}
 }
  
 function JphUtil_Console( modules)
 {
	this.maLines	=	new Array();
 	this.mrInstance	=	null;
 	
	if (modules)
		this.maModules	=	modules.split(',');
	else
		this.maModules	=	new Array();
	
	if (DEBUG_AJAX_URL)
	{
		set_interval( this, '_DumpAjaxLog', '', DEBUG_AJAX_TIMEOUT);
	}
 }

 // STATIC
 JphUtil_Console.CreateConsole = function( modules)
 {
	this.mrInstance = new JphUtil_Console( modules);
	this.mrInstance.Init();
 	return this.mrInstance;
 }
 
 JphUtil_Console.GetInstance = function()
 {
 	if (this.mrInstance == null)
		throw new Error('Console not started');
 	return this.mrInstance;
 }
 // STATIC - END
 
 JphUtil_Console.prototype.Init = function()
 {
 	document.write( this._GetStyleHtml());
 }
 
 JphUtil_Console.prototype._GetStyleHtml = function()
 {
	var str			=	new Array();
	str[str.length]	=	'<style>';
	str[str.length]	=	'.console';
	str[str.length]	=	'{';
	str[str.length]	=	'overflow:auto;';
	str[str.length]	=	'border: 2px solid orange;';
	str[str.length]	=	'position: absolute;';
//	str[str.length]	=	'left:10px;';
	str[str.length]	=	'background: black;';
	str[str.length]	=	'opacity: 0.70;';
	str[str.length]	=	'font-size: 10px !important;';
	str[str.length]	=	'font-weight: bold;';
	str[str.length]	=	'color: white;';
	str[str.length]	=	'z-index:100;';
	str[str.length]	=	'}';
	str[str.length]	=	'#console';
	str[str.length]	=	'{';
	str[str.length]	=	'width: 95%;';
	str[str.length]	=	'height: 140px;';
	str[str.length]	=	'top:100px;';
	str[str.length]	=	'}';
//	str[str.length]	=	'body[orient="landscape"] #console';
//	str[str.length]	=	'{';
//	str[str.length]	=	'width: 460px;';
//	str[str.length]	=	'top: 100px;';
//	str[str.length]	=	'height: 100px;';
//	str[str.length]	=	'}';
	str[str.length]	=	'</style>';
	
	return str.join('');
 }
 

 JphUtil_Console.prototype.Debug = function( str)
 {
	if (!this._ShouldDebug( str))
		return;
	
	var d 	= 	new Date();	
	
	this.maLines[this.maLines.length]	=	d / 1000 + ': ' + str;
	
	if (!DEBUG_MODE)
		return;
	
	try
	{
		if (navigator.userAgent.indexOf('Chrome') > -1)
		{
			
		}
		else if (navigator.userAgent.indexOf('iPhone') > -1)
		{
			throw new Error('iPhone console is unuable in this case');
		}
			
		
		var d 	= 	new Date();	
		str		=	d / 1000 + ': ' + str;
		console.log.apply( console, arguments);
	}
	catch(err)
	{
//	    var d		=	document.createElement('div');
//		d.innerHTML	=	str;
//		var c		=	this._GetContainer();
//		c.appendChild( d);
//		c.scrollTop	=	c.scrollHeight - c.clientHeight;	
	}
 };
  
 JphUtil_Console.prototype._ShouldDebug = function( str)
 {
	for (var i=0; i<this.maModules.length; i++)
 	{
		if (str.indexOf( this.maModules[i] + ':') == 0)
			return true;	 		
 	}
 };
 
 JphUtil_Console.prototype._GetContainer = function()
 {
 	var c	=	document.getElementById('console');
 	if (!c)
	{
		c			=	document.createElement('div');
		c.id		=	'console';
		c.className	=	'console';
		
		document.body.appendChild( c);
	}
 	return c;
 }
 
 

 JphUtil_Console.prototype._DumpAjaxLog = function()
 {
	 if (this.maLines.length == 0)
		 return;
	 var str		=  	escape( this.maLines.join( '\n'));
	 this.maLines	=	new Array();
	 
	 var xmlhttp;
	 if (window.XMLHttpRequest)
	 {// code for IE7+, Firefox, Chrome, Opera, Safari
	   xmlhttp	=	new XMLHttpRequest();
	 }
	 else
	   {// code for IE6, IE5
	   xmlhttp	=	new ActiveXObject("Microsoft.XMLHTTP");
	 }
	 
	 xmlhttp.open( "POST", DEBUG_AJAX_URL, true);
	 xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	 xmlhttp.send( 'log=' + str);
	 
 };
function JphUtil_AnimationFactory( elem)
{
	this.mhElement	=	elem;
	this.mUse2d		=	false;
}

JphUtil_AnimationFactory.prototype.GetAnimation	=	function()
{
	if (this._IsWebkit())
	{
		var animation	=	new JphUtil_WebkitAnimation( this.mhElement);
		animation.Init();
		return animation;
	}
	
	var animation	=	new JphUtil_DefaultAnimation( this.mhElement);
	animation.Init();
	return animation;
};

JphUtil_AnimationFactory.prototype._IsWebkit = function()
{	
	var ret	=	navigator.userAgent.match(/AppleWebKit/i);
	
	if(ret)
		return true;
	return false;
};

JphUtil_AnimationFactory.prototype._IsAndroid = function()
{
	var ret	=	navigator.userAgent.match(/Android/i);
	
	if(ret)
		return true;
	return false;
};

			
function JphThumbs_ThumbsApp( app)
 {
 	this.mrApp				=	app;
 	this.mrContainer		=	this.mrApp.mrContainer;
 	
 	this.mhThumbnails		=	null;
 	this.mhThumbsTopBar		=	null;
 	this.mhThumbsContainer	=	null;
 	this.mhThumbsCount		=	null;
	
 	this.mrBehavior			=	null;
	this.mrPreloader			=	null;
	
	this.mInitialzed		=	false;
	
	this.maThumbnails		=	new Array();
 }
 
 // INIT
 JphThumbs_ThumbsApp.prototype.Init		=	function()
 {
	this.mhThumbsTopBar		=	document.getElementById('thumbs-toolbar-top');
	this.mhThumbnails		=	document.getElementById('thumbs-images-container');
	this.mhThumbsContainer	=	document.getElementById('thumbs-container');
	this.mhThumbsCount		=	document.getElementById('thumbs-count-text');	
	
	this.mrBehavior			=	new JphThumbs_Behavior( this);
	this.mrBehavior.Init();
	
	for (var i=0; i<this.mrApp.mrDao.maImages.length; i++)
	{
		this.maThumbnails[this.maThumbnails.length]	=	
				new JphThumbs_Item( this.mrApp, this.mrApp.mrDao.maImages[i]);
	} 	
	
	this.mrPreloader				=	new JphUtil_Preloader( MAX_CONCURENT_LOADING_THUMBNAILS);

	
 	this.mhThumbnails.innerHTML		=	this._HtmlThumbs();
	this.mhThumbsCount.innerHTML	=	this._HtmlCount();
	
	for (var i=0; i<this.maThumbnails.length; i++)
	{
		this.maThumbnails[i].Init();
		
		var mover	= new JphUtil_Touches( this.maThumbnails[i].mhDiv, false);
		mover.AttachListener( 'TouchStart', this.mrBehavior, 'ThumbTouched', this.maThumbnails[i]);	
		mover.AttachListener( 'TouchEnd', this.mrBehavior, 'ThumbSelected', this.maThumbnails[i]);	
		mover.AttachListener( 'MoveCancel', this.mrBehavior, 'ThumbTouchMoved', this.maThumbnails[i]);	
		mover.Init();
	}
	
	this.mrContainer.AttachListener( 'ContainerResized', this, 'ContainerResized');
	this.ContainerResized();
	
	this.mInitialized	=	true;
 };

 
 JphThumbs_ThumbsApp.prototype._HtmlThumbs	=	function()
 {
 	var str	=	new Array();
	var cnt	=	0;
	
	for (var i=0; i<this.maThumbnails.length; i++)
		str[cnt++]	=	this.maThumbnails[i].Html();
	
	return str.join('');
 }
 
 JphThumbs_ThumbsApp.prototype._HtmlCount		=	function()
 {
	var count	=	this.mrApp.mrDao.maImages.length;
	var text	=	count + ' photos';
	if (count == 1)
		text	=	count + ' photo';
			
	return text;
 }
 
//EVENT LISTENERS
 JphThumbs_ThumbsApp.prototype.ContainerResized	=	function( e)
 {
  	
  	
// 	this.mhThumbsContainer.style.width = this.mrContainer.GetWidth() + 'px';
 	this.mhThumbsContainer.style.minHeight = this.mrContainer.GetHeight() + 'px';
 };

 // ACTIONS
 JphThumbs_ThumbsApp.prototype.Show			=	function()
 {
	this.mhThumbsTopBar.style.display		=	'block';
	this.mhThumbnails.style.display			=	'block';
	this.mhThumbsContainer.style.display	=	'block';
	
	this.mrPreloader.Play();
 };
 
 
 JphThumbs_ThumbsApp.prototype.Hide			=	function()
 {
	this.mhThumbsTopBar.style.display		=	'none';
	this.mhThumbnails.style.display			=	'none';
	this.mhThumbsContainer.style.display	=	'none';
	
	this.mrPreloader.Pause();
 };
 
 
 
 

function JphThumbs_Item( app, image)
 {
 	this.mrApp		=	app;
 	this.mrImage	=	image;
	this.mhDiv		=	null;
	this.mhImage	=	null;
 }
 
 JphThumbs_Item.prototype.Init		=	function()
 {
 	this.mhDiv		=	document.getElementById( this.GetHtmlId('thumb_div'));
 	this.mhImage	=	document.getElementById( this.GetHtmlId('thumb_img'));
 	this.mrApp.mrThumbnails.mrPreloader.Load( this.mhImage, this.mrImage.mSrcThumb);
 	
 	attach_method( this.mhImage, 'onload', this, '_ImageLoaded');
 };

 JphThumbs_Item.prototype._ImageLoaded = function( e)
 {
 	this.mhImage.style.display	=	'inline';
 };
 
 JphThumbs_Item.prototype.SelectSlide = function()
 {
	 this.mrApp.ShowSliderAction( this.mrImage.mIndex);
	 //this.mrApp.mrSlider.Show();
 };
 
 JphThumbs_Item.prototype.SelectThumb = function()
 {
 	this.mhDiv.style.opacity	=	'.50';
 };
 
 JphThumbs_Item.prototype.DeselectThumb = function()
 {
 	this.mhDiv.style.opacity	=	'1.0';
 };
 
 JphThumbs_Item.prototype.Html				=	function()
 {
 	var str		=	new Array();
	var cnt		=	0;
	
	str[cnt++]	=	'<div class="thumbnail"';
	str[cnt++]	=	get_html_attribute("id", this.GetHtmlId('thumb_div'));
	str[cnt++]	=	'>';
	str[cnt++]	=	'<img';
	str[cnt++]	=	get_html_attribute("id", this.GetHtmlId('thumb_img'));
	str[cnt++]	=	get_html_attribute('title', this.mrImage.mTitle);
	str[cnt++]	=	get_html_attribute('style', 'display: none;');
	str[cnt++]	=	'/>';
	str[cnt++]	=	'</div>';
	
	
	return str.join('');
 }
 
 
 JphThumbs_Item.prototype.GetHtmlId = function( key)
 {
 	return this.mrImage.mIndex + '_' + key;
 }
				
var TOOLS_MODE_ALL_HIDDEN	=	'ALL_HIDDEN';
 var TOOLS_MODE_TOOLBARS_ON	=	'TOOLBARS_ON';
 var TOOLS_MODE_TEXT_ON		=	'TEXT_ON';
 var TOOLS_MODE_BOTH_ON		=	'VISIBLE_ON';

 
 function JphThumbs_Behavior( thumbs)
 {
	this.mrThumbs	=	thumbs;
	
	this.mrLastSelectedThumb	=	null;
 }
 
 
 
 JphThumbs_Behavior.prototype.Init					=	function()
 {
 }

 
 JphThumbs_Behavior.prototype.ThumbTouched		=	function( e, thumbItem)
 {
	
	if (this.mrLastSelectedThumb)
	{
		this.mrLastSelectedThumb.DeselectThumb();
		this.mrLastSelectedThumb	=	null;
	}
	
	
	thumbItem.SelectThumb();
	
	this.mrLastSelectedThumb	=	thumbItem;
 };
 
 
 JphThumbs_Behavior.prototype.ThumbTouchMoved		=	function( e, thumbItem)
 {
	 
	 if (this.mrLastSelectedThumb)
	 {
		 this.mrLastSelectedThumb.DeselectThumb();
		 this.mrLastSelectedThumb	=	null;
	 }
 };
 
 
 JphThumbs_Behavior.prototype.ThumbSelected		=	function( e, thumbItem)
 {
	thumbItem.SelectSlide();
	thumbItem.DeselectThumb();
 }
	
function JphSlider_ToolbarsManager()
 {
	this.mDeactivation	=	null;
	this.maElements		=	new Array();
	
	this.mHidden		=	true;
 }
 
 JphSlider_ToolbarsManager.prototype.Register	=	function( elem)
 {
 	this.maElements[this.maElements.length]	=	elem;
 };
 
 JphSlider_ToolbarsManager.prototype.GetHeight	=	function()
 {
	 var height	=	0;
	 for (var i=0; i<this.maElements.length; i++)
		 height += this.GetSingleHeight();
	 return height;
 };
 
 JphSlider_ToolbarsManager.prototype.GetSingleHeight	=	function()
 {
	 return 40;
 };
 
 // ACTIONS
 JphSlider_ToolbarsManager.prototype.Show	=	function()
 {
	this._RemoveDeactivation();
		
	for (var i=0; i<this.maElements.length; i++)
		this.maElements[i].style.display	=	'block';
		
	this._SetDeactivation();
	
	this.mHidden	=	false;
 };
 
 JphSlider_ToolbarsManager.prototype.Hide	=	function()
 {
	this._DeactivateToolbars();
 };
  
 
 JphSlider_ToolbarsManager.prototype.IsHidden		=	function()
 {
	return this.mHidden;
 };
 
 JphSlider_ToolbarsManager.prototype._DeactivateToolbars	=	function()
 {
	this._RemoveDeactivation();
	
	for (var i=0; i<this.maElements.length; i++)
		this.maElements[i].style.display	=	'none';
		
	this.mHidden	=	true;
 };
  
 JphSlider_ToolbarsManager.prototype.Toggle	=	function()
 {
 	if (this.mHidden) 	
		this.Show();
	else
		this.Hide();
 };
 
 // TIMER
 JphSlider_ToolbarsManager.prototype._SetDeactivation	=	function()
 {
 	this.mDeactivation		=	set_timeout( this, '_DeactivateToolbars', '', TOOLBARS_HIDE_TIMEOUT);	
 };

 JphSlider_ToolbarsManager.prototype._RemoveDeactivation	=	function()
 {
 	if (this.mDeactivation)
		clearTimeout( this.mDeactivation);
		
	this.mDeactivation	=	null;
 };
			
function JphSlider_SwipeComponentCell( table, index)
{
	this.mrTable		=	table;
	this.mIndex			=	index;
	
	this.mhContainer	=	null;
	this.mrImage		=	null;
}

JphSlider_SwipeComponentCell.prototype.Init = function()
{


};
 
JphSlider_SwipeComponentCell.prototype.SetImage = function( image)
{
	this.mrImage	=	image;
};

JphSlider_SwipeComponentCell.prototype.GetHtmlId = function( key)
{
	return this.mrTable.GetHtmlId( 'slide', this.mIndex);
};

JphSlider_SwipeComponentCell.prototype.toString	=	function()
{
	return 'JphSlider_SwipeComponentCell['+this.mIndex+']';
};
			
function JphSlider_SwipeComponent( container, preloader, queue, image)
{
	this.DEFAULT_CELL_COUNT		=	3;
	
	this.mrContainer	=	container;
	
	this.mrAnimation	=	null;
	
	this.mhTable		=	null;
	this.mPotention		=	0;
	
	this.mrActiveSlide	=	null;
	this.maSlides		=	{};
}

JphSlider_SwipeComponent.prototype.Init = function()
{
	var factory						=	new JphUtil_AnimationFactory( this.mhSliderTable);
	this.mrAnimation				=	factory.GetAnimation();
	
	for (i=0; i<this.DEFAULT_CELL_COUNT; i++)
	{
		this.maSlides[i]	=	new JphSlider_SwipeComponentCell( this, i);
		this.maSlides[i].Init();
	}
};
 
// NAVIATION
JphSlider_SwipeComponent.prototype.Next = function()
{
	this._ExpandRight( false);
	this._SwipeBack();
};

JphSlider_SwipeComponent.prototype.Previous = function()
{
	
};

JphSlider_SwipeComponent.prototype._ExpandRight = function( reverse)
{
	var row		=	this.mhTable.rows[0];
	var cell	=	row.cells[0];
	
	row.appendChild( cell);
	
	// table swap places
		// first td -> last
	// inverse potention
};

JphSlider_SwipeComponent.prototype._SwipeBack = function()
{
	this.mrAnimation.SlideTo( 0);
	// webkit padding animationa
};

// EVENTS
// end boundary
// start boundary
// 


JphSlider_SwipeComponent.prototype.toString	=	function()
{
	return '[JphSlider_SwipeComponent]';
};
		
function JphSlider_SlideShow( slider)
 {
 	this.mrSlider		=	slider;
	this.mSlideTimeout	=	null;
 }
 
 JphSlider_SlideShow.prototype.IsActive	=	function()
 {
	if (this.mSlideTimeout)
		return true;
 	return false;
 }
 
 JphSlider_SlideShow.prototype.TogglePlay	=	function()
 {
	if (this.IsActive())
	{
	 	this.StopSlideshow();
	}
	else
	{
	 	this._RequestNext();
	}
	this.mrSlider.mrSliderNavi.CheckButons();
 }
 
 JphSlider_SlideShow.prototype.StopSlideshow	=	function()
 {
 	clearTimeout( this.mSlideTimeout);
	this.mSlideTimeout	=	null;	
 }
 
 JphSlider_SlideShow.prototype._RollSlideshow	=	function()
 {
 	this.mrSlider.mrSlidesComponent.Next();
	
	if (this.mrSlider.mrSlidesComponent.IsLast())
	{		
		this.StopSlideshow();
		this.mrSlider.mrSliderNavi.CheckButons();
		this.mrSlider.mrToolbars.Show();
	}
	
	if (this.IsActive())
	{
		this._RequestNext();		
	}
 };
 

 
 JphSlider_SlideShow.prototype._RequestNext	=	function()
 { 		
	this.mSlideTimeout		=	set_timeout( this, '_RollSlideshow', '', SLIDESHOW_ROLL_TIMEOUT);	
 }

function JphSlider_SlidesComponent( app, images, container)
 {
 	this.mrApp				=	app;
 	this.mrContainer		=	app.mrContainer;
 	this.maImages			=	images;
 	this.mhContainer		=	container;
 	
 	this.mhSliderTable		=	null;
	
	this.mCurrent			=	0;
	this.mrCurrentSlide		=	null;
	
	this.maSlides			=	new Array();
	
	this.mrBehavior			=	this.mrApp.mrSlider.mrBehavior;
	this.mrPreloader		=	this.mrApp.mrSlider.mrPreloader;
	this.mrImageQueue		=	this.mrApp.mrSlider.mrImageQueue;
	
	this.mReverse			=	false;
	
	this.mLeft				=	0;
	
	this.mrAnimation		=	null;
	
	
	implement_events( this);
 }
 

 JphSlider_SlidesComponent.prototype.Init		=	function()
 {
	
	var last_slide	=	null;
	for (var i=0; i<this.maImages.length; i++)
	{
		var image 	=	this.maImages[i];
		var slide	=	new JphSlider_Slide( this.mrContainer, this.mrPreloader, this.mrImageQueue, image);	
		this.maSlides[image.mIndex]	=	slide;
		
		if (last_slide != null)
		{
			last_slide.mrNext 	= 	slide;
			slide.mrPrevious	=	last_slide;	
		}
		
		last_slide	=	slide;
	} 	
	
	
	prepend_html( this.mhContainer, this._HtmlSlider());
	
	
	this.mhSliderTable				=	document.getElementById('slider-table');
	
	var factory						=	new JphUtil_AnimationFactory( this.mhSliderTable);
	this.mrAnimation				=	factory.GetAnimation();
	
//	this.mrAnimation				=	new JphSlider_Animation( this.mhSliderTable);
//	this.mrAnimation.Init();
	
	for (var i=0; i<this.maSlides.length; i++)
	{
		this.maSlides[i].Init();
	}
	
	
	this.mrCurrentSlide		=	this.maSlides[this.mCurrent];	
	
	var slider_mover		= 	new JphUtil_Touches( this.mhSliderTable, true);
	slider_mover.AttachListener( 'TouchEnd', this.mrBehavior, 'SlideTouched');
	slider_mover.AttachListener( 'MovedLeft', this.mrBehavior, 'SlideDraggedLeft');
	slider_mover.AttachListener( 'MovedRight', this.mrBehavior, 'SlideDraggedRight');
	slider_mover.AttachListener( 'Moving', this.mrBehavior, 'SlideDragging');
	slider_mover.AttachListener( 'MoveCancel', this, 'RepaintPosition');
	slider_mover.Init();

	this.mrContainer.AttachListener( 'ContainerResized', this, 'ContainerResized');
	this.ContainerResized();
	
	this.AttachListener( 'SlideChanged', this.mrBehavior, 'SlideChanged');
	
 };


 JphSlider_SlidesComponent.prototype._HtmlSlider		=	function()
 {
 	
 	var str		=	new Array();
	var cnt		=	0;
			
	str[cnt++]	=	'<table id="slider-table" cellspacing="0" cellpadding="0" style="position: absolute;">';
	str[cnt++]	=	'<tr>';
	for (var i=0; i<this.maSlides.length; i++)
	{
		str[cnt++]	=	this.maSlides[i].HtmlSlide();
	} 	
	str[cnt++]	=	'</tr>';
	str[cnt++]	=	'</table>';
	
	return str.join('');
 };
 
 
 /*******************************************/
 /*			SLIDER - NAVIGATION				*/
 /*******************************************/  
 
 // ACTINS
 
 JphSlider_SlidesComponent.prototype.Previous		=	function()
 {
 	if (this.IsFirst())
	{
 		// TODO: first reached event
//		this.mrToolbars.Show();
		return ;
	}
	
	this.mReverse	=	true;
	this.SelectSlide( this.mCurrent - 1);
 };
 
 JphSlider_SlidesComponent.prototype.Next			=	function()
 {
 	if (this.IsLast())
	{
 	// TODO: last reached event
//		this.mrToolbars.Show();
		return ;
	}
	
	this.mReverse	=	false;
	this.SelectSlide( this.mCurrent + 1);
 };

 JphSlider_SlidesComponent.prototype.SelectSlide		=	function( index)
 { 	
		
 	var last_slide			=	this.mrCurrentSlide;
 	this.mrCurrentSlide		=	this.maSlides[index];	
	this.mCurrent			=	index;

	if (!this.mrCurrentSlide)
		throw Error('Slide not found by index ['+index+']');

	this.mrCurrentSlide.SetActive();
	
	if (last_slide)
		last_slide.SetInactive();
	
	this.RepaintPosition();
	
	this.FireEvent('SlideChanged');
	
 };
 
// EVENT LISTENERS
JphSlider_SlidesComponent.prototype.ContainerResized	=	function( e)
{
 	
	this.mhSliderTable.style.height = 	this.mrContainer.GetHeight() + 'px';
	this.mhSliderTable.style.width	=	this._GetTotalWidth()+'px';
};

// INFO
 JphSlider_SlidesComponent.prototype.IsLast	=	function()
 {
 	if ((this.mCurrent + 1) == this.maImages.length)
		return true;	
	return false;
 };
 
 JphSlider_SlidesComponent.prototype.IsFirst	=	function()
 {
 	if (this.mCurrent == 0)
		return true;	
	return false;
 };
 
 
 // UTIL

 JphSlider_SlidesComponent.prototype.RepaintPosition	=	function( immediate)
 {
	this.mLeft	=	this._GetPositionLeft( this.mCurrent);
	
	if (typeof( immediate) == 'boolean' && immediate)
		immediate = true;
	else
		immediate = false;
	
	if (immediate)
	{
		this.mrAnimation.SetTo( this.mLeft);
	}
	else
	{
		this.mrAnimation.SlideTo( this.mLeft);
	}
 };
 
 JphSlider_SlidesComponent.prototype.MovePosition	=	function( move)
 {
	 var left	=	this.mLeft + move;
	 
	 if (left > MIN_DISTANCE_TO_BE_A_DRAG)
		 return;
	 
	 if (left <  - (this._GetTotalWidth() - this.mrContainer.GetWidth() + MIN_DISTANCE_TO_BE_A_DRAG))
		 return;	 
	 
	 this.mLeft	=	left;
	 
	 this.mrAnimation.SetTo( this.mLeft);
 };
 
 JphSlider_SlidesComponent.prototype._GetTotalWidth		=	function()
 {
	 var count	=	this.maImages.length;
	 var space	=	SLIDE_SPACE_WIDTH * (count - 1);
	 return count * this.mrContainer.GetWidth() + space;
 };
 
 JphSlider_SlidesComponent.prototype._GetPositionLeft	=	function( index)
 {
	 var width	=	
		 this.mrContainer.GetWidth() + SLIDE_SPACE_WIDTH;
	 return width * index * -1;
 };

	
function JphSlider_SliderControls( slider)
 {
 	this.mrSlider	=	slider;
	
 	this.mhInfo;
	
 	this.mrPrev;
	this.mrPlay;
	this.mrPause;
	this.mrNext;
 }
 
 JphSlider_SliderControls.prototype.Init		=	function()
 {
 	this.mhInfo		=	document.getElementById('navi-info');

	this.mrPrev		=	new Jph_NaviButton( 'navi-prev');
	this.mrPrev.Init();
	var mover		= 	new JphUtil_Touches( this.mrPrev.mhImage, false);
	mover.AttachListener( 'TouchEnd', this.mrSlider.mrBehavior, 'PreviousPressed');	
	mover.Init();

	
	this.mrPlay		=	new Jph_NaviButton( 'navi-play');
	this.mrPlay.Init();
	var mover		= 	new JphUtil_Touches( this.mrPlay.mhImage, false);
	mover.AttachListener( 'TouchEnd', this.mrSlider.mrBehavior, 'PlayPressed');	
	mover.Init();
	
	
	this.mrPause	=	new Jph_NaviButton( 'navi-pause');
	this.mrPause.Init();
	var mover		= 	new JphUtil_Touches( this.mrPause.mhImage, false);
	mover.AttachListener( 'TouchEnd', this.mrSlider.mrBehavior, 'PausePressed');	
	mover.Init();
	
		
	this.mrNext		=	new Jph_NaviButton( 'navi-next');
	this.mrNext.Init();
	var mover		= 	new JphUtil_Touches( this.mrNext.mhImage, false);
	mover.AttachListener( 'TouchEnd', this.mrSlider.mrBehavior, 'NextPressed');	
	mover.Init();
		
 };
 
 JphSlider_SliderControls.prototype.CheckButons = function()
 {
	
	if (this.mrSlider.mrSlideshow.IsActive())
	{
		this.mrPlay.Hide();
		this.mrPause.Show();
	}
	else
	{
		this.mrPlay.Show();
		this.mrPause.Hide();
	}
	
	if (this.mrSlider.mrSlidesComponent.IsFirst())
		this.mrPrev.Disable();
	else
		this.mrPrev.Enable();

	if (this.mrSlider.mrSlidesComponent.IsLast())
		this.mrNext.Disable();
	else
		this.mrNext.Enable();
 };


function JphSlider_SliderApp( app)
 {
 	this.mrApp				=	app;
 	this.mrContainer		=	this.mrApp.mrContainer;
 	this.mhSliderDiv		=	null;
 	this.mrSlidesComponent	=	null;
 	
 	
	this.mrToolbars			=	null;	
	this.mrSliderNavi		=	null;
	this.mrSlideshow		=	null;
	this.mrCompnents		=	null;
	
	
	this.mhTopBar			=	null;
	this.mhBottomBar		=	null;
	
	this.mrPreloader		=	null;
	this.mrImageQueue		=	null;	
	this.mrBehavior			=	null;
 }
 

 JphSlider_SliderApp.prototype.Init		=	function()
 {
	
 	// temp solution - mrContainer should be enough
 	this.mhSliderDiv		=	document.getElementById('slider-container');
 	
	this.mhTopBar			=	document.getElementById('slider-toolbar-top');
	this.mhBottomBar		=	document.getElementById('slider-toolbar-bottom');
 	
	this.mrPreloader		=	new JphUtil_Preloader( MAX_CONCURENT_LOADING_SLIDE);
	this.mrImageQueue		=	new JphSlider_ImageQueue( SLIDE_MAX_IMAGE_ELEMENS);	
 	
	this.mrBehavior			=	new JphSlider_Behavior( this);
	this.mrBehavior.Init();
	
	this.mrSlidesComponent	=	new JphSlider_SlidesComponent( this.mrApp, this.mrApp.mrDao.maImages, this.mhSliderDiv);
	this.mrSlidesComponent.Init();
	
	this.mrSlideshow		=	new JphSlider_SlideShow( this);
	
	this.mrSliderNavi		=	new JphSlider_SliderControls( this);
	this.mrSliderNavi.Init();
	
	this.mrToolbars			=	new JphSlider_ToolbarsManager();
	this.mrToolbars.Register( this.mhTopBar);
	this.mrToolbars.Register( this.mhBottomBar);
	
	
	this.mrDescription		=	new JphSlider_Description( this.mrApp, this.mrToolbars.GetSingleHeight());
	this.mrDescription.Init();
	
	
	this.mrCompnents		=	new JphSlider_ComponentVisibility( this);
	
	// TODO: refactor
	var top_tool_touch		= 	new JphUtil_Touches( this.mhTopBar, false);
	top_tool_touch.AttachListener( 'TouchEnd', this.mrBehavior, 'ToolbarTouched');
	top_tool_touch.Init();
	
	var bottom_tool_touch	= 	new JphUtil_Touches( this.mhBottomBar, false);
	bottom_tool_touch.AttachListener( 'TouchEnd', this.mrBehavior, 'ToolbarTouched');
	bottom_tool_touch.Init();
	
	var text_mover			= 	new JphUtil_Touches( this.mrDescription.mhDescTitle, true);
	text_mover.AttachListener( 'TouchEnd', this.mrBehavior, 'DescriptionTouched');	
	text_mover.Init();
	
	var title_mover			= 	new JphUtil_Touches( this.mrDescription.mhDescText, true);
	title_mover.AttachListener( 'TouchEnd', this.mrBehavior, 'DescriptionTouched');	
	title_mover.Init();
	
	
	var slider_mover		= 	new JphUtil_Touches( this.mrDescription.mhDescContainer, true);
	slider_mover.AttachListener( 'TouchEnd', this.mrBehavior, 'SlideTouched');
	slider_mover.AttachListener( 'MovedLeft', this.mrBehavior, 'SlideDraggedLeft');
	slider_mover.AttachListener( 'MovedRight', this.mrBehavior, 'SlideDraggedRight');
	slider_mover.AttachListener( 'Moving', this.mrBehavior, 'SlideDragging');
	slider_mover.AttachListener( 'MoveCancel', this.mrSlidesComponent, 'RepaintPosition');
	slider_mover.Init();
	

	this.mrContainer.AttachListener( 'ContainerResized', this, 'ContainerResized');
	this.ContainerResized();
	
 };


 /*******************************************/
 /*			SLIDER - NAVIGATION				*/
 /*******************************************/  
 
 // ACTINS
 JphSlider_SliderApp.prototype.Hide			=	function()
 {
		
	this.mhSliderDiv.style.display			=	'none';
		
	this.mrDescription.Hide();
	this.mrToolbars.Hide();
 };
 
 JphSlider_SliderApp.prototype.Show			=	function()
 {
	
	this.mhSliderDiv.style.display			=	'block';
	this.mrToolbars.Show();
	
	this.mrCompnents.Show();
 };
 
 JphSlider_SliderApp.prototype.IsVisible		=	function()
 {
	 
	 if (this.mhSliderDiv.style.display == 'block')
		 return true;
	 return false;
 };
 JphSlider_SliderApp.prototype.HideTable		=	function()
 {
		
 	this.mrSlidesComponent.mhSliderTable.style.display			=	'none';
 };
 
 JphSlider_SliderApp.prototype.ShowTable		=	function()
 {
	
	this.mrSlidesComponent.mhSliderTable.style.display			=	'block';
 };
 
 
 JphSlider_SliderApp.prototype.SetDefaultSlide		=	function( index)
 { 	
	 this.mrSlidesComponent.SelectSlide( index);
 };
 
// EVENT LISTENERS
JphSlider_SliderApp.prototype.ContainerResized	=	function( e)
{
 	
 	
	this.mhSliderDiv.style.width = this.mrContainer.GetWidth() + 'px';
	this.mhSliderDiv.style.height = this.mrContainer.GetHeight() + 'px';
	
	var padding			=	this.mrToolbars.GetHeight(); // top & bottom
	var padded_height	=	this.mrContainer.GetHeight() - padding;
	this.mrDescription.mhDescContainer.style.height = 	padded_height + 'px';
};


 // REPAINT
 JphSlider_SliderApp.prototype.RepaintInfo		=	function()
 {
 	var count	=	this.mrApp.mrDao.GetImagesCount();
 	var current	=	this.mrSlidesComponent.mCurrent + 1;
	this.mrSliderNavi.mhInfo.innerHTML	=	current + ' of ' + count;
 };
 
			
function JphSlider_Slide( container, preloader, queue, image)
 {
	this.mrContainer	=	container;
 	this.mrPreloader	=	preloader;
 	this.mrImageQueue	=	queue;
 	this.mrImage		=	image;
 	
	this.mActive		=	false;
	this.mrPrevious		=	null;
	this.mrNext			=	null;
	
	this.mhTd			=	null;
	
	this.maNeighboursDefault	=	new Array();
	this.maNeighboursReverse	=	new Array();
	
	this.mPreloadTimeoutId		=	null;
 }

 JphSlider_Slide.prototype.Init = function()
 {
 	this.mhTd			=	document.getElementById( this.GetHtmlId('td'));

 	// 	
 	var indexes			=	SLIDE_PRELOAD_SEQUENCE.split(',');
 	for (var i=0; i<indexes.length; i++)
 	{
 		var distance			=	parseInt( indexes[i]);
 		var sequence_slide		=	this.GetSibling( distance);
 		if (sequence_slide)
 			this.maNeighboursDefault[this.maNeighboursDefault.length]	=	sequence_slide;
 			
 		var sequence_slide		=	this.GetSibling( -distance);
 		if (sequence_slide)
 			this.maNeighboursReverse[this.maNeighboursReverse.length]	=	sequence_slide;
 	}
 	
 	this.mrContainer.AttachListener( 'ContainerResized', this, 'ContainerResized');
 	this.ContainerResized();
 	
 };
 
//EVENT LISTENERS
 JphSlider_Slide.prototype.ContainerResized	=	function( e)
 {
  	
  	
 	this.mhTd.style.width = this.mrContainer.GetWidth() + 'px';
 	this.mhTd.style.height = this.mrContainer.GetHeight() + 'px';
 	
 	this.mhTd.style.maxWidth = this.mrContainer.GetWidth() + 'px';
 	this.mhTd.style.maxHeight = this.mrContainer.GetHeight() + 'px';
  	
 };

 JphSlider_Slide.prototype.HtmlSlide		=	function()
 {
 	var str		=	new Array();
	var cnt		=	0;
	
	str[cnt++]	=	'<td class="slide" valign="middle"';
	str[cnt++]	=	get_html_attribute('id', this.GetHtmlId('td'));
	str[cnt++]	=	'>';
	str[cnt++]	=	'</td>';
	
	if (!this.IsLast() && SLIDE_SPACE_WIDTH > 0)
	{
		str[cnt++]	=	'<td';
		str[cnt++]	=	get_html_attribute('width', SLIDE_SPACE_WIDTH);
		str[cnt++]	=	'>';
		str[cnt++]	=	'</td>';	
	}
				
	return str.join('');
 };
 
 
 JphSlider_Slide.prototype.SetInactive = function()
 {
 	
 }; 
 
 JphSlider_Slide.prototype.SetActive = function()
 {

	this._Load();
	
 	if (this.mPreloadTimeoutId)
 	{
 		clearTimeout( this.mPreloadTimeoutId);
 		this.mPreloadTimeoutId	=	null;
 	}
 	
	this.mPreloadTimeoutId	=	set_timeout( this, '_PrepaireNeighbours', this.mReverse ? 'true' : '', SLIDE_PRELOAD_TIMEOUT);
 }; 
 
 JphSlider_Slide.prototype._PrepaireNeighbours = function( strReverse)
 {
 	var reverse		=	strReverse == 'true' ? true : false;
 	var slides		=	reverse ? this.maNeighboursReverse : this.maNeighboursDefault;
 	
 	for (var i=0; i<slides.length; i++)
 		slides[i]._Load();
 };
 
 JphSlider_Slide.prototype._Load = function()
 {
	this.mrPreloader.Load( this._GetImage(), this.mrImage.mSrc);
 }; 
 
 JphSlider_Slide.prototype._GetImage = function()
 {
 	var img		=	this.mhTd.childNodes[0];
 	
 	if (img)
 	{
 		this.mrImageQueue.RegisterUsage( img); 		
 	}
 	else
 	{
		img		=	this.mrImageQueue.GetImage();
		this.mhTd.appendChild( img);
 	}
 	
 	return img;
 };
 
 JphSlider_Slide.prototype.IsLast = function()
 {
	if (this.mrNext == null)
		return true;
 };
 
 JphSlider_Slide.prototype.IsFirst = function()
 {
	if (this.mrPrevious == null)
		return true;
 }; 

 JphSlider_Slide.prototype.GetSibling = function( distance)
 {
	if (distance == 0)
		return this;
	if (this.mrNext && distance > 0)
		return this.mrNext.GetSibling( distance - 1);
	if (this.mrPrevious && distance < 0)
		return this.mrPrevious.GetSibling( distance + 1);		
 }; 

 
 JphSlider_Slide.prototype.GetHtmlId = function( key)
 {
 	return 'slide_' + this.mrImage.mIndex + '_' + key;
 };
 
 JphSlider_Slide.prototype.toString	=	function()
 {
 	return '[JphSlider_Slide ['+this.mrImage.mIndex+']]';
 };
				
function JphSlider_ImageQueue( queueSize)
 {
 	this.maQueueSize	=	queueSize;
 	this.maLruQueue		=	new Array();
 }
 
 JphSlider_ImageQueue.prototype.GetImage	=	function()
 {
	
	if (this._IsFull())
	{
		return this._ReuseImage();
	}	
	
	return this._CreateImage();
 }
 
 
 JphSlider_ImageQueue.prototype.RegisterUsage	=	function( img)
 {
	
	var arr	=	new Array();
	
	for (var i=0; i<this.maLruQueue.length; i++)
		if (this.maLruQueue[i] != img)
			arr[arr.length]	=	this.maLruQueue[i];
			
	this.maLruQueue	=	arr;
	this.maLruQueue[this.maLruQueue.length]	=	img;
 }
  
 
 JphSlider_ImageQueue.prototype._ReuseImage	=	function()
 {
 	var img	=	this.maLruQueue.shift();
 	img.parentNode.removeChild( img);
 	this.maLruQueue[this.maLruQueue.length]	=	img;
 	return img;
 }
 
 JphSlider_ImageQueue.prototype._CreateImage	=	function()
 {
 	var img	=	document.createElement('img');
 	img.style.maxWidth = 'inherit';
 	img.style.maxHeight = 'inherit';
 	this.maLruQueue[this.maLruQueue.length]	=	img;
 	return img; 
 };
 
 JphSlider_ImageQueue.prototype._IsFull	=	function( src)
 {
 	if (this.maLruQueue.length >= this.maQueueSize)
 		return true;
 }

 JphSlider_ImageQueue.prototype.toString	=	function()
 {
 	return 'JphSlider_ImageQueue [length='+this.maLruQueue.length+'][max-size='+this.maQueueSize+']';
 }
				
function JphSlider_Description( app, paddingTop)
 {
 	this.mrApp				=	app;
 	this.mPaddingTop		=	paddingTop;
	
	this.mhDescContainer	=	null;
	this.mhDescTitle		=	null;
	this.mhDescText			=	null;
	
	this.mHidden			=	false;
 }
 

 JphSlider_Description.prototype.Create		=	function()
 {
 }
 
 
 JphSlider_Description.prototype.Init		=	function()
 {
	
	var tmp	=	document.createElement('div');
	tmp.innerHTML			=	this.Html();
	document.body.appendChild( tmp);
	
	this.mhDescContainer	=	document.getElementById('slider-desc-container');
	this.mhDescTitle		=	document.getElementById('desc-title');
	this.mhDescText			=	document.getElementById('desc-text');
	
	this.mhDescContainer.style.top		=	this.mPaddingTop + 'px';
 };
 
 JphSlider_Description.prototype.Html = function()
 {
 	var str		=	new Array();
	var cnt		=	0;
	
	str[cnt++]	=	'<table id="slider-desc-container" class="slider-text" border="0">';
	str[cnt++]	=	'<tr>';
	str[cnt++]	=	'	<td valign="bottom">';
	str[cnt++]	=	'		<div id="desc-title">';
	str[cnt++]	=	'		</div>';
	str[cnt++]	=	'		<div id="desc-text">';
	str[cnt++]	=	'		</div>';
	str[cnt++]	=	'	</td>';
	str[cnt++]	=	'</tr>';
	str[cnt++]	=	'</table>';
				
	return str.join('');
 }
 
 JphSlider_Description.prototype._OnTouch		=	function( e)
 {
	this.mrApp.mrSlider.mrToolbars.Deactivate();
 }
 
 JphSlider_Description.prototype.IsHidden		=	function()
 {
	return this.mHidden;
 }
 
 JphSlider_Description.prototype.Hide			=	function()
 {
	this.mhDescContainer.style.display	=	'none';
	this.mHidden						=	true;
 }
 
 JphSlider_Description.prototype.Show			=	function()
 {
 	try
 	{
		this.mhDescContainer.style.display	=	'table';
	}
	catch(e)
	{
		this.mhDescContainer.style.display	=	'block';
	}
	this.mHidden						=	false;
 };
 

 JphSlider_Description.prototype.SetDescription		=	function( title, desc)
 {
	this.mhDescTitle.innerHTML	=	title;
	this.mhDescText.innerHTML	=	desc;
 };
 
				
function JphSlider_ComponentVisibility( slider)
 {
 	this.mrSlider			=	slider;
	
	// defaults
	this.mTextAllreadyUsed	=	false;
	this.mTextAvailable		=	true;
	this.mTextVisible		=	true;
	this.mToolsVisible		=	true;
 }


 JphSlider_ComponentVisibility.prototype.HideAll = function()
 {
	this.mrSlider.mrToolbars.Hide();
	this.mrSlider.mrDescription.Hide();
 }
 
 JphSlider_ComponentVisibility.prototype.Show = function()
 {
	this.mrSlider.mrToolbars.Show();
	this.mrSlider.mrDescription.Show();
 }
  
 JphSlider_ComponentVisibility.prototype.ToggleAll = function()
 {

 	this._Refresh();
 	
 	
	if (this.mToolsVisible)
	{
		this.mrSlider.mrToolbars.Hide();
		this.mrSlider.mrDescription.Hide();
		
		if (this.mTextAvailable)
		{
			this.mTextAllreadyUsed	=	true;
		}
	}
	else
	{
		this.mrSlider.mrToolbars.Show();
		
		if (this.mTextAvailable)
		{
			this.mrSlider.mrDescription.Show();
		}
	}
 }
  
 JphSlider_ComponentVisibility.prototype.FirstTimeTextFix = function()
 {
 	this._Refresh();
	var force_desc	=	!this.mTextAllreadyUsed && this.mTextAvailable;
	
	
	if (force_desc)
		this.mrSlider.mrDescription.Show();
 }
  
 JphSlider_ComponentVisibility.prototype.Roll				=	function()
 {
	this._Refresh();
	
	if (this.mTextAvailable)
	{
		if (!this.mTextAllreadyUsed)
		{
			this.mrSlider.mrDescription.Show();
		}
		
		if (this.mTextVisible && this.mToolsVisible)
		{
			this.mrSlider.mrToolbars.Hide();
		}
		else if ( this.mTextVisible)
		{
			this.mrSlider.mrDescription.Hide();
		}
		else if ( !this.mTextVisible && !this.mToolsVisible)
		{
			this.mrSlider.mrToolbars.Show();
			this.mrSlider.mrDescription.Show();
		}
		else
		{
			this.mrSlider.mrToolbars.Show();
			this.mrSlider.mrDescription.Show();
		}
		
		this.mTextAllreadyUsed	=	true;
	}
	else
	{
		if (this.mToolsVisible)
		{
			this.mrSlider.mrToolbars.Hide();
		}
		else
		{
			this.mrSlider.mrToolbars.Show();
		}
	}
 }
 
 JphSlider_ComponentVisibility.prototype._Refresh 		= 	function()
 {
 	this.mTextAvailable	=	this.mrSlider.mrSlidesComponent.mrCurrentSlide.mrImage.HasText();
	
	if (this.mrSlider.mrToolbars.IsHidden())
		this.mToolsVisible	=	false;
	else
		this.mToolsVisible	=	true;
	
	if (this.mrSlider.mrDescription.IsHidden())
		this.mTextVisible	=	false;
	else
		this.mTextVisible	=	true;
		
	if (!this.mTextAvailable)
	{
		this.mTextVisible		=	true;
	}
 }
				
var TOOLS_MODE_ALL_HIDDEN	=	'ALL_HIDDEN';
 var TOOLS_MODE_TOOLBARS_ON	=	'TOOLBARS_ON';
 var TOOLS_MODE_TEXT_ON		=	'TEXT_ON';
 var TOOLS_MODE_BOTH_ON		=	'VISIBLE_ON';

 
 function JphSlider_Behavior( slider)
 {
	this.mrSlider	=	slider;
 }
 
 
 
 JphSlider_Behavior.prototype.Init					=	function()
 {
	this.mrSlider.mrApp.mrOrientation.AttachListener( 'OrientationChanged', this, '_OrientationChanged');
 };
 
 // DRAG
 JphSlider_Behavior.prototype.SlideDraggedLeft		=	function( e)
 {
	
 	if (this.mrSlider.mrSlideshow.IsActive())
	 	this.mrSlider.mrSlideshow.StopSlideshow();
 	this.mrSlider.mrSlidesComponent.Next();
 };
 
 JphSlider_Behavior.prototype.SlideDraggedRight		=	function( e)
 {
	
 	if (this.mrSlider.mrSlideshow.IsActive())
	 	this.mrSlider.mrSlideshow.StopSlideshow();
		
	this.mrSlider.mrSlidesComponent.Previous();
 };
 
 JphSlider_Behavior.prototype.SlideDragging		=	function( e)
 {
	 
	 if (this.mrSlider.mrSlideshow.IsActive())
		 this.mrSlider.mrSlideshow.StopSlideshow();
	 
//	 this.mrSlider.SetPosition( e.mScrollX);
	 this.mrSlider.mrSlidesComponent.MovePosition( e.mScroll);
 };

 // TOUCH
 JphSlider_Behavior.prototype.SlideTouched			=	function( e)
 {
	
	this.mrSlider.mrCompnents.ToggleAll();
 };
  
 JphSlider_Behavior.prototype.DescriptionTouched	=	function( e)
 {
	this.mrSlider.mrCompnents.Roll();
	if (e.mrEvent)
		e.mrEvent.cancelBubble = true;
 };
  
 JphSlider_Behavior.prototype.ToolbarTouched		=	function( e)
 {
 	this.mrSlider.mrToolbars.Show();
 };

 // BUTTONS
 JphSlider_Behavior.prototype.NextPressed			=	function( e)
 {
	
 	if (this.mrSlider.mrSlideshow.IsActive())
	 	this.mrSlider.mrSlideshow.StopSlideshow();
		
 	this.mrSlider.mrSlidesComponent.Next();
 };
 
 JphSlider_Behavior.prototype.PreviousPressed		=	function( e)
 {
	
 	if (this.mrSlider.mrSlideshow.IsActive())
	 	this.mrSlider.mrSlideshow.StopSlideshow();
		
 	this.mrSlider.mrSlidesComponent.Previous();
 };
 
 JphSlider_Behavior.prototype.PlayPressed			=	function( e)
 {
	
	this.mrSlider.mrSlideshow.TogglePlay();
 }
 
 JphSlider_Behavior.prototype.PausePressed			=	function( e)
 {
	
	this.mrSlider.mrSlideshow.TogglePlay();
 }
 
 JphSlider_Behavior.prototype.SlideChanged		=	function( e)
 {

 	this.mrSlider.RepaintInfo();
 	this.mrSlider.mrSliderNavi.CheckButons();
 	this.mrSlider.mrApp.NormalizeVertical();
 	
 	var slide	=	this.mrSlider.mrSlidesComponent.mrCurrentSlide;
 	
	this.mrSlider.mrDescription.SetDescription( slide.mrImage.mTitle, slide.mrImage.mDesc);
//	this.mrSlider.mrCompnents.FirstTimeTextFix();
	
	this.mrSlider.mrApp.mrHistory.SelectSlide( this.mrSlider.mrSlidesComponent.mCurrent);
	
	if (slide.IsFirst() || slide.IsLast())
		this.mrSlider.mrToolbars.Show();
	else
		this.mrSlider.mrToolbars.Hide();
 }; 
 
 // ORIENTATION
 JphSlider_Behavior.prototype._OrientationChanged	=	function( e)
 {
	
	if (this.mrSlider.IsVisible())
	{
		this.mrSlider.HideTable();
		set_timeout( this.mrSlider, 'ShowTable', '', 100);
	}	
	this.mrSlider.mrSlidesComponent.RepaintPosition( true);
 };
 
 
				
function Jph_SafariHistory( app)
{
	this.mrApp	=	app;
	
	this.mStartupMode		=	null;
	this.mMode				=	null;
	this.mIndex				=	0;
	this.mFirtsTimeSlider	=	true;
	
	this.mTrackValue		=	'';
	this.mTrackPattern		=	'/?nats';	
	
	implement_events( this);
}

Jph_SafariHistory.prototype.Init = function(index)
{
	this._CheckLoacation();
	set_interval( this, '_CheckLoacation', '', 500);
}

 Jph_SafariHistory.prototype._CheckLoacation = function()
 {
 	var last_index	=	this.mIndex;	
 	var last_mode	=	this.mMode;	
	
	this._Refresh();

	if (last_index != this.mIndex || last_mode != this.mMode)
	{
	//	this.FireEvent('LocationChanged');
	}
 }	

Jph_SafariHistory.prototype._Refresh = function(index)
{
	var hash = document.location.hash;
	var parts	=	hash.split( this.mTrackPattern);
	if (parts.length > 1)
	{
		this.mTrackValue	=	this.mTrackPattern + parts[1];
		hash				=	parts[0];
	}

	hash = hash.replace('#', '');
	
	if (hash) 
	{
		var args = hash.split('-');
		if (args.length > 1) {
			this.mMode = args[0];
			this.mIndex = parseInt( args[1]);
		}
		else 
		{
			var value = args[0];
			
			if (value == parseInt(value)) 
			{
				this.mMode	=	GALLERY_STARTUP_SLIDER;
				this.mIndex = 	parseInt( value);
			}
			else 
				this.mMode = value;
		}
	}
	else {
		this.mMode = this.mrApp.mDefaultMode;
		this.mIndex = 0;
	}
	
	if (this.mStartupMode == null)
		this.mStartupMode	=	this.mMode;
}
	

Jph_SafariHistory.prototype.SelectSlide	=	function( index)
{
	if (this.mStartupMode == GALLERY_STARTUP_THUMBNAILS &&
	 this.mFirtsTimeSlider)
	 {
	 //	document.location.hash	=	index + this.mTrackValue;
		this.mFirtsTimeSlider	=	false;
	 }
	 else
	 {
	 //	document.location.replace( '#' + index + this.mTrackValue);
	 }
	 
	this.mMode 	= 	GALLERY_STARTUP_SLIDER;
	this.mIndex = 	index;
}

Jph_SafariHistory.prototype.SelectThumbnails	=	function()
{
	this.mMode 	= 	GALLERY_STARTUP_THUMBNAILS;
	this.mIndex = 	0;
	
	// document.location.hash	=	'thumbs' + this.mTrackValue;
}
			
function Jph_NaviButton( htmlId)
 {
	this.mHtmlId	=	htmlId;
	this.mhImage;
 }
 
 Jph_NaviButton.prototype.Init		=	function()
 {
	this.mhImage	=	document.getElementById( this.mHtmlId);
 }
 
 Jph_NaviButton.prototype.Hide		=	function()
 {
 	this.mhImage.style.display	=	'none';
 }
 Jph_NaviButton.prototype.Show		=	function()
 {
 	this.mhImage.style.display	=	'block';
 }
 Jph_NaviButton.prototype.Disable	=	function()
 {
 	this.mhImage.style.opacity	=	'0.30';
 }
 Jph_NaviButton.prototype.Enable	=	function()
 {
 	this.mhImage.style.opacity	=	'1.00';
 }			
function Jph_Image( index, src, thumbSrc, title, desc)
{
 	if (title==undefined)
		title =	'';
 	if (desc==undefined)
		desc =	'';
			
 	this.mIndex		=	index;
 	this.mSrcThumb	=	thumbSrc;
	this.mSrc		=	src;
	this.mTitle		=	title;	
	this.mDesc		=	desc;	
	
}
 
 
Jph_Image.prototype.HasText		=	function()
{
 	if (this.mTitle == '' && this.mDesc == '')
		return false;
	return true;
};
				
function Jph_History( app)
{
	this.mrApp	=	app;
	
	this.mStartupMode		=	null;
	this.mMode				=	null;
	this.mIndex				=	0;
	this.mFirtsTimeSlider	=	true;
	
	this.mTrackValue		=	'';
	this.mTrackPattern		=	'/?nats';	
	
	implement_events( this);
}

Jph_History.prototype.Init = function()
{
	this._CheckLoacation();
	set_interval( this, '_CheckLoacation', '', 500);
};

 Jph_History.prototype._CheckLoacation = function()
 {
 	var last_index	=	this.mIndex;	
 	var last_mode	=	this.mMode;	
	
	this._Refresh();

	if (last_index != this.mIndex || last_mode != this.mMode)
	{
		this.FireEvent('LocationChanged');
	}
 };

Jph_History.prototype._Refresh = function(index)
{
	var hash 	= 	document.location.hash;
	var parts	=	hash.split( this.mTrackPattern);
	if (parts.length > 1)
	{
		this.mTrackValue	=	this.mTrackPattern + parts[1];
		hash				=	parts[0];
	}

	hash = hash.replace('#', '');
	
	if (hash) 
	{
		var args = hash.split('-');
		if (args.length > 1) {
			this.mMode = args[0];
			this.mIndex = parseInt( args[1]);
		}
		else 
		{
			var value = args[0];
			
			if (value == parseInt(value)) 
			{
				this.mMode	=	GALLERY_STARTUP_SLIDER;
				this.mIndex = 	parseInt( value);
			}
			else 
				this.mMode = value;
		}
	}
	else {
		this.mMode = this.mrApp.mDefaultMode;
		this.mIndex = 0;
	}
	
	if (this.mStartupMode == null)
		this.mStartupMode	=	this.mMode;
};
	

Jph_History.prototype.SelectSlide	=	function( index)
{
	if (this.mStartupMode == GALLERY_STARTUP_THUMBNAILS &&
	 this.mFirtsTimeSlider)
	 {
	 	document.location.hash	=	index + this.mTrackValue;
		this.mFirtsTimeSlider	=	false;
	 }
	 else
	 {
	 	document.location.replace( '#' + index + this.mTrackValue);
	 }
	 
	this.mMode 	= 	GALLERY_STARTUP_SLIDER;
	this.mIndex = 	index;
};

Jph_History.prototype.SelectThumbnails	=	function()
{
	this.mMode 	= 	GALLERY_STARTUP_THUMBNAILS;
	this.mIndex = 	0;
	
	document.location.hash	=	'thumbs' + this.mTrackValue;
};
				
function Jph_Dao()
 {
 	this.maImages				=	new Array();
 }
 
 Jph_Dao.prototype.AddImage 	= function( img)
 {
 	this.maImages[img.mIndex]	=	img;
 } 
 
 Jph_Dao.prototype.ReadImages	=	function()
{
	var i=0;
	do
	{
		var id			=	arguments[i++];
		var src			=	arguments[i++];
		var src_thumb	=	arguments[i++];
		var title		=	arguments[i++];
		var desc		=	arguments[i++];
		
		this.AddImage( new Jph_Image( id, src, src_thumb, title, desc));	
	}
	while (i<arguments.length) 
}

 Jph_Dao.prototype.ReadImage		=	function( id, src, thumbSrc, title, desc)
{
// function Jph_Image( index, src, thumbSrc, title, desc)	
	
	var obj			=	new Jph_Image();
	obj.mIndex		=	id;
 	obj.mSrcThumb	=	thumbSrc;
	obj.mSrc		=	src;
	obj.mTitle		=	title;	
	obj.mDesc		=	desc;	

	this.AddImage( obj);	
}

 Jph_Dao.prototype.GetImage			=	function( url)
 {
	 for (var i=0; i<this.maImages.length; i++)
	{
		var image		=	this.maImages[i];
		if ( image.src == url)
			return image;
	}

	throw new Error('Image [' + url + '] not found');
 }
 Jph_Dao.prototype.GetImagesCount = function()
 {
 	return this.maImages.length;
 }
				
function get_html_attribute( name, value)
 {
	var str			=	new Array();
	str[str.length]	=	' ';
	str[str.length]	=	name;
	str[str.length]	=	'="';
	str[str.length]	=	value;
	str[str.length]	=	'"';
	return str.join('');
 }
 
function set_timeout( obj, method, argsString, timeout)
{
	var id		=	setTimeout(				
		function() 								
		{										
			obj[method]( argsString);
		}, 										
		timeout);								
	return id;
} 

function set_interval( obj, method, argsString, timeout)
{
	var id		=	setInterval(
		function() 					
		{							
			obj[method]( argsString);
		}, 									
		timeout);							
	return id;
} 

function attach_method( master, eventName, obj, method)
{
	if (master == null || master == undefined)
		throw new Error('Empty master object passed ['+eventName+']['+obj+']['+method+']');
	if (eventName.indexOf('on') == 0)
		eventName	=	eventName.substring( 2);
	master.addEventListener( 
			eventName, 
			function( event) 						
			{										
				obj[method]( event);				
			},
			false);
											
//		master[eventName] = 					
//			function( event) 						
//			{										
//			obj[method]( event);				
//			};										
}

function append_html( elem, html)
{
	var div	=	document.createElement('div');
	div.innerHTML	=	html;
	
	for (var i=0; i<div.childNodes.length; i++)
	{
		elem.appendChild( div.childNodes[i]);
	}
}

function prepend_html( elem, html)
{
	var div	=	document.createElement('div');
	div.innerHTML	=	html;
	
	for (var i=0; i<div.childNodes.length; i++)
	{
		if (elem.childNodes.length)
		{
			elem.insertBefore( div.childNodes[i], elem.childNodes[0]);
		}
		else
		{
			elem.appendChild( div.childNodes[i]);
		}
	}
}









				
 var GALLERY_STARTUP_THUMBNAILS	=	'thumbs';
 var GALLERY_STARTUP_SLIDER		=	'slider';
 var GALLERY_STARTUP_SLIDE_SHOW	=	'slideshow';

function Jph_Application( dao)
{
	this.mrDao				=	dao;	
	this.mrOrientation		=	null;
	this.mrContainer		=	null;
	
	this.mrContainerFactory	=	new JphUtil_JaiphoContainerFactory();
	
	this.mrSlider			=	null;
	this.mrThumbnails		=	null;

	this.mrHistory			=	null;
	
	
	this.mDefaultMode		=	DEFAULT_STARTUP_MODE;
	
	this.mVerticalTimeout	=	null;
	
}

/*******************************************/
/*			INIT							*/
/*******************************************/
Jph_Application.prototype.Init		=	function()
{
 	
	 this.mrContainer	=	this.mrContainerFactory.GetContainer();
	 
	 this.mrOrientation	=	new JphUtil_OrientationManager( this.mrContainer);
//		this.mrOrientation.LockWidthAt( 320);
	 this.mrOrientation.Init();	 
	 
	 
//	this.mrSlider		=	new JphSlider_SliderApp( this);
//	this.mrSlider.Init();
//	
//	this.mrThumbnails	=	new JphThumbs_ThumbsApp( this);
//	this.mrThumbnails.Init();
//	
	if (ENABLE_SAFARI_HISTORY_PATCH)
		this.mrHistory		=	new Jph_SafariHistory( this);
	else
		this.mrHistory		=	new Jph_History( this);
		
	this.mrHistory.Init();
	
	this.mrHistory.AttachListener( 'LocationChanged', this, '_OnLocationChanged');
	
	var back	=	document.getElementById('slider-back-button');
	if (back)
	{
		var mover	= new JphUtil_Touches( back, true);
//		mover.AttachListener( 'TouchStart', this.mrBehavior, 'ThumbTouched', this.maThumbnails[i]);	
		mover.AttachListener( 'TouchEnd', this, 'ShowThumbsAction');	
//		mover.Init();
	}
	
//	attach_method( back, 'onclick', this, 'ShowThumbsAction');
};
 
 Jph_Application.prototype.Run		=	function()
 {
	 	
	if (GALLERY_STARTUP_THUMBNAILS == this.mrHistory.mMode)
	{
		this.ShowThumbsAction();
	}
	else if (GALLERY_STARTUP_SLIDER == this.mrHistory.mMode)
	{	
		this.ShowSliderAction( this.mrHistory.mIndex);
	}
	else if (GALLERY_STARTUP_SLIDE_SHOW == this.mrHistory.mMode)
	{	
		this.StartSlideshow( this.mrHistory.mIndex);
	}
	else
	{
		throw new Error('Invalid mode ['+this.mrHistory.mMode+']');	
	}
 };
 
 Jph_Application.prototype.GetSliderApp = function()
 {
	if (!this.mrSlider)
	{
		this.mrSlider		=	new JphSlider_SliderApp( this);
		this.mrSlider.Init();
	}
	
	return this.mrSlider;
 };
 Jph_Application.prototype.GetThumbnailsApp = function()
 {
	if (!this.mrThumbnails)
	{
		this.mrThumbnails	=	new JphThumbs_ThumbsApp( this);
		this.mrThumbnails.Init();
	}
	
	return this.mrThumbnails;
 };

 /*******************************************/
 /*			EVENTS							*/
 /*******************************************/  
 Jph_Application.prototype._OnLocationChanged = function()
 {
 	this.Run();
 };

 /*******************************************/
 /*			ACTIONS							*/
 /*******************************************/
 Jph_Application.prototype.ShowThumbsAction		=	function()
 {
	
 	var thumbs	=	this.GetThumbnailsApp();
 	
 	if (this.mrSlider)
 	{
 		this.mrSlider.Hide();
 		this.mrSlider.mrSlideshow.StopSlideshow();		
 		this.mrSlider.mrToolbars.Hide();
 	}

 	thumbs.Show();	
	this.mrHistory.SelectThumbnails();
	
	this.NormalizeVertical();
 };
 
 Jph_Application.prototype.ShowSliderAction		=	function( index)
 { 	
	index	=	parseInt(index);
	
 	var slider	=	this.GetSliderApp();
	
 	if (this.mrThumbnails)
 		this.mrThumbnails.Hide();
	
	slider.SetDefaultSlide( index);
	slider.Show();
	slider.mrToolbars.Show();
	
	this.NormalizeVertical();
 };
 
 Jph_Application.prototype.StartSlideshow		=	function( index)
 { 	
	
	this.ShowSliderAction( index);
	this.mrSlider.mrSlideshow.TogglePlay();
	this.mrSlider.mrToolbars.Show();
 };
 
 
 /*******************************************/
 /*			UTIL							*/
 /*******************************************/  
  
 Jph_Application.prototype.NormalizeVertical = function()
 {
 	if (this.mVerticalTimeout)
 		clearTimeout( this.mVerticalTimeout);
 	this.mVerticalTimeout	=	setTimeout('scrollTo(0,0)',100);
 };