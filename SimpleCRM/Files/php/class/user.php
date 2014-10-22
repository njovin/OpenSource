<?php
require_once('db.php');
require_once('general.php');
class User{

	public $user_id;
	private $private_dir;
	private $general;
	protected $db;
	protected $siteurl;
	public $email,$levelname,$is_admin,$active;
	// DEFAULTS
	public $level = 0;  // access level
	protected $admin_threshold = 2; // SET default for admin level..	
	
	public function __construct($user_id = FALSE){
		$this->db = new Database();
		$this->general = new General();
		$this->siteurl = $general->siteurl;
		if($user_id): // Creates a user object from an optional ID, else it will use the logged in user.
			$this->user_id = $user_id;
		else:
			if(isset($_SESSION['userID'])) # Sets the user to the default logged in user.
				$this->user_id = $_SESSION['userID'];
		endif;
		
		$this->build_user();
		
	}
	
	public function login($email,$password){
		$sql = "SELECT id FROM users WHERE email = '?' AND password = '?' AND active = '1'";
		$q = $this->db->qry($sql,$email,$this->general->encrypt($password,$this->general->salt));
		if(mysql_num_rows($q)):
			$row = mysql_fetch_assoc($q);
			$_SESSION['userID'] = $row['id'];
			return true;
		else:
			return false;
		endif;
	}
	public function hash_is_valid($hash){
		$SELECT = "SELECT id FROM users WHERE password = '?' AND active = 1 AND deleted = 0";
		$q = $this->db->qry($SELECT,$hash);
		if(mysql_num_rows($q)):
			return true;
		else:
			return false;
		endif;
	}
	public function update_password($hash,$pass){
		$u = "UPDATE users SET password = '?' WHERE password = '?' LIMIT 1";
		$this->db->qry($u,$this->general->encrypt($pass,$this->general->salt),$hash);
		
		if(mysql_affected_rows($this->db->databaseLink))
			return true;
		return false;
	}
	public function forgot($email){
	
		$s = "SELECT email,password FROM users WHERE email = '?' AND active = 1  AND deleted = 0";
		$q = $this->db->qry($s,$email);
		
		if(!mysql_num_rows($q))	
			return false;

		$d = mysql_fetch_assoc($q);
		
		// Email the user the link with the hash...
		$reset_link = $this->siteurl.'reset.php?hq='.$d['password'];
		$msg = "Please use the following link to reset your password on ".$this->general->site_title.".";
		$msg .= "<br/>";
		$msg .= '<a href="'.$reset_link.'">Reset Password</a>';

		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		// Additional headers
		$headers .= 'To: '.$d['email']. "\r\n";
		$headers .= 'From: '.$this->general->site_title.' <'.$this->general->server_email.'>' . "\r\n";
		
		mail($d['email'],'Your Reset Link',$msg,$headers);		
		return true;
		
	}
	protected function build_user(){
		$select = "SELECT users.email,users.password,users.active,users.level,levels.name AS levelname FROM users JOIN levels ON users.level = levels.id WHERE users.id = '?'";
		$q = $this->db->qry($select,$this->user_id);
		$r = mysql_fetch_assoc($q);
		$this->level = $r['level'];
		$this->email = $r['email'];
		$this->levelname = $r['levelname'];
		$this->is_admin = ($r['level'] >= $this->admin_threshold) ? true : false;
		$this->active = $r['active'];
		$this->password = $this->general->decrypt($r['password'],$this->general->salt);
	}	
	
	
	private function has_private_dir(){
		$s = "SELECT private_dir FROM users WHERE id = '?'";
		$res = $this->db->qry($s,$this->user_id);
		$results = mysql_fetch_assoc($res);
		$dir = $results['private_dir'];
		
		if($dir == NULL){
				$this->create_private_dir();	
				return $this->has_private_dir();
		}
		$this->private_dir = $dir;
		return true;		
	}
	
