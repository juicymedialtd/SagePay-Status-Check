<?php 
/**
Copyright Juicy Media Ltd, Peter Davies 01/01/2011

Usage, add a CRON at any frequency pointing to the script:
/usr/bin/wget -q http://www.testdomain.com/test-sagepay/checksagepay.php

Update your own e-mail address too.

**/
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors',1);

// useful to tell the remote service who we are
$useragent = 'SagePay Live Check 1.0';

// contact info
$user = "sage.error";
$domain = "testdomain.com";

// sagepay URL
$url = "https://live.sagepay.com";
$status = 200;
$error = false;

// define cURL options
// specifically ignore any SSL issues
// also no html body required, we only need to know if we can connect
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, $useragent); 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

// get any errors
$ce = curl_exec($ch);
$curl_errno = curl_errno($ch);
$curl_error = curl_error($ch);	
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// if there is an problem we need to know
// i.e. anything other than the required status
if(!$ce) {
	$error = true; 
} elseif($status == $httpcode){
	$error = false; // good response
} else {
	$error = true; 
}

// now report on error
if ($error){
	$error_string = "SagePay Error: ".$curl_error. " (curl error: ".$curl_errno." & http code: ".$httpcode.")";
	echo $error_string;
	
	// construct basic e-mail
	$to = $user."@".$domain;
	$subject = $useragent." Error";
	$message = $error_string;
	$headers = 'From: servers@'.$domain . "\r\n";
	mail($to, $subject, $message, $headers);	
} else {
	// output good message for load balancer and failover detection
	// i.e. to notify that everything is OK
	echo "SagePay check ok";
}

unset($ce);
exit();
?>