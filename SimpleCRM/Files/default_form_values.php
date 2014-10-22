<?php session_start(); $title = "Default Form Values";$safe_title="default_form_values"; $access_level = 2;?>
<?php include('inc/header.php');?>
<h2 class="text-center">List Default Values</h2>
<?php if(isset($_POST['update_the_values'])): 
	// ONLY USED TO CHECK IF FORM POSTED... Needed to unset because it was getting added to the database!
	unset($_POST['update_the_values']); 
	$update = $admin->update_default_form_values($_POST);
	if($update['status'])
		echo $general->js("alertify.success('".$update['msg']."');");
	else
		echo $general->js("alertify.error('".$update['msg']."');");
		

endif;?>
<form action="" method="post" name="edit_default_form_values" class="printed_menu">
<?php $form_fields = $admin->get_default_form_fields(); ?>
<?php foreach($form_fields as $form_field):?>
<?php 
$thisID = $form_field['id'];
$thisLABEL = $form_field['label'];
?><div class="edit_form_field_wrap">
	<button class="show_form_field btn btn-default" type="button" data-showthis="<?php echo $thisID;?>_field"><?php echo $thisLABEL;?></button>
	<div class="edit_form_field_item text-center" id="<?php echo $thisID;?>_field">
		<h3><?php echo $thisLABEL;?></h3>
		Active: <input type="checkbox" name="active_<?php echo $thisID;?>" id="active_<?php echo $thisID;?>" value="1" <?php if($form_field['active']) echo "checked";?> /><br>
		<!--Required: <input type="checkbox" name="required_<?php echo $thisID;?>" id="required_<?php echo $thisID;?>" value="1" <?php if($form_field['required']) echo "checked";?> /><br/>	-->
		<textarea name="<?php echo $thisID;?>"><?php foreach($form_field['values'] as $form_field_value):?><?php echo $form_field_value."\n";?><?php endforeach;?></textarea>
	</div>
</div>
<?php endforeach; ?>
<div class="edit_form_field_wrap"><button type="submit" class="btn-primary btn text-center">UPDATE</button></div>
<input type="hidden" value="1" name="update_the_values" />
</form>

<?php include('inc/footer.php');?>