	public function get_private_dir(){
		if($this->user_id != $_SESSION['userID']) return 'nice_try';
		$this->has_private_dir();
		
		return $this->private_dir;
	}
	private function create_private_dir(){
	
		$u = "UPDATE users SET private_dir = '?' WHERE id = '?' LIMIT 1";
		
		$hash = crypt(uniqid()).'.'.$this->user_id;
		
		

		if(mkdir(dirname(__FILE__).'/../../e/'.$hash,0777)){
			$this->db->qry($u,$hash,$this->user_id);		
		}
		return true;
				
	}
	
	
	
	public function form_label_from_master_form_id($master_form_id){
		$s = "SELECT label FROM master_form WHERE id = '?'";
		$q = $this->db->qry($s,$master_form_id);
		$r = mysql_fetch_assoc($q);
		return $r['label'];
	}
	protected function does_email_exist($email){
		$sql = "SELECT * FROM users WHERE email = '?'";
		$r = $this->db->qry($sql,$email);
		if(mysql_num_rows($r))
			return true;
		return false;
	}
	
	public function activate($activate = 1){
		$s = "UPDATE users SET active = '?' WHERE id = '?' LIMIT 1";
		$this->db->qry($s,$activate,$this->user_id);
	}
	public function deactivate(){
		$this->activate(0);
	}
	public function delete(){
		$s = "DELETE FROM users WHERE id = '?' LIMIT 1";
		$this->db->qry($s,$this->user_id);
		return true;
	}
	private function user_has_default_form_fields(){
	
		$s = "SELECT * FROM user_forms WHERE user_id = '?'";
		$q = $this->db->qry($s,$this->user_id);
		if(mysql_num_rows($q))
			return true;
		return false;
		
	}
	
	private function get_missing_form_ids(){
		# GET all the missing default form fields from the user table.
		$s = "SELECT DISTINCT master_form_id
		FROM master_form_values
		WHERE master_form_values.master_form_id NOT
		IN (
			SELECT user_forms.master_form_id
			FROM user_forms
			WHERE user_id = '?'
		)";	
		$q = $this->db->qry($s,$this->user_id);
		$missing = array();
		while($id = mysql_fetch_assoc($q)){
			$missing[] = $id['master_form_id'];
		}
		return $missing;
	}
	private function get_my_custom_fields(){
		$s = "SELECT 
master_form.id,
master_form.label,
user_forms.value,
user_master_forms.active
FROM master_form 
RIGHT JOIN user_forms ON master_form.id = user_forms.master_form_id
RIGHT JOIN user_master_forms ON master_form.id = user_master_forms.master_form_id
WHERE user_master_forms.user_id = '?' AND user_forms.user_id = '?'";
				
	$q = $this->db->qry($s,$this->user_id,$this->user_id);
		
		$form_fields = array();
		
		while($form_field = mysql_fetch_assoc($q)):
		//id,label,active,values	
			$form_fields[$form_field['id']]['id'] = $form_field['id'];
			$form_fields[$form_field['id']]['label'] = $form_field['label'];
			//$form_fields[$form_field['id']]['required'] = $form_field['required'];
			$form_fields[$form_field['id']]['active'] = $form_field['active'];
			$form_fields[$form_field['id']]['values'][] = $form_field['value'];  
		
		endwhile;
		//foreach($form_fields as $k=>$v):
			// strip duplicates...
	//		$form_fields[$k]['values'] = array_unique($form_fields[$k]['values']);
		//endforeach;
		

		return $form_fields;
		
	}	
/*	private function default_form_label_by_id($id){
		$s = "SELECT label FROM master_form WHERE id = '?'";
		$q = $this->db->qry($s,$id);
		$res = mysql_fetch_assoc($q);
		return $res['label'];
	}
	private function default_form_required_by_id($id){
		$s = "SELECT required FROM master_form WHERE id = '?'";
		$q = $this->db->qry($s,$id);
		$res = mysql_fetch_assoc($q);
		return $res['required'];		
	}
	private function default_form_active_by_id($id){
		$s = "SELECT active FROM master_form WHERE id = '?'";
		$q = $this->db->qry($s,$id);
		$res = mysql_fetch_assoc($q);
		return $res['active'];		
	}		
*/
	private function default_form_values_by_id($id){
		$s = "SELECT value FROM master_form_values WHERE master_form_id = '?'";
		$q = $this->db->qry($s,$id);
		$values = array();
		while($val = mysql_fetch_assoc($q)){
			$values[] = $val['value'];
		}	
		return $values;	
	}	
	
