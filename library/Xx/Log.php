<?PHP

class Xx_Log
{
	protected static $_instance;
	
	public $mPrefix;
	public $mPath;
	public $mUseSession	=	true;
	
	private function __construct( $path, $prefix, $useSession)
	{
		$this->mPath		=	$path;
		$this->mPrefix		=	$prefix;
		$this->mUseSession	=	$useSession;
	}
	
	
	private function init()
	{
		if (!is_dir( $this->mPath))
		{
			if (false === mkdir( $this->mPath))
				throw new Exception( 'Log folder ['.$this->mPath.'] could not be created. Please creeate it manualy or make the parent folder writable by php');
		}
		
// 		if (is_writable( $this->mPath))
// 			throw new Exception( 'Log folder ['.$this->mPath.'] is not writable. Please change file permissions on that folder');
	}
	
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
			
		self::$_instance	=	new Xx_Log( $path, $prefix, $useSession);		
		self::$_instance->Init();
		
		self::$_instance->logDebug( '============================================================');
		self::$_instance->logDebug( $_SERVER['REQUEST_URI']);
		self::$_instance->logDebug( $_SERVER['HTTP_USER_AGENT']);
		self::$_instance->logDebug( '============================================================');
		
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
			
		self::$_instance	=	new Xx_Log( '', 'fallback');	
		self::$_instance->Init();
		self::$_instance->logDebug('Starting fallback log');
		
		return self::$_instance;
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
	

	private function log( $type, $str)
	{
		$baktrace	=	debug_backtrace();
		$trace		=	@$baktrace[1];
		
		if (@trim($baktrace[2]['class']))
		{
			$info	=	'['.@$baktrace[2]['class'].':'.@$baktrace[2]['function'].'('.@$baktrace[1]['line'].")]\t";
		}
		else {
			$info	=	'['.$trace['file'].' '."(" .$trace['line'] .")]\t";	
		}
		
		$str1 	= 	$this->_getInfo( $type, $info);
		
		$str1	.=	' '.str_replace("\r\n", "\r\n\t", $str) . "\r\n";
		

		error_log( $str1, 3, $this->_getFilename( $this->mPath, $this->mPrefix));	
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
	
	protected static function _getFilename( $path='', $root='')
	{
		$name	=	$path.$root.'_'.date('Y-m-d').'.log';
		return $name;
	}		

	
	protected function _getInfo( $type, $info)
	{
		$time 	= 	microtime();
		$date	=	date( 'H:i:s').':'.substr( $time, 2, 3);
		
		if ($this->mUseSession)
			$str 	= 	$date . " " . $type. "\t{"  . session_id(). "}\t" . $info;
		else 
			$str 	= 	$date . " " . $type. "\t" . $info;
		
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