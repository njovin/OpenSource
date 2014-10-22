<?php session_start();  $access_level = 1;
require_once('php/general.php');
$contact = new Contact($user,$_GET['conact_id']);
if($contact->do_i_own_this($_GET['contact_id'])):

	// EDIT HERE
	$ftp_host = "123.456.789.0";		
	$ftp_user = "ftp_user_name";
	$ftp_pass = "ftp_user_pass";
	$ftp_folder = "my_exports_folder";
	//END EDIT HERE
		
	// write
	$user_data = $contact->coma_limited($_GET['contact_id']);
	//echo $user_data;
	
	$filename = date('m-d-Y_hi').'_'.$user_data['fullname'].'.csv';
			
	$h = fopen($filename,"w");
	fwrite($h,$user_data);
	fclose($h);
	// open
	//echo $filename;
	$o = fopen($filename,'r');
	//echo file_get_contents($filename);
	$conn = ftp_connect($ftp_host);
	$login = ftp_login($conn,$ftp_user,$ftp_pass);
	// turn passive mode on..
	ftp_pasv($conn, true);
	ftp_fput($conn,$ftp_folder.'/'.$filename,$o, FTP_BINARY);
	ftp_close($conn);
	fclose($o);			

	

else:
	echo "You are not authorized to be here.";
	exit;
endif;
?>