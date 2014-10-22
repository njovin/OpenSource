<?php session_start(); $title = "Reset";$safe_title = "reset"; $access_level = 0;

if(isset($_SESSION['userID'])){ // user already logged in.
	header('Location: index.php');
}

elseif(!isset($_GET['hq'])){ // missing password hash..
	header('Location: index.php');	
}

elseif(isset($_POST['new_password'])){

	require_once('php/class/user.php');
	$user = new User();

	if(!$user->hash_is_valid($_GET['hq'])){
		header('Location: index.php');	
	} // end if get password hash is NOT valid...
	else{ // if has IS valid
		
			if($user->update_password($_GET['hq'],$_POST['new_password'])){
				// UPDATING SUCCESS
				header('Location: index.php');
			}
			else{
				// ERROR updating			
				header('Location: index.php');
			}
				
	}
}
else{
//	do nothing
	//header('Location: ?m=el');	
}
?>
<?php include('inc/header.php');?>
<div class="text-center">
<h1><?php echo $general->welcome_logo_html;?></h1>
<h2>Reset Password.</h2>
<p>Please type your email and we will send you a reset link to your email address.</p>

<form class="form-horizontal" action="reset.php?hq=<?php echo strip_tags($_GET['hq']);?>" method="post">
<fieldset>

<!-- Form Name -->

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="new_password">Password</label>  
  <div class="col-md-4">
  <input id="new_password" name="new_password" placeholder="Password" class="form-control input-md" required type="text">
  <span class="help-block">please type your new password</span>  
  </div>
</div>
<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="singlebutton"></label>
  <div class="col-md-4">
    <button id="singlebutton" name="singlebutton" type="submit" class="btn btn-primary">Save</button>
  </div>
</div>
</fieldset>
</form>
</div>

<?php include('inc/footer.php');?>