<?php session_start(); $title = "List Users"; $safe_title="list_users"; $access_level = 2;?>
<?php include('inc/header.php');?>
<?php $list_users = $admin->list_users();?>
<?php 
if(isset($_GET['del']) && $_GET['del'] == true): 
	echo $general->js("alertify.success('User Deleted.');");
endif;
?>
<table class="table table-striped">
	<thead>
    	<th>ID</th>
		<th>EMAIL</th>
		<th>LEVEL</th>
	<!--<th>ACTION</th>-->
	</thead>	
	<tbody>
		<?php	foreach($list_users as $a_user): ?>
		<tr>
        <td><?php echo $a_user['id'];?></td>
        <td><a href="edit_user.php?id=<?php echo $a_user['id'];?>"><?php echo $a_user['email'];?></a></td>
        <td><?php echo $a_user['levelname'];?></td>
        
       <!--<td><a href="edit_user.php?id=<?php echo $a_user['id'];?>"><i class=" glyphicon glyphicon-pencil"></i></a> <a href="#" class="toggle_active_user" data-userID="<?php echo $a_user['id'];?>" data-adminID="<?php echo $_SESSION['userID'];?>"><i class="glyphicon glyphicon-eye-<?php echo ($a_user['active'] == "1") ? "open" : "close";?>"></i></a> <a href="#" class="delete_user" data-userID="<?php echo $a_user['id'];?>" data-adminID="<?php echo $_SESSION['userID'];?>"><i class="glyphicon glyphicon-remove-sign"></i></a></td>-->
        
        </tr>
		<?php	endforeach; ?>
		<tr><td colspan="3" class="text-right"><a href="add_user.php"><i style="font-size:20px;" class="glyphicon glyphicon-plus">ADD NEW</i></a></td></tr>	
	</tbody>
</table>

<?php include('inc/footer.php');?>
