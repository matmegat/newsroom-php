<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_parent_controller('cli/base');

class Transfer_Initial extends CLI_Base {
	
	public function index()
	{
		$ci =& get_instance();
		
		$bulk = 10;
		$start = time();		
		$result = $ci->db->query("SELECT id FROM prs_temp");
		$result->free_result();
		$total = $result->num_rows;
		$count = 0;
		
		@ob_end_flush();
		@ob_end_flush();
		@ob_end_flush();
		
		gc_enable();
		
		while ($count += $bulk)
		{
			$now = time();
			$diff = $now - $start;
			$fraction = $count / $total;
			$time_total = (1 / $fraction) * $diff;
			$estimated_seconds = (int) ($time_total - $diff);
			$estimated = Date::seconds($estimated_seconds);
			$eta = Date::difference_in_words($estimated);
			
			printf("%02.2f %% ({$count}/{$total}) - {$diff} seconds - {$eta}\n", 
				($fraction * 100));
			flush();
			
			set_time_limit(300);
			$command_status = 0;
			$next_index = $count - 1;
			$controller = 'transfer_initial_item';
			system("php index.php cli {$controller} {$next_index} {$bulk}", $command_status);
			if ($command_status != 111) return;
		}
	}
	
}

?>