<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_Content extends Model {
	
	const MAX_SLUG_LENGTH = 64;
	
	const TYPE_PR       = 'pr';
	const TYPE_NEWS     = 'news';
	const TYPE_IMAGE    = 'image';
	const TYPE_AUDIO    = 'audio';
	const TYPE_VIDEO    = 'video';
	const TYPE_EVENT    = 'event';
	
	public $id;
	public $company_id;
	public $type;
	public $slug;
	public $date_created;
	public $date_publish;
	public $is_published;
	public $is_draft;
	
	const BASIC    = 'BASIC';
	const PREMIUM  = 'PREMIUM';
	
	protected static $__table = 'nr_content';
	
	public static function recent_tags($company, $limit)
	{
		$limit = (int) $limit;
		$sql = "SELECT t.value 
			FROM nr_content c 
			INNER JOIN nr_content_tag t
			ON c.id = t.content_id AND c.company_id = ?
			GROUP BY t.value 
			ORDER BY c.id DESC, t.value ASC 
			LIMIT {$limit}";
		
		$results = array();
		$model = new static();
		$query = $model->db->query($sql, array($company));
		
		foreach ($query->result() as $result)
			$results[] = $result->value;
		
		return $results;
	}
	
	public function set_tags($tags)
	{
		$this->db->query("DELETE FROM nr_content_tag 
			WHERE content_id = ?", array($this->id));
		
		foreach ($tags as $tag)
		{
			if (!($tag = trim($tag))) continue;
			$uniform = Tag::uniform($tag);
			$this->db->query("INSERT IGNORE INTO nr_content_tag (content_id, 
				value, uniform) VALUES (?, ?, ?)", array($this->id, $tag, $uniform));
		}
	}
	
	public function set_images($images)
	{
		$this->db->query("DELETE FROM nr_content_image
			WHERE content_id = ?", array($this->id));
		
		foreach ($images as $image)
		{
			if ($image instanceof Model_Image) $image = $image->id;
			$this->db->query("INSERT IGNORE INTO nr_content_image (content_id, 
				image_id) VALUES (?, ?)", array($this->id, $image));
		}
	}
	
	public function set_related($related_set)
	{
		$this->db->query("DELETE FROM nr_content_related
			WHERE content_id = ?", array($this->id));
		
		foreach ($related_set as $related)
		{
			if ($related instanceof Model_Content) $related = $related->id;
			$this->db->query("INSERT IGNORE INTO nr_content_related (content_id, 
				content_id_far) VALUES (?, ?)", array($this->id, $related));
		}
	}
	
	public function get_tags()
	{
		$tags = array();
		$query = $this->db->query("SELECT value FROM nr_content_tag 
			WHERE content_id = ?", array($this->id));
		
		foreach ($query->result() as $result)
			$tags[] = $result->value;
		
		return $tags;
	}
	
	public function get_images()
	{
		$query = $this->db->query("SELECT i.* FROM nr_image i 
			INNER JOIN nr_content_image ci 
			ON i.id = ci.image_id WHERE content_id = ?", 
			array($this->id));
		
		$images = Model_Image::from_db_all($query);
		return $images;
	}
	
	public function get_related()
	{
		$query = $this->db->query("SELECT c.* 
			FROM nr_content c INNER JOIN nr_content_related cr 
			ON c.id = cr.content_id_far WHERE cr.content_id = ?", 
			array($this->id));
		
		$related_set = Model_Content::from_db_all($query);
		return $related_set;
	}
	
	public function load_local_data()
	{
		$table = "nr_pb_{$this->type}";
		$this->load_data($table);
	}
	
	public function load_content_data()
	{
		$table = 'nr_content_data';
		$this->load_data($table);
	}
	
	public function load_data($table)
	{
		$result = $this->db->get_where($table, 
			array('content_id' => $this->id));
		$data = $result->row();
		foreach ($data as $k => $v)
			$this->$k = $v;
	}
	
	public function uuid()
	{
		return UUID::hashed($this->id);
	}
	
	public function is_scheduled()
	{
		if ($this->is_published) return false;
		if ($this->is_under_review) return false;
		if ($this->is_draft) return false;
		return true;
	}
	
	public function is_consume_locked()
	{
		if ($this->is_published) return true;
		if ($this->is_under_review) return true;
		if ($this->is_approved) return true;
		return false;
	}
	
	public function owner()
	{
		if (!($nr = Model_Newsroom::find($this->company_id))) return false;
		return Model_User::find($nr->user_id);
	}
	
	public function url()
	{
		return "view/{$this->slug}";
	}
	
	public function permalink()
	{
		$ci =& get_instance();
		return $ci->website_url("view/id/{$this->id}");
	}
	
	public function url_id()
	{
		return $this->permalink();
	}
	
	public function url_raw()
	{
		$ci =& get_instance();
		return $ci->website_url("view/raw/{$this->id}");
	}
	
	public static function permalink_from_id($id)
	{
		$ci =& get_instance();
		return $ci->website_url("view/id/{$id}");
	}
	
	public static function allowed_types()
	{
		return array(
			static::TYPE_PR, static::TYPE_NEWS, 
			static::TYPE_EVENT, static::TYPE_IMAGE, 
			static::TYPE_AUDIO, static::TYPE_VIDEO, 
		);
	}
	
	public static function is_allowed_type($type)
	{
		return in_array($type, static::allowed_types());
	}
	
	public static function full_type($type)
	{
		$display = array(
			static::TYPE_PR => 'Press Release',
			static::TYPE_NEWS => 'News', 
			static::TYPE_IMAGE => 'Image', 
			static::TYPE_AUDIO => 'Audio',
			static::TYPE_VIDEO => 'Video', 
			static::TYPE_EVENT => 'Event',
		);
		
		return @$display[$type];
	}
	
	public static function full_type_plural($type)
	{
		$display = array(
			static::TYPE_PR => 'Press Releases',
			static::TYPE_NEWS => 'News', 
			static::TYPE_IMAGE => 'Images', 
			static::TYPE_AUDIO => 'Audio',
			static::TYPE_VIDEO => 'Videos', 
			static::TYPE_EVENT => 'Events',
		);
		
		return @$display[$type];
	}
	
	public static function short_type($type)
	{
		$display = array(
			static::TYPE_PR => 'PR',
			static::TYPE_NEWS => 'News', 
			static::TYPE_IMAGE => 'Image', 
			static::TYPE_AUDIO => 'Audio',
			static::TYPE_VIDEO => 'Video', 
			static::TYPE_EVENT => 'Event',
		);
		
		return @$display[$type];
	}
	
	public function delete()
	{
		$this->db->delete('nr_content', array('id' => $this->id));
		$this->db->delete('nr_content_data', array('content_id' => $this->id));
		$this->db->delete('nr_content_tag', array('content_id' => $this->id));
		$this->db->delete('nr_content_image', array('content_id' => $this->id));
		$this->db->delete("nr_pb_{$this->type}", array('content_id' => $this->id));
		
		if (!$this->is_published && $this->is_under_review)
		{
			// consumed is not always present => calculated 
			if ($consumed = Model_Limit_PR_Consumed::find($this->id))
				$consumed->restore($this->owner());
		}			
	}
	
	public function title_to_slug()
	{
		$this->slug = static::generate_slug($this->title, (int) $this->id);
	}
	
	public static function generate_slug($title, $existing_id = 0)
	{
		if ($title) 
		{
			// normalize title
			$slug = strtolower($title);
			$slug = preg_replace('#[^a-z0-9]#is', '-', $slug);
			$slug = preg_replace('#--*#is', '-', $slug);
			$slug = preg_replace('#(^-|-$)#is', '', $slug);
			
			// trim and then re-normalize
			$slug = substr($slug, 0, static::MAX_SLUG_LENGTH);
			$slug = preg_replace('#(^-|-$)#is', '', $slug);
		}
		else
		{
			// generate a random slug based on time
			return substr(md5(microtime(true)), 0, 32);
		}
		
		$model = new static();
		$result = $model->db->query(
			"SELECT 1 FROM nr_content
			 WHERE slug = ? AND id != ?", 
			array($slug, (int) $existing_id));
		
		if (!$result->num_rows())
			return $slug;
		
		$time = substr(md5(microtime(true)), 0, 8);
		$extra_len = strlen($time) + 1;
		$max_len = static::MAX_SLUG_LENGTH;
		$max_len = $max_len - $extra_len;
		$slug = substr($slug, 0, $max_len);
		$slug = preg_replace('#(^-|-$)#is', '', $slug);
		$slug = sprintf('%s-%s', $slug, $time);
		return $slug;
	}
	
}

?>