<?php
session_start();
require_once('../class/user.php');
require_once('../class/admin.php');
require_once('JSON.php');
$json = new JSON();

if(!isset($_GET['query'])){
	$json->returnError("Missing Parameters."); 
	exit;
}

$admin = new Admin();

if($admin->is_admin){
	// list the users.
	$users_list = $admin->suggest_list_user($_GET['query']);
	$json->returnJSON(array("status"=>true,"suggestions"=>$users_list));	
}
else{
	$json->returnError("Sorry, but you must be an admin!"); 
	exit;
}?>
