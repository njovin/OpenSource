<?php session_start(); $title = "Add Contact"; $safe_title="add_contact"; $access_level = 1;?>
<?php include('inc/header.php');?>
<?php if(isset($_POST['fullname'])):?>
	<?php
    	$contact = new Contact($user);
		$insert_contact = $contact->insert_new_contact($_POST);
		$msg = $insert_contact['msg'];

		if($insert_contact['status']){
			$contact_id = $insert_contact['contact_id'];
			echo $general->js("alertify.success('".$msg."');");
			if($general->ftp)
				echo $general->js("export_contact('".$contact_id."');");
		}
		else{
			echo $general->js("alertify.error('".$msg."');");		
		}
	?>
<?php endif;?>
<div id="add_contact" class="text-center">
<form class="form-horizontal" action="" method="POST">
<fieldset>

<!-- Form Name -->
<legend>Insert Contact.</legend>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="fullname">First Name*</label>  
  <div class="col-md-4">
  <input id="fullname" name="fullname" placeholder="Full Name" class="form-control input-md" required="" type="text">
    
  </div>
</div>


<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="company">Company</label>  
  <div class="col-md-4">
  <input id="company" name="company" placeholder="Company" class="form-control input-md" type="text">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="address">Address</label>  
  <div class="col-md-4">
  <input id="address" name="address" placeholder="Address" class="form-control input-md" type="text">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="city">City</label>  
  <div class="col-md-4">
  <input id="city" name="city" placeholder="City" class="form-control input-md" type="text">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="state">State</label>  
  <div class="col-md-4">
  <input id="state" name="state" placeholder="State" class="form-control input-md" type="text">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="zip">Zip</label>  
  <div class="col-md-4">
  <input id="zip" name="zip" placeholder="Zip" class="form-control input-md" type="text">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="email">Email</label>  
  <div class="col-md-4">
  <input id="email" name="email" placeholder="Email" class="form-control input-md" type="text">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="phone">Phone</label>  
  <div class="col-md-4">
  <input id="phone" name="phone" placeholder="Phone" class="form-control input-md" type="text">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="cell">Cell</label>  
  <div class="col-md-4">
  <input id="cell" name="cell" placeholder="Cell" class="form-control input-md" type="text">
    
  </div>
</div>

<!-- Select Basic -->
<div class="form-group">
  <label class="col-md-4 control-label" for="typeofperson">Type of Person</label>
  <div class="col-md-4">
    <select id="typeofperson" name="typeofperson" class="form-control">
      <option value="Customer">Customer</option>
      <option value="Non-Customer">Non-Customer</option>
    </select>
  </div>
</div>



<!-- Select Basic -->
<?php foreach($user->get_default_form_fields() as $form_field): if($form_field['active'] == 0): continue; endif;?>
<div class="form-group">
  <label class="col-md-4 control-label" for="<?php echo $form_field['id'];?>"><?php echo $form_field['label'];?></label>
  <div class="col-md-4">
    <select id="<?php echo $form_field['id'];?>" name="<?php echo $form_field['id'];?>" class="form-control">
    <?php if(isset($_POST[$form_field['id']]) && $_POST[$form_field['id']] != "0"):?>
		<option value="<?php echo $form_field['id'];?>" selected >-<?php echo $_POST[$form_field['id']];?>-</option>
	<?php endif;?>
		<option value="0">N/A</option>
		<?php foreach($form_field['values'] as $value):?>
        	<option value="<?php echo $value;?>"><?php echo $value;?></option>
        <?php endforeach;?>
    </select>
  </div>
</div>
<?php endforeach;?>


<!-- Textarea -->
<div class="form-group">
  <label class="col-md-4 control-label" for="notes">Notes</label>
  <div class="col-md-4">                     
    <textarea class="form-control no_comma" id="notes" name="notes"  maxlength="255"></textarea>
  </div>
</div>

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="add_contact_btn"></label>
  <div class="col-md-4">
    <button id="add_contact_btn" name="add_contact_btn" class="btn btn-primary">Save Contact</button>
  </div>
</div>

</fieldset>
</form>


</div>
<?php include('inc/footer.php');?>
