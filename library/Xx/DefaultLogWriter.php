<?PHP


class Xx_DefaultLogWriter 
{
	
	public $mUseSession	=	true;
	
	public function __construct( $useSession)
	{
		$this->mUseSession	=	$useSession;
	}
	
	
	public function init()
	{
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
	
	
		error_log( $str1);
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
	
	protected function _getInfo( $type, $info)
	{
		$time 	= 	microtime();
		$date	=	':'.substr( $time, 2, 3);
	
		if ($this->mUseSession)
		$str 	= 	$date . " " . $type. "\t{"  . session_id(). "}\t" . $info;
		else
		$str 	= 	$date . " " . $type. "\t" . $info;
	
		return $str;
	}
	
}