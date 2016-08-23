<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('cli/base');

class Publish_To_Social_Controller extends CLI_Base {
	
	public function index()
	{
		set_time_limit(3600);
		
		while (true)
		{
			$sql = "SELECT c.id, c.type, c.slug, c.title, c.company_id,
				cd.post_to_facebook, cd.post_to_twitter 
				FROM nr_content c INNER JOIN nr_content_data cd 
				ON c.id = cd.content_id WHERE 
				c.is_published = 1 AND
				cd.post_to_facebook = 1 OR 
				cd.post_to_twitter = 1
				LIMIT 1";
				
			$result = $this->db->query($sql);
			if (!$result->num_rows()) break;
			$content = Model_Content::from_db($result);
			
			$sql = "UPDATE nr_content_data SET post_to_facebook = 0, 
				post_to_twitter = 0 WHERE content_id = ?";
			$this->db->query($sql, array($content->id));
			
			$newsroom = Model_Newsroom::find_company_id($content->company_id);
			$user = Model_User::find($newsroom->user_id);
			if ($user->is_free_user()) continue;
			
			if ($content->post_to_facebook)
				$this->post_to_facebook($content, $newsroom);
				
			if ($content->post_to_twitter)
				$this->post_to_twitter($content, $newsroom);
		}
	}
	
	private function post_to_facebook($content, $newsroom)
	{
		$facebook_auth = Social_Facebook_Auth::find($content->company_id);
		if (!$facebook_auth || !$facebook_auth->is_valid())
			return;
		
		$url = $this->website_url($content->url());			
		$facebook_post = new Social_Facebook_Post();
		$facebook_post->set_auth($facebook_auth);
		$facebook_post->set_data(Social_Facebook_Post::DATA_MESSAGE, $content->title);
		$facebook_post->set_data(Social_Facebook_Post::DATA_LINK, $url);
		
		if ($content->type === 'image' || $content->type === 'video')
		{
			$m_content = Model_Content::from_object($content);
			$m_content->load_local_data();
			
			if ($m_content->image_id)
			{
				$image = Model_Image::find($m_content->image_id);
				$original = $image->variant('original');
				$image_url = Stored_Image::url_from_filename($original->filename);
				$facebook_post->set_data(Social_Facebook_Post::DATA_PICTURE,
					$newsroom->url($image_url));
			}
		}
				
		$res = $facebook_post->save();
		if (!$res) return;
		
		$sql = "UPDATE nr_content_data SET post_id_facebook = ? WHERE content_id = ?";
		$this->db->query($sql, array($res, $content->id));
	}
	
	private function post_to_twitter($content, $newsroom)
	{
		$twitter_auth = Social_Twitter_Auth::find($content->company_id);
		if (!$twitter_auth || !$twitter_auth->is_valid())
			return;
		
		$url = $this->website_url($content->url());
		$message = $content->title;
		$max = Social_Twitter_Post::MAX_LENGTH;
		$max = ($max - Social_Twitter_Post::TCO_LENGTH) - 1;
		
		if (strlen($message) > $max) 
		{
			$message = substr($message, 0, ($max - 4));
			$message = "{$message} ...";
		}
		
		$twitter_post = new Social_Twitter_Post();
		$twitter_post->set_auth($twitter_auth);
		$twitter_post->set_message("{$message} {$url}");
		$res = $twitter_post->save();
		if (!$res) return;
		
		$sql = "UPDATE nr_content_data SET post_id_twitter = ? WHERE content_id = ?";
		$this->db->query($sql, array($res, $content->id));
	}
	
}

?>