<?php session_start(); $title = "Edit Contact";$safe_title="edit_contact"; $access_level = 1;?>
<?php include('inc/header.php');?>
<?php
$C = new Contact($user);
if(isset($_POST['fullname'])):
	$update = $C->update_contact($_POST);
	$message = $update['msg'];
	if($update['status']):
		
		if($_POST['deleted'] != '1'):
			$contact_id = $update['contact_id'];
			echo $general->js("alertify.success('".$message."');");
			if($general->ftp):
				echo $general->js("export_contact('".$contact_id."');");
			endif;
		else:
			echo $general->js("document.location = 'view_contacts.php';");
		endif;		
	else:
		echo $general->js("alertify.error('".$message."');");
	endif;		
endif;

$contact = $C->get_one($_GET['id']);
if(!$contact['status']):
	echo $contact['msg'];
else:	
$contact = $contact['contact'];
$fullname = $contact['fullname'];
$company = $contact['company'];
$address = $contact['address'];
$city = $contact['city'];
$state = $contact['state'];
$zip = $contact['zip'];
$email = $contact['email'];
$phone = $contact['phone'];
$cell = $contact['cell'];
$typeofperson = $contact['typeofperson'];
// CUSTOM FIELDS
$custom_json = json_decode($contact['custom_fields']);
$deleted = $contact['deleted'];
$notes = $contact['notes'];
?>
<div id="add_contact" class="text-center">
<form class="form-horizontal" action="" method="POST">
<fieldset>

<!-- Form Name -->
<legend>Edit Contact.</legend>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="fullname">Full Name*</label>  
  <div class="col-md-4">
  <input id="fullname" name="fullname" value="<?php echo $fullname;?>" placeholder="Full Name" class="form-control input-md" required type="text">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="company">Company</label>  
  <div class="col-md-4">
  <input id="company" name="company" placeholder="Company" value="<?php echo $company;?>"  class="form-control input-md" type="text">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="address">Address</label>  
  <div class="col-md-4">
  <input id="address" name="address" placeholder="Address" class="form-control input-md" value="<?php echo $address;?>"  type="text">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="city">City</label>  
  <div class="col-md-4">
  <input id="city" name="city" placeholder="City" class="form-control input-md" type="text" value="<?php echo $city;?>" >
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="state">State</label>  
  <div class="col-md-4">
  <input id="state" name="state" placeholder="State" class="form-control input-md" type="text" value="<?php echo $state;?>" >
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="zip">Zip</label>  
  <div class="col-md-4">
  <input id="zip" name="zip" placeholder="Zip" class="form-control input-md" type="text" value="<?php echo $zip;?>" >
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="email">Email</label>  
  <div class="col-md-4">
  <input id="email" name="email" placeholder="Email" class="form-control input-md" type="text"  value="<?php echo $email;?>" >
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="phone">Phone</label>  
  <div class="col-md-4">
  <input id="phone" name="phone" placeholder="Phone" class="form-control input-md" type="text"  value="<?php echo $phone;?>" >
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="cell">Cell</label>  
  <div class="col-md-4">
  <input id="cell" name="cell" placeholder="Cell" class="form-control input-md" type="text" value="<?php echo $cell;?>" >
    
  </div>
</div>

<!-- Select Basic -->
<div class="form-group">
  <label class="col-md-4 control-label" for="typeofperson">Type of Person</label>
  <div class="col-md-2">
    <select id="typeofperson" name="typeofperson" class="form-control">
      <option value="Customer" <?php if($typeofperson == "Customer") echo "selected";?>>Customer</option>
      <option value="Non-Customer" <?php if($typeofperson == "Non-Customer") echo "selected";?>>Non-Customer</option>
    </select>
  </div>
</div>



<!-- Select Basic -->
<?php foreach($user->get_default_form_fields() as $form_field): if($form_field['active'] == 0): continue; endif;?>
<div class="form-group">
  <label class="col-md-4 control-label" for="<?php echo $form_field['id'];?>"><?php echo $form_field['label'];?></label>
  <div class="col-md-2">
    <select id="<?php echo $form_field['id'];?>" name="<?php echo $form_field['id'];?>" class="form-control">
    <option value="0">N/A</option>
					<?php $mark_my_words = "";?>
		<?php foreach($form_field['values'] as $value):?>
            <?php foreach($custom_json as $json_std):?>
					<?php $mark_my_words = "";?>
				<?php //if($json_std->id == $form_field['id']):
						// Entered the right FORM FIELD
						if(trim($json_std->value) == trim($value) && $json_std->id == $form_field['id']){
							// same if, same value;
							

							$mark_my_words = " selected ";	
							break;
						}
					//endif; ?>
            <?php endforeach;?>

        	<option value="<?php echo trim(str_replace("\r\n","",$value));?>" <?php echo $mark_my_words;?>><?php echo trim(str_replace("\r\n","",$value));?></option>
        <?php endforeach;?>
    </select>
  </div>
</div>
<?php endforeach;?>


<!-- Textarea -->
<div class="form-group">
  <label class="col-md-4 control-label" for="notes">Notes</label>
  <div class="col-md-4">                     
    <textarea class="form-control" id="notes" name="notes"  maxlength="255"><?php echo $notes;?></textarea>
  </div>
</div>
<!-- Select Basic -->
<div class="form-group">
  <label class="col-md-4 control-label" for="deleted">Delete?</label>
  <div class="col-md-1">
    <select id="deleted" name="deleted" class="form-control">
      <option value="1" <?php if($deleted == "1") echo "selected";?>>Yes</option>
      <option value="0" <?php if($deleted == "0") echo "selected";?>>No</option>
    </select>
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
<input type="hidden" name="contact_id" value="<?php echo $_GET['id'];?>" />
</form>


</div>
<?php endif;?>
<?php include('inc/footer.php');?>
