<?php

class sendEmailToQueue{
	var $server_uri;
	var $token;
	var $response;

	public function __construct($server_uri="http://local.invoice.com/mailq/email-queue.php", $token=''){
		$this->server_uri = $server_uri;
		$this->token = $token;
	}

	public function send($to_name, $to_email, $subject, $body_txt, $body_html){
		if($this->token == ''){
			return false;
		}

		$md5 = md5("EMAIL:".$to_email.":MD5:SUBJECT:".$subject.$this->token);
		
		$postData = array();
		$postData['to_name'] = $to_name;
		$postData['to_email'] = $to_email;
		$postData['subject'] = $subject;
		$postData['body_txt'] = $body_txt;
		$postData['body_html'] = $body_html;
		$postData['token'] = $this->token;
		$postData['md5'] = $md5;

		$this->response = $this->execPostRequest($this->server_uri, $postData);
		if($this->response !== false){
			$data = json_decode($this->response, true);
			if($data['success'] == 1){
				return true;
			}
		}
		return false;
	}

	private function execPostRequest($url, $post_array){
		if(empty($url)){ 
			return false;
		}

		$fields_string =http_build_query($post_array);
		//open connection
		$ch = curl_init();

		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);

		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

		//execute post
		$result = curl_exec($ch);

		//close connection
		curl_close($ch);

		return $result;
	}
}