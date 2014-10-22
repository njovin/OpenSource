<?php session_start(); $title = "View Exports"; $safe_title="view_exports"; $access_level = 1;?>
<?php include('inc/header.php');?>
<?php 

	$contact = new Contact($user);
	$export_files = $contact->list_contact_exports();
	
?>

<table class="table table-striped">
	<thead>
		<th>DATE</th>
        <th>FILE</th>
	</thead>	
	<tbody>
    <?php foreach($export_files as $export_file):?>
    	
        <?php 

			$filedatetime = $export_file['datetime'];
			$filename = $export_file['filename'];
		
		?>
        
        <tr>
        	<td><?php echo $filedatetime;?></td>
	        <td><a href="e/<?php echo $user->get_private_dir();?>/<?php echo $filename;?>"><?php echo $filename;?></td>
        </tr>
    <?php endforeach;?>
    </tbody>
	
</table>

<?php include('inc/footer.php');?>
