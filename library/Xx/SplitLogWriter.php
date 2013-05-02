<?PHP

// require_once 'Xx/Log.php';

class Xx_SplitLogWriter // extends Xx_Log
{
	public $mPrefix;
	public $mPath;
	public $mUseSession	=	true;
	
	public function __construct( $path, $prefix, $useSession)
	{
		$this->mPath		=	$path;
		$this->mPrefix		=	$prefix;
		$this->mUseSession	=	$useSession;
	}
	
	
	public function init()
	{
		if (!is_dir( $this->mPath))
		{
			if (false === mkdir( $this->mPath))
			throw new Exception( 'Log folder ['.$this->mPath.'] could not be created. Please creeate it manualy or make the parent folder writable by php');
		}
	
		// 		if (is_writable( $this->mPath))
		// 			throw new Exception( 'Log folder ['.$this->mPath.'] is not writable. Please change file permissions on that folder');
	}
	
	public function log( $type, $str)
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
	
	protected function _formatError( Exception $error)
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