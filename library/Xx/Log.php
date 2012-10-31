<?PHP

require_once 'Xx/DefaultLogWriter.php';
require_once 'Xx/SplitLogWriter.php';

class Xx_Log
{
	protected static $_instance;
	
	
	/**
	 * Enter description here...
	 *
	 * @param string $path
	 * @param string $prefix
	 * @param bool $useSession
	 * @return Xx_Log
	 */
	public static function createLog(  $path, $prefix, $useSession=false)
	{
		if (!XX_LOG_ENABLED)
			return;
			
		self::$_instance	=	new Xx_SplitLogWriter( $path, $prefix, $useSession);		
		self::$_instance->Init();
		
		self::logDebug( '============================================================');
		self::logDebug( $_SERVER['REQUEST_URI']);
		self::logDebug( $_SERVER['HTTP_USER_AGENT']);
		self::logDebug( '============================================================');
		
		return self::$_instance;
	}
	public static function createDefaultLog( $useSession=false)
	{
		if (!XX_LOG_ENABLED)
			return;
			
		self::$_instance	=	new Xx_DefaultLogWriter( $useSession);		
		self::$_instance->Init();
		
		self::logDebug( '============================================================');
		self::logDebug( $_SERVER['REQUEST_URI']);
		self::logDebug( $_SERVER['HTTP_USER_AGENT']);
		self::logDebug( '============================================================');
		
		return self::$_instance;
	}
	
	
	/**
	 * Enter description here...
	 *
	 * @return Xx_Log
	 */
	public static function getInstance()
	{
		if (isset(self::$_instance))
			return self::$_instance;
			
		throw new Exception( 'Log not created. Try to use first createLog() method');
	}
	
	// LOGING	
	public static function logError( Exception $error)
	{
		if (!XX_LOG_ENABLED)
			return;
			
		$log	=	self::getInstance();		
		$str	=	self::_formatError( $error);
		
		if (XX_DEBUG_ENABLED)
			debug( $str);
		
		$arr	=	explode("\n", trim($str));
		
		foreach ($arr as $item)
			$log->log( 'ERROR', trim( $item));
	}
	
	public static function logDebug( $str)
	{
		if (!XX_LOG_ENABLED)
			return;
		$log	=	self::getInstance();		
		$log->log( 'DEBUG', $str);
	}
	
	public static function logTrace( $str)
	{
		if (!XX_LOG_ENABLED)
			return;
		$log	=	self::getInstance();		
		$log->log( 'TRACE', $str);
	}
	
	public static function printR( $str)
	{
		if (!XX_LOG_ENABLED)
		return;
		$log	=	self::getInstance();
		$log->log( 'DEBUG', print_r( $str, true));
	}
	

	protected static function _formatError( Exception $error)
	{
		if ($error instanceof Exception)
		{
			$str	= 	get_class( $error).': ' .$error->getMessage()."\r\n";
			$str	.=	$error->getTraceAsString(); 
		}
		else		
		{
			$str	=	$error;
		}
		return $str;
	}

}


function debug( $argument=null)
{
	if (!XX_DEBUG_ENABLED)
		return;
		
	$str	=	'';
	
	for ($i=0;$i<func_num_args(); $i++)
	{
		$data	=	func_get_arg( $i);
		if(is_null($data))
		{
			$str	.=	'<code><span style="color:green;background-color:white;">NULL</span></code>';				
		}
		else if(is_bool($data))
		{
			$str	.=	'<code><span style="color:green;background-color:white;">'	.($data	?	"TRUE"	:	"FALSE")	."</span></code>";
		}	
		else
		{
			$str	.=	"<pre>".print_r($data,true)."</pre>";
		}			
			
	}
	$time 	= 	microtime();
	$date	=	date( 'H:i:s').':'.substr( $time, 2, 3);
	$trace	=	debug_backtrace();

	$trace	=	$trace[0];

	$info	=	$trace['file'].' ('.$trace['line'].')';

	echo "<FIELDSET><LEGEND>&nbsp;<b>".$date." DEBUG</b>\t<b>$info</b>&nbsp;</LEGEND>"; 
	echo $str;
	echo "</FIELDSET>";
}


?>