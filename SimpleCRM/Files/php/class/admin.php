<?php
require_once('db.php');
require_once('user.php');
require_once('general.php');
##### ADMIN ONLY FUNCTIONS #####
class Admin extends User{
	private $general;

	public function __construct($user_id = FALSE){
		parent::__construct($user_id);
		$this->general = new General();
	}

	
	
	public function create_account($email,$password,$level){
		if(!$this->is_admin):
			return array("status"=>false,"msg"=>"You are NOT an admin!");
		elseif($this->does_email_exist($email)):
			return array("status"=>false,"msg"=>"User email already exists!");
		else:
			$sql = "INSERT into users(email,password,level) VALUES('?','?','?')";		
			$this->db->qry($sql,$email,$this->general->encrypt($password,$this->general->salt),$level);
			return array("status"=>true,"msg"=>"User added.");
		endif;
		
		return array("status"=>false,"msg"=>"An unknown error occured [create_account:ln59]");
		
	}
	public function edit($user_id,$email,$password,$level,$active,$deleted){
		if(!$this->is_admin)
			return array("status"=>false,"msg"=>"You are NOT an admin!");
		if(($this->level != $level && $this->user_id == $user_id) || ($this->user_id == $user_id && $active != $this->active))
			return array("status"=>false,"msg"=>"You can only edit your own email.");
		if($deleted == '1'){
			$u = "DELETE FROM users WHERE id = '?' LIMIT 1";
			$this->db->qry($u,$user_id);
			return array("status"=>true,"msg"=>"User Deleted.","redirect"=>true);	
		}
		$u = "UPDATE users SET email = '?', password = '?', level = '?',active = '?',deleted = '?' WHERE id = '?' LIMIT 1";
		$this->db->qry($u,$email,$this->general->encrypt($password,$this->general->salt),$level,$active,$deleted,$user_id);
		return array("status"=>true,"msg"=>"User Updated.");
	}
	
	public function list_users(){
		if(!$this->is_admin)
			return array("status"=>false,"msg"=>"You are NOT an admin!");
		$s = "SELECT users.id,users.email,users.level,users.active,levels.name as levelname FROM users JOIN levels ON users.level = levels.id WHERE users.deleted = 0 ORDER BY levelname ASC";
		$q = $this->db->qry($s);
		$users = array();
		
		while($row = mysql_fetch_assoc($q)):
			$users[] = $row;
		endwhile;
		
		return $users;
	}
	
	public function suggest_list_user($entered_text){
	
		if(!$this->is_admin)
			return array("status"=>false,"msg"=>"You are NOT an admin!");
		$s = "SELECT id as id,email as value FROM users WHERE deleted = 0 AND email like '?'";
		$q = $this->db->qry($s,'%'.$entered_text.'%');
		$users = array();
		
		while($row = mysql_fetch_assoc($q)):
			$users[] = $row;
		endwhile;
		
		return $users;
	}	
	
	public function edit_user($user_id,$email,$level,$password){
		if(!$this->is_admin)
			return array("status"=>false,"msg"=>"You are NOT an admin!");
		$update = "UPDATE users SET email = '?',password='?', level='?' WHERE id ='?'";
		$this->db->qry($email_update,$email,$this->general->encrypt($password,$this->general->salt),$level,$user_id);
		return array("status"=>true,"msg"=>"User updated.");
	}
	
	public function get_default_form_fields(){
		$s = "SELECT master_form.id,master_form.label,master_form.active,master_form_values.value FROM master_form LEFT JOIN master_form_values ON master_form.id = master_form_values.master_form_id";
		$q = $this->db->qry($s);
		
		$form_fields = array();
		
		while($form_field = mysql_fetch_assoc($q)):
		//id,label,active,values	
			$form_fields[$form_field['id']]['id'] = $form_field['id'];
			$form_fields[$form_field['id']]['label'] = $form_field['label'];
//			$form_fields[$form_field['id']]['required'] = $form_field['required'];
			$form_fields[$form_field['id']]['active'] = $form_field['active'];
			$form_fields[$form_field['id']]['values'][] = $form_field['value'];  
		
		endwhile;
		
		return $form_fields;
		
	}
	
	public function update_default_form_values($postarray){
		if(!$this->is_admin)
				return array("status"=>false,"msg"=>"You must be an admin.");

		# Empty the existing values to prevent duplicates and READ deletions...
		$this->empty_form_defaults();
		foreach($postarray as $postK=>$postV){
			//echo $postK.'....'.$postV.'...<br>';
	
			$master_form_id = $postK;
			$this_active = $_POST['active_'.$master_form_id];
			//$this_required = $_POST['required_'.$master_form_id];
			$this->set_form_field_active($master_form_id,$this_active);
			$values = $postV;
			$form_field = explode(PHP_EOL, $values);
			$form_field  = $this->remove_empties($form_field);
			
			foreach($form_field as $value){
				$this->update_form_field($master_form_id,$value);
			}
			
		} 
		$this->clean_up_form_defaults();
		return array("status"=>true,"msg"=>"Form fields updated.");
	}
	private function clean_up_form_defaults(){
		$d = "DELETE FROM master_form_values WHERE master_form_id LIKE '?'";
		$this->db->qry($d,'active_%');
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
		$s = "INSERT INTO master_form_values(master_form_id,value) values('?','?')";
		$this->db->qry($s,$master_form_default_id,$new_value);
				
	}
	
	private function set_form_field_active($id,$active = 1){
		
		$UPDATE = "UPDATE master_form SET active = '?' WHERE id = '?' LIMIT 1";
		
		$this->db->qry($UPDATE,$active,$id);
		
		}

	
	private function empty_form_defaults(){
		$d = "DELETE FROM master_form_values";
		$this->db->qry($d);
	}

	private function csv_to_array($filename='', $delimiter=',')
	{
	if(!file_exists($filename) || !is_readable($filename))
	return FALSE;
	$header = NULL;
	$data = array();
	if (($handle = fopen($filename, 'r')) !== FALSE)
	{
	while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
	{
	if(!$header)
	$header = $row;
	else
	$data[] = array_combine($header, $row);
	}
	fclose($handle);
	}
	return $data;
	}
	
	public function users_from_csv($filename){

		$array = $this->csv_to_array($filename);
		$skipped = array();
		$skip_bool = false;
		$skip_count = 0;
		$import_count = 0;
		$import_bool = false;
		foreach($array as $k):
			//print_r($k);
			$email = $k['email'];
			$password = $k['password']; 
			$level = $k['level'];
			$active = $k['active'];
			// skip if user email is already in there..
			if($this->does_email_exist($email)):$skipped[] = $email; $skip_bool = true; $skip_count++; continue; endif;
			$import_bool = true;
			$import_count++;
			$q = "INSERT into users(email,password,level,active) VALUES('?','?','?','?')";
			$this->db->qry($q,$email,$this->general->encrypt($password,$this->general->salt),$level,$active);
		endforeach;
		return array("status"=>true,"msg"=>$import_count. " user(s) imported.","import_bool"=>$import_bool,"import_count"=>$import_count,"skipped"=>$skipped,"skip_bool"=>$skip_bool,"skip_count"=>$skip_count);
		
	}
}

	

?>
