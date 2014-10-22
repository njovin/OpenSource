<?php
session_start();
require_once('../class/user.php');
require_once('../class/admin.php');
require_once('JSON.php');
$json = new JSON();

if(!isset($_POST['userID'])){
	$json->returnError("Missing Parameters."); 
	exit;
}
if($_SESSION['userID'] == $_POST['userID']){
	$json->returnError("You cannot delete yourself!");
	exit;	
}
$admin = new Admin();

if($admin->is_admin){
	$user = new User($_POST['userID']);
	$user->delete();
	$json->returnJSON(array("status"=>true,"User Deleted."));	
}
else{
	$json->returnError("Sorry, but you must be an admin!"); 
	exit;
}?>
