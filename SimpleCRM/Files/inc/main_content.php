<?php

$user_level = isset($user->level) ? $user->level : 0;
switch($user_level):
	case '1': // Standard USER Menu
		echo print_user_menu();
	break;
	case '2': // Admin USER Menu
		echo print_admin_menu();
	break;
endswitch;

function print_user_menu(){
	$menu = "
	<div id='printed_user_menu' class='printed_menu'>
		<div><a href='add_contact.php'><button class='btn btn-default'><i class='glyphicon glyphicon-plus'></i> Add Contact</button></a></div>
		<div><a href='view_contacts.php'><button class='btn btn-default'><i class='glyphicon glyphicon-user'></i> View Contacts</button></a></div>
		<div><a href='user_form_defaults.php'><button class='btn btn-default'><i class='glyphicon glyphicon-list'></i> List Values</button></a></div>
	</div>
	";
	return $menu;
}
function print_admin_menu(){
	$menu = "
		<div id='printed_admin_menu' class='printed_menu'>
			<div><a href='add_user.php'><button class='btn btn-default'><i class='glyphicon glyphicon-plus'></i> Add User</button></a></div>
			<div><a href='list_users.php'><button class='btn btn-default'><i class='glyphicon glyphicon-user'></i> View Users</button></a></div>
			<div><a href='default_form_values.php'><button class='btn btn-default'><i class='glyphicon glyphicon-list'></i> List Values</button></a></div>
		</div>
	";
	return $menu;
}




?>