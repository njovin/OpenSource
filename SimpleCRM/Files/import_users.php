<?php session_start(); $title = "Import Users";$safe_title="import_users"; $access_level = 2;?>
<?php include('inc/header.php');?>
<?php
if(isset($_FILES['my_csv'])):
	// Move uploaded file.
	$tempLocation = "thisCSV".uniqid().'.csv';
	move_uploaded_file($_FILES['my_csv']['tmp_name'],$tempLocation);
	// Send file to parser.
	$create = $admin->users_from_csv($tempLocation);

	
	@unlink($tempLocation);	
	$message = $create['msg'];
	if($create['import_bool']):

		echo $general->js("alertify.success('".$message."');");
	else:
		echo $general->js("alertify.error('No users imported.');");
	endif;		
	if($create['skip_bool']):
		 echo $general->js("alertify.error('".$create['skip_count']." user(s) skipped.');");
	endif;	
endif;
?>
<div id="add_user" class="text-center">
	<form class="form-horizontal" action="" method="post" enctype="multipart/form-data" role="form" id="import_users_form" data-toggle="validator">
<fieldset>

<!-- Form Name -->
<legend>Import Users.</legend>
<!-- Text input-->
<div class="control-group" style="max-width:250px;width:100%;margin-left:auto;margin-right:auto;">
  <label class="control-label" for="my_csv">Plase select a CSV.</label>
  <div class="controls form-group">
    <input id="my_csv" name="my_csv" placeholder="comma seperated file" class="input-xlarge" required="" type="file">
  </div>
</div>

</div>
<!-- Button -->
<div class="control-group text-center"  style="max-width:250px;width:100%;margin-left:auto;margin-right:auto;">
  <label class="control-label" for="import_submit_btn"></label>
  <div class="controls form-group">
    <button id="import_submit_btn" name="import_submit_btn" class="btn btn-primary" style="width:100%;">Import!</button>
  </div>
</div>

</fieldset>
</form>
</div>
<?php include('inc/footer.php');?>
