<?php session_start(); $title = "Edit User";$safe_title="edit_user"; $access_level = 2;?>
<?php include('inc/header.php');?>
<?php

if(isset($_POST['selectuserlevel'])):
	$edit = $admin->edit($_GET['id'],$_POST['user_email'],$_POST['user_password'],$_POST['selectuserlevel'],$_POST['active'],$_POST['deleted']);
	$message = $edit['msg'];
	if($edit['status']):
		if(isset($edit['redirect']) && $edit['redirect'] == true){
			echo $general->js("document.location = '/list_users.php?del=true'");
		}
		else{
			echo $general->js("alertify.success('".$message."');");
		}
	else:
		echo $general->js("alertify.error('".$message."');");
	endif;		
endif;
if(!isset($_GET['id'])):
	echo "Missing Parameters.";
else:
$u = new User($_GET['id']);
?>
<div id="edit_user" class="text-center">
	<form class="form-horizontal" action="" method="post" role="form" id="edit_user_form" data-toggle="validator">
<fieldset>

<!-- Form Name -->
<legend>Edit User.</legend>
<!-- Text input-->
<div class="control-group">
  <label class="control-label" for="user_email">Email</label>
  <div class="controls form-group">
    <input id="user_email" name="user_email" placeholder="email address" class="input-xlarge" required="" value="<?php echo $u->email;?>" type="email" data-error="Not a valid email address.">
    <p class="help-block">(ex: email@example.com )</p>
  </div>
</div>
<!-- Password input-->
<div class="control-group">
  <label class="control-label" for="user_password">Password</label>
  <div class="controls form-group">
    <input id="user_password" data-minlength="6" name="user_password" placeholder="your password" class="input-xlarge" required="" value="<?php echo $u->password;?>" type="text">
    <span class="help-block">Minimum of 6 characters</span>
  </div>
</div>
<!-- select basic -->
<div class="control-group">
  <label class="control-label" for="selectuserlevel">User Level</label>
  <div class="controls form-group">
    <select id="selectuserlevel" name="selectuserlevel" class="input-xlarge">
    <?php foreach($general->get_user_levels() as $level):?>
    <option <?php if($level['id'] == $u->level) { echo " selected "; }?> value='<?php echo $level['id'];?>'><?php echo $level['name'];?></option>
    <?php endforeach;?>
    </select>
  </div>
</div>

<!-- select basic -->
<div class="control-group">
  <label class="control-label" for="active">Account Status</label>
  <div class="controls form-group">
    <select name="active" class="input-xlarge">
    	<option value="0" <?php if($u->active == "0"){ echo " selected ";}?>>Disabled</option>
    	<option value="1" <?php if($u->active == "1"){ echo " selected ";}?>>Enabled</option>
    </select>
  </div>
</div>

<!-- select basic -->
<div class="control-group">
  <label class="control-label" for="active">Delete</label>
  <div class="controls form-group">
    <select name="deleted" class="input-xlarge">
    	<option value="0">No</option>
    	<option value="1">Yes</option>
    </select>
  </div>
</div>

<!-- Button -->
<div class="control-group">
  <label class="control-label" for="user_submit_btn"></label>
  <div class="controls form-group">
    <button id="user_submit_btn" name="user_submit_btn" class="btn btn-primary">Go!</button>
  </div>
</div>

</fieldset>
</form>
</div>
<?php endif;?>
<?php include('inc/footer.php');?>
