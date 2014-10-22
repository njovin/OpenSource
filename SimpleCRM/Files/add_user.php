<?php session_start(); $title = "Add User";$safe_title="add_user"; $access_level = 2;?>
<?php include('inc/header.php');?>
<?php
if(isset($_POST['selectuserlevel'])):
	$create = $admin->create_account($_POST['user_email'],$_POST['user_password'],$_POST['selectuserlevel']);
	$message = $create['msg'];
	if($create['status']):
		echo $general->js("alertify.success('".$message."');");
	else:
		echo $general->js("alertify.error('".$message."');");
	endif;		
endif;
?>
<div id="add_user" class="text-center">
	<form class="form-horizontal" action="" method="post" role="form" id="add_user_form" data-toggle="validator">
<fieldset>

<!-- Form Name -->
<legend>Add User.</legend>
<!-- Text input-->
<div class="control-group">
  <label class="control-label" for="user_email">Email</label>
  <div class="controls form-group">
    <input id="user_email" name="user_email" placeholder="email address" class="input-xlarge" required="" type="email" data-error="Not a valid email address.">
    <p class="help-block">(ex: email@example.com )</p>
  </div>
</div>
<!-- select basic -->
<div class="control-group">
  <label class="control-label" for="selectuserlevel">User Level</label>
  <div class="controls form-group">
    <select id="selectuserlevel" name="selectuserlevel" class="input-xlarge">
    <?php foreach($general->get_user_levels() as $level):?>
    	<option value='<?php echo $level['id'];?>'><?php echo $level['name'];?></option>
    <?php endforeach;?>
    </select>
  </div>
</div>

<!-- Password input-->
<div class="control-group">
  <label class="control-label" for="user_password">Password</label>
  <div class="controls form-group">
    <input id="user_password" data-minlength="6" name="user_password" placeholder="your password" class="input-xlarge" required="" type="text">
    <span class="help-block">Minimum of 6 characters</span>
  </div>
</div>
<!-- Password input-->
<!--<div class="control-group">
  <label class="control-label" for="user_password2">Re-type Password</label>
  <div class="controls form-group">
    <input id="user_password2" name="user_password2" placeholder="your password" class="input-xlarge" required="" type="text" data-match="#user_password" data-match-error="Whoops, these don't match">
     <div class="help-block with-errors"></div>
  </div>
 
</div>-->
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
<?php include('inc/footer.php');?>
