<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Stored_File {
	
	const SAFE_EXTENSION = 'bin';
	const SIZE_GIB = 1073741824;
	const SIZE_MIB = 1048576;
	const SIZE_KIB = 1024;
	
	protected static $__supported_exts = array(
	
		'gif',	// image
		'png',	// image
		'jpg',	// image
		'jpeg',	// image
		'doc',	// word document
		'docx',	// word document
		'xls',	// excel spreadsheet
		'xlsx',	// excel spreadsheet
		'ppt',	// powerpoint
		'pptx',	// powerpoint
		'rtf',	// rich text document
		'pdf',	// portable document
		'mp3',   // mp3 audio 
		'csv',   // csv data
		'txt',   // text file
		'zip',   // zip file
		
	);	
	
	public $id;
	public $destination;
	public $extension;
	public $mime;
	public $filename;
	public $source;
	
	protected $__moved;
	
	public static function parse_extension($filename, $default = null)
	{
		if ($default === null) 
			$default = static::SAFE_EXTENSION;
		
		// if no extension return a safe .bin extension
		if (strpos($filename, '.') === false) 
			return $default;		
			
		// parse extension and check it is allowed
		$extension = end(explode('.', basename($filename)));
		if (!in_array($extension, static::$__supported_exts))
			return $default;
		
		return $extension;
	}
	
	public static function from_file($source, $default_ext = null)
	{
		if (!is_file($source))
			return new static();
		
		$file = new static();
		$file->source = $source;
		$file->extension = static::parse_extension($source, $default_ext);
		$file->generate_filename();
		return $file;
	}
	
	public static function from_uploaded_file($name, $default_ext = null)
	{
		if (!isset($_FILES[$name])) 
			return new static();
		
		$upload = new static();
		$upload->source = $_FILES[$name]['tmp_name'];
		$upload->extension = static::parse_extension(
			$_FILES[$name]['name'], $default_ext);
		$upload->generate_filename();
		return $upload;
	}
	
	public static function from_stored_filename($filename)
	{
		$file = new static();
		$ci =& get_instance();
		$prefix = $ci->conf('upload_url');		
		$file->filename = $filename;
		$file->source = "{$prefix}/{$filename}";
		$file->destination = "{$prefix}/{$filename}";
		$file->moved = true;
		$file->extension = static::parse_extension($filename);
		return $file;
	}
	
	public static function from_db($id)
	{
		$row = static::load_data_from_db($id);
		if (!$row) return false;
		$filename = $row->filename;
		$sf = static::from_stored_filename($filename);
		$sf->id = (int) $id;
		return $sf;
	}
	
	public function actual_filename()
	{
		if ($this->__moved) 
			return $this->destination;
		return $this->source;
	}
	
	public function has_supported_extension()
	{
		if (!$this->extension)
			$this->extension = static::parse_extension($this->filename);
		
		if (!$this->extension)
			return false;
		
		return in_array($this->extension, 
			static::$__supported_exts);
	}
	
	public function exists()
	{
		return is_file($this->source);
	}
	
	public function size()
	{
		if ($this->__moved) 
			return filesize($this->destination);
		return filesize($this->source);
	}
	
	public function detect_mime()
	{
		if ($this->mime) return $this->mime;
		if ($this->__moved) 
			return $this->mime = File_Util::detect_mime($this->destination);
		return $this->mime = File_Util::detect_mime($this->source);
	}
	
	public function generate_filename($ext = null)
	{
		if (!$ext) $ext = $this->extension;
		
		$ci = & get_instance();
		$base = $ci->conf('upload_dir');
		$hash = md5_file($this->source);
		$dir1 = substr($hash, 0, 2);
		$dir2 = substr($hash, 2, 2);
		$name = substr($hash, 4, 28);
		
		$this->filename = "{$dir1}/{$dir2}/{$name}.{$ext}";
		$this->destination = "{$base}/{$this->filename}";
		return $this->filename;
	}
	
	public function read()
	{
		if ($this->__moved) 
			return file_get_contents($this->destination);
		return file_get_contents($this->source);
	}
	
	public function move()
	{
		if ($this->__moved) return;
		
		$dir = dirname($this->destination);
		if (!is_dir($dir)) mkdir($dir, 0755, true);
	
		$source = $this->source;
		$destination = $this->destination;
		rename($source, $destination);
		$this->__moved = true;
	}
	
	public function delete()
	{
		if ($this->__moved) 
			return $this->delete_destination();
		return $this->delete_source();
	}
	
	public function delete_source()
	{
		return unlink($this->source);
	}
	
	public function delete_destination()
	{
		return unlink($this->destination);
	}
	
	public function save_to_db()
	{
		if (!$this->__moved)
			throw new Exception();
		
		$ci =& get_instance();
		$ci->db->query("INSERT IGNORE INTO 
			nr_stored_file (filename) VALUES (?)",
			array($this->filename));
		
		if (!($id = $ci->db->insert_id()))
		{
			$query = $ci->db->query("SELECT id FROM nr_stored_file 
				WHERE filename = ?", array($this->filename));
			$result = $query->row();
			$this->id = $id = $result->id;
		}
		
		return $id;
	}
	
	public static function load_data_from_db($id)
	{
		$ci =& get_instance();
		$data = array('id' => $id);
		$result = $ci->db->get_where('nr_stored_file', $data);
		return $result->row();
	}
	
	public function human_size()
	{
		$size = $this->size();
		
		if ($size > static::SIZE_GIB) 
		{
			$size = ($size / static::SIZE_GIB);
			return sprintf('%.2f GiB', $size);
		}
		
		if ($size > static::SIZE_MIB) 
		{
			$size = ($size / static::SIZE_MIB);
			return sprintf('%.2f MiB', $size);
		}
		
		$size = ($size / static::SIZE_KIB);
		return sprintf('%.2f KiB', $size);
	}
	
	public function url()
	{
		$ci =& get_instance();
		$prefix = $ci->conf('upload_url');
		return "{$prefix}/{$this->filename}";
	}
	
	public static function url_from_filename($filename)
	{
		$ci =& get_instance();
		$prefix = $ci->conf('upload_url');
		return "{$prefix}/{$filename}";
	}
	
}

?>