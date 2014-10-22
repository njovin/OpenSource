<?php
class JSON
	{
		
		public function returnError($message)
		{
			$return = array("status" => FALSE, 'error'=> $message);
			$this->returnJSON($return);
			
		}
		public function returnJSON($json)
		{
			header('Access-Control-Allow-Origin: *');
			header('Content-type: application/json');
			echo json_encode($json);
			exit;
		}
		public function returnErrorWithCode($message, $code)
		{
			$return = array("status" => FALSE, 'error' => $message, 'code' => $code);
			$this->returnJSON($return);
		}
		
	}
	?>
