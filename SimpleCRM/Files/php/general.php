<?php
require_once(dirname(__FILE__).'/class/db.php');
require_once(dirname(__FILE__).'/class/user.php');
require_once(dirname(__FILE__).'/class/general.php');

// Set up user. (if Applicable)
$user = new User();
// Set up the admin if the user IS an admin...
if($user->is_admin) : 
	require_once(dirname(__FILE__).'/class/admin.php');
	$admin = new Admin($user->user_id); 
endif;

$general = new General();
//is page secure?
if($access_level > 0):
	if($user->user_id && $user->level >= $access_level){ // LOGIN REQUIRED, AND MEETS LEVEL
		//DO NOTHING.
		//echo "Grant Access!";	
		
	}
	elseif($user->user_id && !$user->level < $access_level){  // SENIOR LEVEL
		echo "You are not authorized to view this page.";
		exit;
	}
	else{
		header("Location: login.php");	
	}
else:
	// PAGE NOT PRIVATE
endif;
?>
