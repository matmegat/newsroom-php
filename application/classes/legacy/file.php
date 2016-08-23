<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class LEGACY_File {
	
	// import an existing file
	public static function import($file)
	{
		$sf_original = Stored_File::from_file($file);
		$sf_original->move();
		$sf_original->save_to_db();
		return $sf_original;
	}
	
}

?>