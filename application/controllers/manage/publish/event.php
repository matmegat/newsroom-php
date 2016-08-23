<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('manage/publish/content');

class Event_Controller extends Content_Base { 

	public function __construct()
	{
		parent::__construct();
		$this->vd->title[] = 'iPublish';
		$this->vd->title[] = 'Event';
	}

	public function index()
	{
		$this->redirect('manage/publish/event/all');
	}
	
	public function edit($content_id = null)
	{		
		$vars = parent::edit($content_id);
		extract($vars, EXTR_SKIP);
		
		$vd = array();
		$vd['event_types'] = array();
		
		$query = $this->db
			->order_by('name', 'asc')
			->get('nr_event_type');
			
		foreach ($query->result() as $result)
			$vd['event_types'][] = $result;
		
		if ($m_content)
		{
			$date_start = Date::out($m_content->date_start);			
			$m_content->date_start_str = $date_start->format('Y-m-d');
			$m_content->time_start_str = $date_start->format('h:i:s A');
			
			if ($m_content->date_finish)
			{
				$date_finish = Date::out($m_content->date_finish);
				$m_content->date_finish_str = $date_finish->format('Y-m-d');
				$m_content->time_finish_str = $date_finish->format('h:i:s A');
			}
			else
			{
				$m_content->date_finish_str = $m_content->date_start_str;
				$m_content->time_finish_str = '11:59:59 PM';
			}
		}
		
		$this->load->view('manage/header');
		$this->load->view('manage/publish/menu');
		$this->load->view('manage/pre-content');
		$this->load->view('manage/publish/event-edit', $vd);
		$this->load->view('manage/post-content');
		$this->load->view('manage/footer');
	}
	
	public function edit_save()
	{
		$vars = parent::edit_save('event');
		extract($vars, EXTR_SKIP);
		
		$event_type_id = (int) $post['event_type_id'];
		$is_all_day = (bool) @$post['is_all_day'];
		$discount_code = value_or_null($post['discount_code']);
		$price = (float) $post['price'];
		$address = value_or_null($post['address']);
		
		if ($is_all_day)
		{
			$date_start = Date::in($post['date_start']);
			$str_date_start = $date_start->format(Date::FORMAT_MYSQL);
			$str_date_finish = null;
		}
		else
		{
			$date_start = new DateTime($post['date_start'], $this->local_tz());
			$time_start = new DateTime($post['time_start']);
			$ts_h = (int) $time_start->format('H');
			$ts_m = (int) $time_start->format('i');
			$ts_s = (int) $time_start->format('s');
			$date_start->setTime($ts_h, $ts_m, $ts_s);
			
			$date_finish = new DateTime($post['date_finish'], $this->local_tz());
			$time_finish = new DateTime($post['time_finish']);
			$tf_h = (int) $time_finish->format('H');
			$tf_m = (int) $time_finish->format('i');
			$tf_s = (int) $time_finish->format('s');
			$date_finish->setTime($tf_h, $tf_m, $tf_s);
			
			$date_start->setTimezone(Date::$utc);
			$date_finish->setTimezone(Date::$utc);
			
			$str_date_start = $date_start->format(Date::FORMAT_MYSQL);
			$str_date_finish = $date_finish->format(Date::FORMAT_MYSQL);
		}
		
		if ($is_preview)
		{
			$m_content = Detached::read('m_content');
			$m_content->event_type_id = $event_type_id;
			$m_content->date_start = $str_date_start;
			$m_content->date_finish = $str_date_finish;
			$m_content->is_all_day = $is_all_day;
			$m_content->price = $price;
			$m_content->discount_code = $discount_code;
			$m_content->address = $address;
			$m_content->set_tags((array) $tags);
			$m_content->set_images((array) $images);
			Detached::write('m_content', $m_content);
			return;
		}
		else
		{
			if ($is_new_content)
			     $m_pb_event = new Model_PB_Event();
			else $m_pb_event = Model_PB_Event::find($m_content->id);
			
			$m_pb_event->event_type_id = $event_type_id;
			$m_pb_event->date_start = $str_date_start;
			$m_pb_event->date_finish = $str_date_finish;
			$m_pb_event->is_all_day = $is_all_day;
			$m_pb_event->price = $price;
			$m_pb_event->discount_code = $discount_code;
			$m_pb_event->address = $address;
			$m_pb_event->content_id = $m_content->id;
			$m_pb_event->save();
		}
	}
	
}

?>