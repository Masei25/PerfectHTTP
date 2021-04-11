<?php 
/**
 * @author Olu Sammy (c) 2021
 * @package Perfect HTTP Package v 0.1.3
 */
namespace Src;
use Exception;

class PerfectHTTP
{
	public $params;
	private $error_msg_prefix;
	private $errors = [];
	public $response;
	
	public function __construct()
	{
		$this->error_msg_prefix = 'PerfectHTTP Error: ';
	}

	public function setRequest($params){
		$this->params = $params;
		$allowed_keys = ['URL', 'METHOD', 'OPTIONS', 'HEADER'];

		$allowed_http_request_methods = ['GET', 'POST'];

		$request_state = [];

		if (isset($this->params['METHOD']) && isset($this->params['URL'])) {
			foreach ($params as $key => $value) {
				if (count($params)  == 4 ) {

					$key = strtoupper($key);

					if (in_array($key, $allowed_keys)) {
						if ($key == 'URL') {
							if (filter_var($value, FILTER_VALIDATE_URL) === false) {
								$e = "Your URL is invalid";
								$this->errors[] = $e;
								throw new Exception($this->error_msg_prefix.$e, 1);
								exit();
							} 
							if (strlen($value) == 0) {
								$e = "Empty URL";
								$this->errors[] = $e;
								throw new Exception($this->error_msg_prefix.$e, 1);	
								exit();
							} 
							
							$request_state['URL'] = true;
							
						} 


						if ($key == 'METHOD') {
							$value = strtoupper($value);
							if (!in_array($value, $allowed_http_request_methods)) {
								$e = "Invalid Request Method";
								$this->errors[] = $e;
								throw new Exception($this->error_msg_prefix.$e, 1);
								exit();
							} 
							
							$request_state['METHOD'] = true;

						}

						if ($key == 'OPTIONS') {
							$request_state['OPTIONS'] = true;
						}


						if ($key == 'HEADER') {
							isset($this->params['METHOD']) == 'POST' ? $request_state['HEADER'] = true 
								: $request_state['HEADER'] = false;
							isset($this->params['METHOD']) == 'GET' ? $request_state['HEADER'] = true 
								: $request_state['HEADER'] = false;	
						}
					}
				} 
			}
		} 

		if(!(isset($this->params['METHOD']) && isset($this->params['URL']))){
			$e = "Parameter Missing";
			$this->errors[] = $e;
			throw new Exception($this->error_msg_prefix.$e, 1);
			exit();
		}

		if (empty($this->errors)) {
			$this->sendRequest();
		}	
	}

	private function sendRequest()
	{
		//Sending GET request	
		if($this->params['METHOD'] == 'GET') {

		    $ch = curl_init(); 
			curl_setopt_array($ch, [
				CURLOPT_URL => trim($this->params['URL'])."?".http_build_query($this->params['OPTIONS']),
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HTTPHEADER => $this->params['HEADER'] ?? null,
			]);
			
			$output=curl_exec($ch);
		    curl_close($ch);
		    return $this->response =  $output;	
		}

		//Sending POST request
		if ($this->params['METHOD'] == 'POST') {

			$ch = curl_init();
			curl_setopt_array($ch, [
				CURLOPT_URL => $this->params['URL'],
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_POSTFIELDS => $this->params['OPTIONS'],			
				CURLOPT_HTTPHEADER => $this->params['HEADER'],
			]);
	
			$request = curl_exec($ch);
			curl_close($ch);

			return $this->response =  $request;
		}
	}
}