<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Billing_Shared {

	protected static $soap_uri = 
		'https://secure.ultracart.com/axis/services/OrderManagementServiceV1?wsdl';

	public static function view_order($option = null)
	{
		$db = Legacy::database();
		$ci =& get_instance();		
		$view_data = array();		
		$view_data['ex'] = array();
		
		try 
		{ 
			$auth = $ci->conf('ultracart_api');	
			$user = Auth::user();
			$order = array();
		
			if ($option === 'autoOrder')
			{
				$ultra_id = $ci->input->get('ultra_id');
				$client = new SoapClient(static::$soap_uri);
				$auto_order = $client->getAutoOrder($auth, $ultra_id);
				$ref_order = $client->exportOrder($auth, 'memberBillingArea', $ultra_id);
				$xml = simplexml_load_string($ref_order);
				
				if ((int) $xml->order->custom_field_2 !== $user->id && 
			    	 (int) $xml->order->custom_field_2 !== 0) 
					throw new Exception('Access Denied');
				
				$order['ultra_id'] = $ultra_id;
				$order['is_auto_order'] = true;
				$order['last_paid_ts'] = strtotime($auto_order->items[0]->lastOrderDts);
				$order['next_paid_ts'] = strtotime($auto_order->items[0]->nextShipment);
				$order['is_active'] = $auto_order->enabled;
				
				if ($ci->input->post('cancel'))
				{
					if (Model_User::authenticate(
						$user->email, $ci->input->post('password')))
					{
						$client->cancelAutoOrderAsCustomerByOrderId($auth, $ultra_id);
						// load feedback message for the user
						$feedback_view = 'shared/account/billing/partials/cancel_feedback';
						$feedback = $ci->load->view($feedback_view, null, true);
						$ci->add_feedback($feedback);
					}
					else
					{
						// load feedback message for the user
						$feedback_view = 'shared/account/billing/partials/exception_feedback';
						$feedback = $ci->load->view($feedback_view, array('e' => 'Password Incorrect'), true);
						$ci->add_feedback($feedback);
					}
					
					$url = "?ultra_id={$ultra_id}#view";
					$ci->redirect($url, false);
				}
			}
			else if ($option === 'dbOrder')
			{
				$id = (int) $ci->input->get('id');
				$dbr = $db->query("SELECT raw_data FROM raw_payments
					WHERE id = {$id} and userid = {$user->id}");
				$db_order = $dbr->row_array();
				$xml = simplexml_load_string($db_order['raw_data']);
				$order['ultra_id'] = $xml->order->order_id;
			}		
					
			if (!$xml) throw new Exception('Order Not Found');
			
			$order['amount'] = $xml->order->total;
			$order['paid_ts'] = strtotime($xml->order->payment_date_time);
			$order['bill_to'] = array();
			$order['bill_to']['company'] = $xml->order->bill_to_company;
			$order['bill_to']['title'] = $xml->order->bill_to_title;
			$order['bill_to']['first_name'] = $xml->order->bill_to_first_name;
			$order['bill_to']['last_name'] = $xml->order->bill_to_last_name;
			$order['bill_to']['address1'] = $xml->order->bill_to_address1;
			$order['bill_to']['address2'] = $xml->order->bill_to_address2;
			$order['bill_to']['city'] = $xml->order->bill_to_city;
			$order['bill_to']['state'] = $xml->order->bill_to_state;
			$order['bill_to']['zip'] = $xml->order->bill_to_zip;
			$order['bill_to']['country'] = $xml->order->bill_to_country;
			$order['email'] = $xml->order->email;
			$order['day_phone'] = $xml->order->day_phone;
			$order['evening_phone'] = $xml->order->evening_phone;
			$order['fax'] = $xml->order->fax;
			$order['card_type'] = $xml->order->card_type;
			$order['card_number'] = $xml->order->card_number;
			$order['items'] = array();
			
			foreach ($xml->order->item as $item)
			{
				$order_item = array();
				$order_item['id'] = $item->item_id;
				$order_item['quantity'] = $item->quantity;
				$order_item['description'] = $item->description;
				$order_item['amount'] = $item->cost;
				$order['items'][] = $order_item;
			}
			
			$order['subtotal'] = $xml->order->subtotal;
			$order['shipping'] = $xml->order->shipping_handling_total;
			$order['tax_rate'] = $xml->order->tax_rate;
			$order['tax'] = $xml->order->tax;
			$order['total'] = $xml->order->total;	
								
			$view_data['order'] = $order;
		} 
		catch (Exception $e)
		{
			// load feedback message for the user
			$feedback_view = 'shared/account/billing/partials/exception_feedback';
			$feedback = $ci->load->view($feedback_view, array('e' => $e->getMessage()), true);
			$ci->use_feedback($feedback);
		}
		
		return $view_data;		
	}
	
	public static function view_list($option = null)
	{
		$ci =& get_instance();
		$db = Legacy::database();
		$auth = $ci->conf('ultracart_api');
		$user = Auth::user();
		
		$view_data = array();
		$view_data['ex'] = array();
		$view_data['autoOrders'] = array();		
		$view_data['dbOrders'] = array();
		
		$known_emails = array($user->email);			
		
		$dt_cut = Date::months(-6)->format(Date::FORMAT_MYSQL);
		$dbr = $db->query("SELECT * FROM (SELECT id, raw_data, orderid 
			FROM raw_payments WHERE `from` = 'ULTRA' AND
			userid = {$user->id} AND datetime > '{$dt_cut}'
			ORDER BY id DESC) r
			GROUP BY orderid ORDER BY id DESC");
		
		foreach ($dbr->result_array() as $db_order)
		{
			try 
			{
				$order = array();
				$xml = simplexml_load_string($db_order['raw_data']);
				$order['ultra_id'] = $xml->order->order_id;
				$order['amount'] = $xml->order->total;
				$order['card_type'] = @$xml->order->card_type;
				$order['card_number'] = substr(@$xml->order->card_number, -4);
				$order['paid_dt'] = Date::out($xml->order->payment_date_time);
				$order['id'] = $db_order['id'];
				$view_data['dbOrders'][] = $order;
				$email = (string) $xml->order->email;
				if (!in_array($email, $known_emails))
					$known_emails[] = $email;
			}
			catch (Exception $e)
			{
				$view_data['ex'][] = new Exception('Bad Order Data');
			}				
		}
		
		ini_set('soap.wsdl_cache_enabled', '1');
		ini_set('soap.wsdl_cache', '1');
		
		try
		{
			$client = new SoapClient(static::$soap_uri);
			$all_results = array();
			
			foreach ($known_emails as $email)
			{
				$results = $client->getActiveAutoOrderOrderIds($auth, $email);
				$all_results = array_merge($all_results, $results);
			}
			
			foreach ($all_results as $auto_id)
			{
				$order = array();
				$auto_order = $client->getAutoOrder($auth, $auto_id);
				$order['ultra_id'] = $auto_order->referenceOrderId;
				$ref_order = $client->exportOrder($auth, 'memberBillingArea', $order['ultra_id']);
				$xml = simplexml_load_string($ref_order);
				// purchase made from ci email address but does not 
				// match the account number (so must be used for 2 accounts)
				if ((int) $xml->order->custom_field_2 !== $user->id && 
				    (int) $xml->order->custom_field_2 !== 0) continue;
				$order['amount'] = $xml->order->total;
				$description = (string) @$xml->order->item->description;
				preg_match('#([a-z]+) Plan#i', $description, $package_match);
				$order['package'] = @$package_match[1];
				$order['paid_dt'] = Date::out($auto_order->items[0]->lastOrderDts);
				$view_data['autoOrders'][] = $order;
			}
		} 
		catch (Exception $e)
		{
			// load feedback message for the user
			$feedback_view = 'shared/account/billing/partials/exception_feedback';
			$feedback = $ci->load->view($feedback_view, array('e' => 'Service Unavailable'), true);
			$ci->use_feedback($feedback);
		}
		
		return $view_data;
	}

}

?>