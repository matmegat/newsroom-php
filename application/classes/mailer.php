<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mailer {
	
	public static function send($email)
	{
		// some users don't have email address
		// so we will protect from failure here
		if (!$email->get('to_email'))
			return false;
		
		$ci =& get_instance();
		$exec = $ci->conf('mailer_exec');
		$file = File_Util::buffer_file();
		file_put_contents($file, $email->send(false));
		$command = sprintf('cat %s | %s', $file, $exec);
		shell_exec($command);
		unlink($file);
		return true;
	}
	
}

?>