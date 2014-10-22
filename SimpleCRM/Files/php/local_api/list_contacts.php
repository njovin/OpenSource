<?php
session_start();
require_once('../class/user.php');
require_once('JSON.php');
$json = new JSON();

if(!isset($_GET['query'])){
	$json->returnError("Missing Parameters."); 
	exit;
}

$user = new User();
$contacts_list = $user->suggest_list_contacts($_GET['query']);
$json->returnJSON(array("status"=>true,"suggestions"=>$contacts_list));	
?>
