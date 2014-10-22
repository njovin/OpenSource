<?php session_start();  $access_level = 1;
require_once('php/general.php');
$contact = new Contact($user,$_GET['contact_id']);
echo $contact->coma_limited($_GET['contact_id']);
?>