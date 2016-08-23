<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class File_Util {
	
	public static function detect_mime($filename)
	{
		$finfo = new finfo(FILEINFO_MIME_TYPE);
		return $finfo->file($filename);
	}
	
	public static function buffer_file()
	{
		return tempnam(SYSTEM_TEMP_DIR, 'BF_');
	}
	
}
	
?>