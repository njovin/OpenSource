<?php

class Database
{

	// EDIT HERE
		private $hostname_logon_live = 'database_host'; 
		private $database_logon_live = 'database_name'; 
		private $username_logon_live = 'database_username'; 
		private $password_logon_live = 'database_password';
	// STOP EDIT HERE
    
 	public $printQuery = FALSE;
	
	public $useLiveDatabase = TRUE;
 
 	public $databaseLink;
    
 
    //connect to database
    function dbconnect(){
        $this->databaseLink = mysql_connect($this->getHostName(), $this->getUserName(), $this->getPassword()) or die ('Unable to connect to the database');
         mysql_select_db($this->getDatabase()) or die ('Unable to select database!');
        return;
    }
	
	 //prevent injection
    function qry($query) {
      $this->dbconnect();
      $args  = func_get_args();
      $query = array_shift($args);
      $query = str_replace("?", "%s", $query);
      $args  = array_map('mysql_real_escape_string', $args);
      array_unshift($args,$query);
      $query = call_user_func_array('sprintf',$args);
	  if($this->printQuery)
	  	echo $query;
	  
      $result = mysql_query($query, $this->databaseLink) or die(mysql_error());
          if($result){
            return $result;
          }else{
             $error = "Error";
             return $result;
          }
    }
	
	function dbClose()
	{
		mysql_close($this->databaseLink);
	}
	function getHostName()
	{
		if($this->useLiveDatabase)
			return $this->hostname_logon_live;
		else
			return $this->hostname_logon_dev;
			
	}
	function getDatabase()
	{
		if($this->useLiveDatabase)
			return $this->database_logon_live;
		else
			return $this->database_logon_dev;
	}
	function getUserName()
	{
		if($this->useLiveDatabase)
			return $this->username_logon_live;
		else
			return $this->username_logon_dev;
	}
	function getPassword()
	{
		if($this->useLiveDatabase)
			return $this->password_logon_live;
		else
			return $this->password_logon_dev;
	}
}

?>
