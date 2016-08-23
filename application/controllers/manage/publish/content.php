<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('manage/listing');

class Content_Base extends Listing_Base {
	
	protected $listing_section = 'publish';
	
	protected function edit($content_id = 0)
	{
		$company_id = (int) $this->newsroom->company_id;
		$is_preview = (bool) $this->input->post('is_preview');
		$m_content = null;
		
		if ($content_id)
		{
			$this->vd->m_content = $m_content = Model_Content::find($content_id);
			if (!$m_content || (int) $m_content->company_id !== $company_id) 
				$this->denied();
			$m_content->load_local_data();
			$m_content->load_content_data();
		}
		
		if ($m_content)
			  $title_type = Model_Content::full_type($m_content->type);
		else $title_type = Model_Content::full_type($this->uri->segment(3));
		array_pop($this->vd->title);
		
		if ($m_content && $m_content->title)
			  $this->title = $m_content->title;
		else if ($m_content) $this->title = "Edit {$title_type}";
		else $this->title = "Add {$title_type}";
		
		if ($is_preview) 
			$m_content = $this->vd->m_content;
		
		$recent_tags = Model_Content::recent_tags($company_id, 5);
		$this->vd->recent_tags = $recent_tags;
		
		$cats = Model_Cat::list_all_cats_by_group();
		$this->vd->cats = $cats;
		
		$twitter_auth = Social_Twitter_Auth::find($company_id);
		$facebook_auth = Social_Facebook_Auth::find($company_id);
		
		$this->vd->social = new stdClass();
		$this->vd->social->twitter = $twitter_auth && $twitter_auth->is_valid();
		$this->vd->social->facebook = $facebook_auth && $facebook_auth->is_valid();
		
		// attempt to renew facebook token
		if ($this->vd->social->facebook)
			$facebook_auth->renew_if_needed();
		
		if ($m_content)
		{
			$dt_date_publish = Date::out($m_content->date_publish, $this->local_tz());
			if ($dt_date_publish->format('H:i') === '00:00')
			     $m_content->date_publish_str = $dt_date_publish->format('Y-m-d');
			else $m_content->date_publish_str = $dt_date_publish->format('Y-m-d H:i');
		}
		
		return get_defined_vars();
	}
	
