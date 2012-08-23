<?php
require_once "lib/config.inc.php";

$result = array();
$result['success'] = 1;
$result['message'] = "";

try{
	print_r($_REQUEST);
	
	$token 		= isset($_REQUEST['token'])?$_REQUEST['token']:'';
	$to_name 	= isset($_REQUEST['to_name'])?$_REQUEST['to_name']:'';
	$to_email 	= isset($_REQUEST['to_email'])?$_REQUEST['to_email']:'';
	$subject 	= isset($_REQUEST['subject'])?$_REQUEST['subject']:'';
	$body_txt 	= isset($_REQUEST['body_txt'])?$_REQUEST['body_txt']:'';
	$body_html 	= isset($_REQUEST['body_html'])?$_REQUEST['body_html']:'';
	$auth 		= isset($_REQUEST['auth'])?$_REQUEST['auth']:'';

	$local_auth = md5("EMAIL:".$to_email.":MD5:SUBJECT:".$subject.$token);

	echo $auth." - ".$local_auth;

	if($auth != $local_auth){
		throw new Exception("Invalid Auth");
	}

	if(!filter_var($to_email, FILTER_VALIDATE_EMAIL)){
		throw new Exception("Invalid Email ID", 1);
	}

	if(trim($subject) == ""){
		throw new Exception("Missing Subject");
	}

	if(trim($body_html) == "" && trim($body_txt) == ""){
		throw new Exception("Missing Body Message");
	}

	$res = $db->fetchRow("select * from `mailer` where `token` = ?", array($token));
	if(is_array($res) && isset($res['id'])){
		if($res['status'] == 0){
			throw new Exception("Account is not active");
		}
	}else{
		throw new Exception("Invalid Account");
	}

	$mailer_id = $res['id'];

	$mailqueue = new MailQueue();
	$id = $mailqueue->add($mailer_id, $to_name, $to_email, $subject, $body_txt, $body_html);
	if($id > 0){
		$result['queue_id'] = $id;
		$result['message'] = "Successfully Added to Mail Queue";
	}else{
		throw new Exception("Failed Adding to Queue");
	}

}catch(Exception $e){

	$result['success'] = 0;
	$result['message'] = $e->getMessage();

}

echo json_encode($result);

