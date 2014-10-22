<?php
session_start();
require_once('../class/user.php');
require_once('JSON.php');
$json = new JSON();

if(!isset($_POST['contactID'])){
	$json->returnError("Missing Parameters."); 
	exit;
}
$user = new User();
$contact = new Contact($user,$_POST['contactID']);
$results = $contact->delete();
if(!$results['status']){
	$json->returnError($results['msg']); 
	exit;
}
else{
	$json->returnJSON($results);
}
?>