	protected function edit_save($content_type)
	{
		$content_id = (int) $this->input->post('id');
		$is_new_content = !$content_id;
		$company_id = (int) $this->newsroom->company_id;
		$post = $this->input->post();
		$is_preview = (bool) @$post['is_preview'];
		$m_content = null;
		
		if ($content_id)
		{		
			$m_content = Model_Content::find($content_id);
			if (!$m_content || (int) $m_content->company_id !== $company_id) 
				$this->denied();
			$m_content->load_local_data();
			$m_content->load_content_data();
		}
		
		if (!($title = $post['title']))
			$title = substr(md5(time()), 0, 16);
		
		$dt_date_publish = new DateTime(@$post['date_publish'], $this->local_tz());
		if (!preg_match('#\d:\d\d#', $post['date_publish']))
			$dt_date_publish->setTime(0, 0, 0);
		$dt_date_publish->setTimezone(Date::$utc);
		
		$content = value_or_null($this->vd->pure(@$post['content']));		
		$summary_max_length = $this->conf('summary_max_length');
		$summary = substr($post['summary'], 0, $summary_max_length);
		$title_max_length = $this->conf('title_max_length');
		$title = substr($title, 0, $title_max_length);		
		
		$is_draft = (bool) @$post['is_draft'];
		$is_premium = (bool) @$post['is_premium'];
		$is_published = false;
		
		if ($m_content && $m_content->is_consume_locked())
			$is_premium = $m_content->is_premium;
		
		if ($m_content && $m_content->is_published)
		{
			$dt_date_publish = new DateTime($m_content->date_publish);
			$post_to_facebook = (bool) $m_content->post_to_facebook;
			$post_to_twitter = (bool) $m_content->post_to_twitter;
			$slug = $m_content->slug;
			$is_published = true;
			$is_draft = false;
		}
		
		// scheduled just now (not a save)
		$is_new_scheduled = !$m_content || 
			$m_content->is_draft && !$is_draft;
		
		// only new content and non-consumed can set socials
		if (!$m_content || !$m_content->is_consume_locked())
		{
			$slug = Model_Content::generate_slug($title, @$m_content->id);
			if ($m_content) $m_content->slug = $slug;
			$post_to_facebook = (bool) @$post['post_to_facebook'];
			$post_to_twitter = (bool) @$post['post_to_twitter'];
		}
		
		$supporting_quote_name = value_or_null(@$post['supporting_quote_name']);
		$supporting_quote_title = value_or_null(@$post['supporting_quote_title']);
		$supporting_quote = value_or_null(@$post['supporting_quote']);
		$rel_res_pri_title = value_or_null(@$post['rel_res_pri_title']);
		$rel_res_pri_link = value_or_null(@$post['rel_res_pri_link']);
		$rel_res_sec_title = value_or_null(@$post['rel_res_sec_title']);
		$rel_res_sec_link = value_or_null(@$post['rel_res_sec_link']);
		$rel_res_pri_link = URL::safe($rel_res_pri_link);
		$rel_res_sec_link = URL::safe($rel_res_sec_link);
		
		$tags = explode(',', $post['tags']);
		$images = array();
		
		foreach ((array) @$post['image_ids'] as $k => $image_id)
		{
			$image = Model_Image::find(value_or_null($image_id));
			if (!$image) continue;
			$images[] = $image;
			$image->save();
		}
		
		$cover_image_id = value_or_null(@$post['cover_image_id']);
		$cover_image = Model_Image::find($cover_image_id);
		if (!$cover_image || (int) $cover_image->company_id !== $company_id) 
			$cover_image_id = null;
		
		if ($is_preview)
		{
			if ($is_new_content) $m_content = new Model_Content();
			$m_content->date_publish = $dt_date_publish->format(Date::FORMAT_MYSQL);
			if ($is_new_content) $m_content->date_created = Date::$now->format(Date::FORMAT_MYSQL);			
			if (isset($slug)) $m_content->slug = $slug;
			$m_content->cover_image_id = $cover_image_id;
			$m_content->is_published = $is_published;
			$m_content->is_premium = $is_premium;
			$m_content->company_id = $company_id;
			$m_content->type = $content_type;
			$m_content->is_draft = !$is_published;			
			$m_content->title = $title;
			
			$m_content->content = $content;
			$m_content->summary = $summary;
			$m_content->supporting_quote = $supporting_quote;
			$m_content->supporting_quote_name = $supporting_quote_name;
			$m_content->supporting_quote_title = $supporting_quote_title;
			$m_content->rel_res_pri_title = $rel_res_pri_title;
			$m_content->rel_res_pri_link = $rel_res_pri_link;
			$m_content->rel_res_sec_title = $rel_res_sec_title;
			$m_content->rel_res_sec_link = $rel_res_sec_link;
			
			Detached::reset();
			Detached::write('m_content', $m_content);
			
			$url = $m_content->url();
			if ($content_type === Model_Content::TYPE_PR && 
			    !$this->newsroom->is_active)
				  $preview_url = Detached::url($url, true);
			else $preview_url = Detached::url($url);
			$this->set_redirect($preview_url, false);
			
			// capture current context vars
			$defined_vars = get_defined_vars();
			return $defined_vars;
		}
		else
		{
			if ($is_new_content) $m_content = new Model_Content();
			$m_content->date_publish = $dt_date_publish->format(Date::FORMAT_MYSQL);	
			if ($is_new_content)	$m_content->date_created = Date::$now->format(Date::FORMAT_MYSQL);
			$m_content->cover_image_id = $cover_image_id;
			$m_content->is_published = $is_published;
			$m_content->is_premium = $is_premium;
			$m_content->company_id = $company_id;
			$m_content->is_draft = $is_draft;
			$m_content->type = $content_type;
			$m_content->title = $title;
			$m_content->slug = $slug;
			$m_content->save();
			
			$content_id = $m_content->id;
			
			if ($is_new_content)
				  $m_content_data = new Model_Content_Data();
			else $m_content_data = Model_Content_Data::find($content_id);			
					
			$m_content_data->content = $content;
			$m_content_data->summary = $summary;
			$m_content_data->post_to_facebook = $post_to_facebook;
			$m_content_data->post_to_twitter = $post_to_twitter;
			$m_content_data->supporting_quote = $supporting_quote;
			$m_content_data->supporting_quote_name = $supporting_quote_name;
			$m_content_data->supporting_quote_title = $supporting_quote_title;
			$m_content_data->rel_res_pri_title = $rel_res_pri_title;
			$m_content_data->rel_res_pri_link = $rel_res_pri_link;
			$m_content_data->rel_res_sec_title = $rel_res_sec_title;
			$m_content_data->rel_res_sec_link = $rel_res_sec_link;
			$m_content_data->content_id = $content_id;
			$m_content_data->save();
			
			$m_content->set_tags((array) $tags);
			$m_content->set_images((array) $images);
			
			$default_newsroom = Auth::user()->default_newsroom();
			if ($this->newsroom->company_id !== $default_newsroom->company_id)
			{
				$current_time = time();
				$default_newsroom->order_default = $current_time;
				$this->newsroom->order_default = $current_time - 1;
				$default_newsroom->save();
				$this->newsroom->save();
			}
			
			// capture current context vars
			$defined_vars = get_defined_vars();
			
			// load feedback message for the user
			$feedback_view = 'manage/publish/partials/feedback/save';
			$feedback = $this->load->view($feedback_view, $defined_vars, true);
			$this->add_feedback($feedback);
		
			// redirect back to type specific listing
			$redirect_url = "manage/publish/{$content_type}/all";
			$this->set_redirect($redirect_url);
			
			return $defined_vars;
		}
	}
	
	protected function do_delete($content_id)
	{
		$m_content = Model_Content::find($content_id);
		$m_content->load_local_data();
		$m_content->load_content_data();
		$m_content->delete();
		return $m_content;
	}
	
	public function delete($content_id)
	{
		if (!$content_id) return;
		$this->vd->type = $type = $this->uri->segment(3);
		if (!Model_Content::is_allowed_type($type))
			return;
		
		if ($this->input->post('confirm'))
		{
			$this->do_delete($content_id);
			
			// load feedback message 
			$feedback_view = 'manage/publish/partials/feedback/delete_after';
			$feedback = $this->load->view($feedback_view, null, true);
			$this->add_feedback($feedback);
			
			// redirect back to type specific listing
			$redirect_url = "manage/publish/{$type}/all";
			$this->set_redirect($redirect_url);
		}
		else
		{
			// load confirmation feedback 
			$this->vd->content_id = $content_id;
			$feedback_view = 'manage/publish/partials/feedback/delete_before';
			$feedback = $this->load->view($feedback_view, null, true);
			$this->use_feedback($feedback);
			$this->edit($content_id);
		}
	}
	
	protected function resolve_video($provider, $video_id, $download_thumb = false)
	{
		$this->json(Video::resolve($provider, $video_id, $download_thumb));
	}
	
}

?>