	public function get_default_form_fields(){
		// Does user have any custom set form fields?
		if($this->user_has_default_form_fields()){
				return $this->get_my_custom_fields();	
		}else{
			//IF NOT:
			require_once('admin.php');
			$admin = new Admin();
			return $admin->get_default_form_fields();
			}
	}
	
	public function update_default_form_values($postarray){
		foreach($postarray as $postK=>$postV){
			//echo $postK.'....'.$postV.'...<br>';
	
			$master_form_id = $postK;
			$this_active = $_POST['active_'.$master_form_id];
			//$this_required = $_POST['required_'.$master_form_id];
			$this->set_form_field_active($master_form_id,$this_active);
			$values = $postV;
			$form_field = explode(PHP_EOL, $values);
			$form_field  = $this->remove_empties($form_field);
			# Empty the existing values to prevent duplicates and READ deletions...
			$this->empty_form_defaults($master_form_id);
			foreach($form_field as $value){
				$this->update_form_field($master_form_id,$value);
			}
			
		} 
		
		
		
		foreach($this->get_missing_form_ids() as $missing_field_id):
			// All the MISSING fields that the user deleted. -> LOAD from default
			$missing_field_values = $this->default_form_values_by_id($missing_field_id);
				foreach($missing_field_values as $de_value){
					$form_fields[$missing_field_id]['values'][] = $de_value; 
					$this->update_form_field($missing_field_id,$de_value);
					$this->set_form_field_active($missing_field_id,0);
				}
	
		endforeach;	
		
		
		
		
		
		$this->clean_up_form_defaults();
		return array("status"=>true,"msg"=>"Form fields updated.");
	}
	private function clean_up_form_defaults(){
		$d = "DELETE FROM user_forms WHERE master_form_id LIKE '?' AND user_id = '?'";
		$this->db->qry($d,'active_%',$this->user_id);
	}
	
	private function remove_empties($array){
		
		foreach($array as $k=>$v){
			$a = trim($v);
			if(empty($a)){ 
				unset($array[$k]);
			}
		}
		
		return $array;
		
	}
	private function update_form_field($master_form_default_id,$new_value){
		if($master_form_default_id == 0 ) return false;
		$s = "REPLACE INTO user_forms(master_form_id,value,user_id) values('?','?','?')";
		$this->db->qry($s,$master_form_default_id,$new_value,$this->user_id);
				
	}
	
