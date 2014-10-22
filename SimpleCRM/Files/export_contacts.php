<?php session_start();  $access_level = 1;
require_once('php/general.php');
$contact = new Contact($user);
echo $contact->export();
?>