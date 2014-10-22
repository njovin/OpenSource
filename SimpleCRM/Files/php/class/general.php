<?php
require_once('db.php');
class General{

	private $db;
	public $ftp,$server_email,$site_title,$logo_nav_html,$siteurl;
	public $salt;
	
	public function __construct(){
		$this->db = new Database();	
		
	###### CONFIGURATION SETTINGS########################################################################
			$this->site_title = "My SITE";		
			$this->siteurl = "http://mysite.biz/";
			$this->server_email = "email@mysite.biz";
			
			// Recommended size: 187x21
			$this->logo_nav_html = '<img src="images/mylogo.png" alt="Logo Nav HTML" />';
			$this->welcome_logo_html = '<img src="images/login_logo_trans.png" alt="Welcome Logo HTML" />';
	
			##Set to FALSE if you do not want to export csvs OR if you do not have a server set up yet.
			$this->ftp = false;
	###### END CONFIGURATION SETTINGS####################################################################
				
	###### DO CHANGE THIS############################################################
		$this->salt = "!$$$45sFASDF45vsdljsdf9025F!!f4SDF";
	}
	
	public function get_user_levels(){
		$s = "SELECT * FROM levels";
		$q = $this->db->qry($s);
		$levels = array();
		while($level = mysql_fetch_assoc($q)):
			$levels[] = $level;
		endwhile;
		return $levels;
	}
	
/*	public function encrypt($pure_string, $encryption_key) {
		$iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, $encryption_key, utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);
		return $encrypted_string;
	}
*/
	/**
	 * Returns decrypted original string
	 */
	/*public function decrypt($encrypted_string, $encryption_key) {
		$iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, $encryption_key, $encrypted_string, MCRYPT_MODE_ECB, $iv);
		return $decrypted_string;
	}*/

	public function encrypt($string, $salt) {
		$output = false;
		$key = $salt;
	   // initialization vector 
	   $iv = md5(md5($key));
	   $output = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_encode($string), MCRYPT_MODE_CBC, $iv);
	   $output = $output;
	   return base64_encode($output);
	}
	
	public function decrypt($string,$salt) {
   $output = false;

   $key = $salt;

	   // initialization vector 
   	$iv = md5(md5($key));

       $output = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($string), MCRYPT_MODE_CBC, $iv);
       $output = rtrim($output, "");
   		return base64_decode($output);
}	
	
	public function js($js){
	
		$j = "
		<script type='text/javascript'>
			$(document).ready(function(e){
				".$js."
				});
		</script>";
		return $j;
		
	}
}
?>