	private function set_form_field_active($id,$active = 1){

		$UPDATE = "REPLACE INTO user_master_forms(active,user_id,master_form_id) VALUES('?','?','?')";
		$this->db->qry($UPDATE,$active,$this->user_id,$id,$this->user_id);
		
		}

	
	private function empty_form_defaults($master_form_id){
		$d = "DELETE FROM user_forms WHERE master_form_id = '?' AND user_id = '?'";
		$this->db->qry($d,$master_form_id,$this->user_id);
	}
	public function suggest_list_contacts($entered_text){
	
		$s = "SELECT id as id,email as value FROM user_contacts WHERE (deleted = 0 AND email like '?' OR fname like '?' OR lname like '?' OR company like '?' OR city like '?' OR state like '?' OR zip like '?' OR phone like '?' OR cell like '?' OR address like '?') AND user_id = '?'";
		$q = $this->db->qry($s,'%'.$entered_text.'%','%'.$entered_text.'%','%'.$entered_text.'%','%'.$entered_text.'%','%'.$entered_text.'%','%'.$entered_text.'%','%'.$entered_text.'%','%'.$entered_text.'%','%'.$entered_text.'%','%'.$entered_text.'%',$this->user_id);
		$users = array();
		
		while($row = mysql_fetch_assoc($q)):
			$users[] = $row;
		endwhile;
		
		return $users;
	}	
	

}
class Contact{
		private $user;
		protected $db;
		public $contact_id;
		public $pagination_limit = 50;
		public function __construct($user_obj, $contact_id = NULL){
				$this->user = $user_obj;
				$this->db = new Database();
				if(!is_null($contact_id))
					$this->contact_id = $contact_id;
		}
		
			
		public function get_all($start){
			$s = "SELECT * FROM user_contacts WHERE user_id = '?' AND deleted = 0 LIMIT ?";

			$q = $this->db->qry($s,$this->user->user_id,$start.",".$this->pagination_limit);

			$contacts = array();
			while($contact = mysql_fetch_assoc($q)):
				$contacts[] = $contact;
			endwhile;
			return $contacts;
		}
		public function update_contact($post){
			if(!$this->do_i_own_this($post['contact_id']))
				return array("status"=>false,"msg"=>"You cannot edit contacts that do not belong to you!");
			
			// HANDLE CUSTOM FIELDS
			$default_form_options = $this->user->get_default_form_fields();
			$custom_fields = array();
			$i = 0;
			foreach($default_form_options as $default_form_field):
				if(isset($post[$default_form_field['id']])){
					$custom_fields[$i]['id'] = $default_form_field['id'];
					$custom_fields[$i]['value'] = str_replace("\r\n","",$post[$default_form_field['id']]);					
					$i++;
				}
			endforeach;
			
			$custom_json = json_encode($custom_fields);

			$update = "
			UPDATE user_contacts 
			SET
			fullname ='?',
			company = '?',
			address = '?',
			city = '?',
			state = '?',
			zip = '?',
			email = '?',
			phone = '?',
			cell = '?',
			typeofperson = '?',
			custom_fields = '?',
			notes = '?',
			deleted = '?' 
			WHERE id = '?' 
			AND user_id = '?' 
			LIMIT 1";
			$this->db->qry($update,str_replace(',',' ',$post['fullname']),str_replace(',',' ',$post['company']),str_replace(',',' ',$post['address']),str_replace(',',' ',$post['city']),str_replace(',',' ',$post['state']),str_replace(',',' ',$post['zip']),str_replace(',',' ',$post['email']),str_replace(',',' ',$post['phone']),str_replace(',',' ',$post['cell']),str_replace(',',' ',$post['typeofperson']),$custom_json,str_replace(',',' ',$post['notes']),$post['deleted'],$post['contact_id'],$this->user->user_id);

			if(mysql_affected_rows($this->db->databaseLink)):
				return array("status"=>true,"msg"=>"Contact Updated.","contact_id"=>$post['contact_id']);
			else:
				return array("status"=>false,"msg"=>"Error Updating Contact.");
			endif;
			
		}
		
		public function get_one($contact_id){
				// get one contact from contact id
				if($this->do_i_own_this($contact_id))
					return array("status"=>true,"msg"=>"Get contact.","contact"=>$this->get_contact($contact_id));
				else
					return array("status"=>false,"msg"=>"You do not own this contact.");
		}
		public function get_contact($contact_id){
			$s = "SELECT * FROM user_contacts WHERE user_id = '?' AND id = '?' AND deleted = 0";
			$q = $this->db->qry($s,$this->user->user_id,$contact_id);
			$r = mysql_fetch_assoc($q);
			return $r;	
		}
		public function do_i_own_this($contact_id){
			// Does the logged in user have access to contact?
			$s = "SELECT * FROM user_contacts WHERE id = '?' AND user_id = '?'";
			$q = $this->db->qry($s,$contact_id,$this->user->user_id);
			if(mysql_num_rows($q))
				return true;
			return false;
		}
		public function next_pagination($start){
			$s = "SELECT * FROM user_contacts WHERE user_id = '?'";
			$q = $this->db->qry($s,$this->user->user_id);
			
			if(mysql_num_rows($q) > ($start + $this->pagination_limit)):
				return "<a href='view_contacts.php?start=".($start+$this->pagination_limit)."'>Next</a>";
			endif;
		}
		public function prev_pagination($start){
			$s = "SELECT * FROM user_contacts WHERE user_id = '?' AND deleted = 0";
			$q = $this->db->qry($s,$this->user->user_id);
			if(($start+1 > $this->pagination_limit)):
				return "<a href='view_contacts.php?start=".($start-$this->pagination_limit)."'>Prev</a>";
			endif;
				
		}
		public function total_pages(){
			$s = "SELECT * FROM user_contacts WHERE user_id = '?' AND deleted = 0";
			$q = $this->db->qry($s,$this->user->user_id);
			$results = mysql_num_rows($q);
			// i.e.  100 results, limit is 50,  results / limit = 2 pages...		
			return (ceil($results / $this->pagination_limit)); 
		}

