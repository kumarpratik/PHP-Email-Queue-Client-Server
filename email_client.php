<?php
require_once "lib/class/send-email-to-queue.class.php";

$to_name = "Kumar Pratik";
$to_email = "pratik.sahu@gmail.com";
$subject = "Test Email";
$body_html = "<h2>Test Email</h2><br /><ul><li>Test #1</li><li>Test #2</li></ul><br />End";
$body_txt = "This message is in txt format\nHope it works!\n\n\nEND";

$email = new sendEmailToQueue("http://local.invoice.com/mailq/email-queue.php","a258de4c4bc98813d58650440131a909");
if($email->send($to_name, $to_email, $subject, $body_txt, $body_html)){
	echo "Message Sent";
	echo "Response : ";
	echo "<pre>";
	print_r($email->response);
	echo "</pre>";
}else{
	echo "Failed!";
	echo "Response : ";
	echo "<pre>";
	print_r($email->response);
	echo "</pre>";
}

