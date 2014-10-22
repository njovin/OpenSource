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

if($_POST['userID'] == $_SESSION['userID']){
	$json->returnError('You cannot disable yourself!');
	exit;	
}

$admin = new Admin();

if($admin->is_admin){
	$user = new User($_POST['userID']);
	if($user->active){
		$user->deactivate();
		$json->returnJSON(array("status"=>true,"msg"=>"User Disabled."));
	}else{
		$user->activate();
		$json->returnJSON(array("status"=>true,"msg"=>"User Activated."));	
	}
}
else{
	$json->returnError("Sorry, but you must be an admin!"); 
	exit;
}?>