		public function delete(){
			if(!$this->do_i_own_this($this->contact_id))
				return array("status"=>false,"msg"=>"You cannot delete contacts you do not own!");

			$u = "UPDATE user_contacts SET deleted = 1,date_deleted = DATE(NOW()) WHERE user_id = '?' AND id = '?' LIMIT 1";
			$this->db->qry($u,$this->user->user_id,$this->contact_id);
			return array("status"=>true,"msg"=>"Contact Deleted.");
		}

		public function insert_new_contact($postarray){
		
			$fullname = str_replace(',',' ',$postarray['fullname']);
			$company = str_replace(',',' ',$postarray['company']);
			$address = str_replace(',',' ',$postarray['address']);
			$city = str_replace(',',' ',$postarray['city']);
			$state = str_replace(',',' ',$postarray['state']);
			$zip = str_replace(',',' ',$postarray['zip']);
			$email = str_replace(',',' ',$postarray['email']);
			$phone = str_replace(',',' ',$postarray['phone']);
			$cell = str_replace(',',' ',$postarray['cell']);
			$typeofperson = str_replace(',',' ',$postarray['typeofperson']);
			$notes = str_replace(',',' ',$postarray['notes']);																					
			// Get user Form Fields
			$custom_form_fields = $this->user->get_default_form_fields();
			$custom_json = array();	
			
			
			
			
			
			foreach($custom_form_fields as $custom_form_field):
				$tmp_label = $postarray[$custom_form_field['label']];
				$tmp_master_form_id = $custom_form_field['id'];
				$tmp_value = $postarray[$custom_form_field['id']];
				$custom_json[] = array("id"=>$tmp_master_form_id,"value"=>$tmp_value);
			endforeach;
			$custom_json = json_encode($custom_json);

			//$this->db->printQuery = TRUE;
			$insert  = "INSERT INTO user_contacts(user_id,fullname,company,address,city,state,zip,email,phone,cell,typeofperson,custom_fields,notes)
			values('?','?','?','?','?','?','?','?','?','?','?','?','?')";
			
			if($this->db->qry($insert,$this->user->user_id,$fullname,$company,$address,$city,$state,$zip,$email,$phone,$cell,$typeofperson,$custom_json,str_replace(',',' ',$notes))):
				return array("status"=>true,"msg"=>"Contact Added.","contact_id"=>mysql_insert_id($this->db->databaseLink));
			else:
				return array("status"=>false,"msg"=>"Error adding contact.");		
			endif;

		}
		
		
		
	
		public function export(){
			$download_dir = $this->user->get_private_dir();
			
			$filename = "contacts_".$this->user->user_id."_".date('m-d-Y_hi').'_'.uniqid().'.csv';
			
			$new_path = dirname(__FILE__).'/../../e/'.$download_dir.'/'.$filename;
			header("Content-type: text/csv");
			header("Content-Disposition: attachment; filename=$filename");
			header("Pragma: no-cache");
			header("Expires: 0");
			
			
			$coma_limited = $this->coma_limited();
			// Save to Server.
			$h = fopen($new_path,"w");
			fwrite($h,$coma_limited);
			fclose($h);
			// Show download link.
			return $coma_limited;

	}	
	private function custom_fields_available(){
		$s = "SELECT * FROM master_form";
		$q = $this->db->qry($s);
		$labels = array();
		$i = 0;
		while($row = mysql_fetch_assoc($q)):
			$labels[$i]['id'] = $row['id'];
			$labels[$i]['label'] = $row['label'];
			$labels[$i]['value'] = NULL;	
			$i++;
		endwhile;
		
		return $labels;
	
	}
	public function coma_limited($contact_id = NULL){
			$coma_limited = "email,contact_id,fullname,company,address,city,state,zip,phone,cell,typeofperson,";
			$avail_labels = $this->custom_fields_available();
			foreach($avail_labels as $label){
				$coma_limited .= $label['label'].",";
			}
			
			$coma_limited .= "notes";
			$coma_limited .= "\r\n";
							
			// override default return limit...
			$this->pagination_limit = 999999;
		
			if(is_null($contact_id))
				$contacts = $this->get_all(0);
			else
				$contacts = array($this->get_contact($contact_id));
			

				
			foreach($contacts as $contact){

				$coma_limited .= str_replace(","," ",$contact['email']);
				$coma_limited .= ",";

				$coma_limited .= str_replace(","," ",$contact['id']);
				$coma_limited .= ",";

				$coma_limited .= str_replace(","," ",$contact['fullname']);
				$coma_limited .= ",";
		
				$coma_limited .= str_replace(","," ",$contact['company']);
				$coma_limited .= ",";


				$coma_limited .= str_replace(","," ",$contact['address']);
				$coma_limited .= ",";

				$coma_limited .= str_replace(","," ",$contact['city']);
				$coma_limited .= ",";

				$coma_limited .= str_replace(","," ",$contact['state']);
				$coma_limited .= ",";

				$coma_limited .= str_replace(","," ",$contact['zip']);
				$coma_limited .= ",";

				$coma_limited .= str_replace(","," ",$contact['phone']);
				$coma_limited .= ",";

				$coma_limited .= str_replace(","," ",$contact['cell']);
				$coma_limited .= ",";

				$coma_limited .= str_replace(","," ",$contact['typeofperson']);
				$coma_limited .= ",";

			// CUSTOM FIELDS
			// FOR EACH LABEL
			
			foreach($avail_labels as $k=>$this_label){
				// CHECK IF HAS VALUE... 
				foreach(json_decode($contact['custom_fields']) as $custom_field){
					if($custom_field->id == $this_label['id']):
						$avail_labels[$k]['value'] = str_replace(',',' ',$custom_field->value);	
						break;
					endif;
				}
				$coma_limited .= str_replace("\n","",str_replace("\r","",str_replace(","," ",$avail_labels[$k]['value']))).',';
			}
				
				$coma_limited .= str_replace(","," ",$contact['notes']);
				$coma_limited .= "\r\n";				
			}			
			return $coma_limited;	
	}
	
	public function list_contact_exports(){
		
		$scandir = scandir(dirname(__FILE__).'/../../e/'.$this->user->get_private_dir());
		
		$files = array();

		foreach($scandir as $dir):
		if($dir == '.' || $dir == '..') continue;
			$preg = preg_split("/contacts_([0-9]+)_/",$dir);
			$date_preg = preg_split('/_(([0-9])([0-9]))(([0-9])([0-9]))/',$preg[1]);

			$time_preg = preg_split('/(([0-9]+)-([0-9]+)-([0-9]+)_)/',$preg[1]);
			$time_preg = preg_split('/_([0-9a-zA-Z]+)/',$time_preg[1]);			
			$time_preg = preg_split('/\.csv/',$time_preg[0]);  
			//$time_preg = substr_replace($time_preg[1],':',1);
			$time = substr_replace($time_preg[0], ':', 2, 0);
			$date = $date_preg[0];

			$files[] = array("datetime"=>$date.' '.$time,"filename"=>$dir);
		endforeach;
		
		return $files;
	
		//RETURN
		//[{datetime=>"",filename=>""},{}]
		
	}

}

?>
