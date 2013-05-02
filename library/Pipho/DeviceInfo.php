<?php
/******************************************************************************
 *	PIPHO, version 1.02.00
 *	(c) 2010 jaipho.com
 *
 *	PIPHO is freely used under the terms of an FreeBSD license.
 *	For details, see the PIPHO web site: http://www.jaipho.com/pipho/
 ******************************************************************************/

class Pipho_DeviceInfo
{
	public $thumbsSize;
	public $slideMaxSize;
	

	/**
	 * Enter description here...
	 *
	 * @return Pipho_DeviceInfo
	 */
	public static function getDeviceInfo()
	{
		$info	=	new Pipho_DeviceInfo();
		
		if (self::isIphone())
		{
			$info->thumbsSize		=	75;
			$info->slideMaxSize		=	480;
		}
		else if (self::isIpad())
		{
			$info->thumbsSize		=	75;
			$info->slideMaxSize		=	1024;
		}
		else if (self::isAndroid())
		{
			$info->thumbsSize		=	75;
			$info->slideMaxSize		=	600;
		}
		else 
		{
			// failback
			$info->thumbsSize		=	75;
			$info->slideMaxSize		=	480;
		}
		
		return $info;
	}
	
	
	public static function isSupported()
	{
 		return self::isIpad() || self::isIphone() || self::isAndroid();
	}
	
	public static function isIphone()
	{
		$user_agent		=	self::_getUserAgent();
		
		if (strstr( $user_agent,'iphone') 
 			|| strstr( $user_agent,'ipod'))
 		{
 			return true;
 		}
 		return false;
	}
	
	public static function isIpad()
	{
		$user_agent		=	self::_getUserAgent();
		
		if (strstr( $user_agent,'ipad'))
 		{
 			return true;
 		}
 		return false;
	}
	
	public static function isAndroid()
	{
		$user_agent		=	self::_getUserAgent();
		
		if (strstr( $user_agent,'android'))
 		{
 			return true;
 		}
 		return false;
	}
	
	
	protected static function _getUserAgent()
	{
		return strtolower( $_SERVER['HTTP_USER_AGENT']);
	}	
}
?>