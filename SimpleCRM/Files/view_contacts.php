<?php session_start(); $title = "View Contacts"; $safe_title="view_contacts"; $access_level = 1;?>
<?php include('inc/header.php');?>
<?php if(isset($_GET['start']) && is_numeric($_GET['start'])): $start = $_GET['start']; else: $start = 0; endif;?>
<?php $contact_obj = new Contact($user); $contacts = $contact_obj->get_all($start);?>

<table class="table table-striped">
	<thead>
    	<th>ID</th>
		<th>NAME</th>
		<th>COMPANY</th>
		<th>EMAIL</th>
<!--        <th>ACTION</th>-->
	</thead>	
	<tbody>
		<?php	foreach($contacts as $contact): ?>
		<tr>
        <td><a href="#" class="delete_contact" data-contactID="<?php echo $contact['id'];?>" data-userID="<?php echo $_SESSION['userID'];?>"><i class="glyphicon glyphicon-remove-sign"></i></a> <?php echo $contact['id'];?></td>
        <td><a href="edit_contact.php?id=<?php echo $contact['id'];?>"><?php echo $contact['fullname'];?></a></td>
        <td><?php echo $contact['company'];?></td>
        <td><?php echo $contact['email'];?></td>
        <!--<td><a href="edit_contact.php?id=<?php echo $contact['id'];?>"><i class=" glyphicon glyphicon-pencil"></i></a> <a href="#" class="delete_contact" data-contactID="<?php echo $contact['id'];?>" data-userID="<?php echo $_SESSION['userID'];?>"><i class="glyphicon glyphicon-remove-sign"></i></a> <a href="#" class="export_contact" data-contact_id="<?php echo $contact['id'];?>" title="export"><i title="export" class="glyphicon glyphicon-list"></i></a></td>
        -->
        </tr>
		<?php	endforeach; ?>
		<tr><td colspan="4" class="text-right"><a href="add_contact.php"><i style="font-size:20px;" class="glyphicon glyphicon-plus">ADD NEW</i></a></td></tr>	
	</tbody>
    <tfoot>
    	<td><?php echo $contact_obj->prev_pagination($start);?></td><td colspan="2"> </td><td><?php echo $contact_obj->next_pagination($start);?></td>
    </tfoot>
</table>

<?php include('inc/footer.php');?>
