<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {
	
	public function index()
	{
		$sql = "SELECT	*
FROM 	`tb_monitor_journey_detail`
WHERE	`monitor_journey_id` = 1040000000000082308";
		print_r(get_rows($sql));
	}
	
	function isJson($string) {
   		json_decode($string);
   		return json_last_error() === JSON_ERROR_NONE;
	}
}

?>