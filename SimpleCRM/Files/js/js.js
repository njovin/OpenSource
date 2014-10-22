$(document).ready(function(){

	
		// User Form Settings
		$('.show_form_field').click(function(e){
				$('.edit_form_field_item').hide();
				var showthisid = $(this).data('showthis');
				var form_field = $('#'+showthisid);
				
				form_field.toggle();
				
		});
		
		// Validator
		$('#add_user_form').validator();
		
		// ADMIN search menu
			
			$('#user_search').autocomplete({
				serviceUrl:'php/local_api/list_users.php?adminID='+$('#user_search').data('userid'),
				onSelect: function(value,data){
					
					document.location = 'edit_user.php?id='+value.id;
				}
			});
		// CONTACT search Menu
		$('#contact_search').autocomplete({
			serviceUrl:'php/local_api/list_contacts.php?userID='+$('#contact_search').data('userid'),
				onSelect: function(value,data){
					
					document.location = 'edit_contact.php?id='+value.id;
				}
		});		
		
		
		// LOCAL API CALLS
		$('.toggle_active_user').click(function(e){
				var tar = $(e.currentTarget);
				//console.log(tar);
				var userID = tar.data('userid');
				var adminID = tar.data('adminid');
				var payloadProfile = {
					"userID" : userID,
					"adminID" : adminID
				};
				var error;
				
				$.ajax({
						type: "POST",
						url: "php/local_api/toggle_active_user.php",
						data: payloadProfile,
						contentType: "application/x-www-form-urlencoded; charset=UTF-8'",
						dataType: "json",
						async: false,
						success: function(data){
						
							if(data.msg){
								error= false;
								alertify.success(data.msg);
							}
							if(data.error){
								error = true;
								alertify.error(data.error);
							}
						}
						
				});
				
				if(!error){ // If there was NO error, change the icon
					$(this).children().toggleClass("glyphicon glyphicon-eye-open glyphicon glyphicon-eye-close");	
				}
		});
		$('.delete_user').click(function(e){
				var tar = $(e.currentTarget);
				//console.log(tar);
				var userID = tar.data('userid');
				var adminID = tar.data('adminid');
				var payloadProfile = {
					"userID" : userID,
					"adminID" : adminID
				};
				var error;
				if(adminID === userID){
					alertify.error("You cannot delete yourself!");
					return;
				}
				if(confirm("Are you SURE you want to delete this user?")){
				
				
				$.ajax({
						type: "POST",
						url: "php/local_api/delete_user.php",
						data: payloadProfile,
						contentType: "application/x-www-form-urlencoded; charset=UTF-8'",
						dataType: "json",
						async: false,
						success: function(data){
							if(data.msg){
								alertify.success(data.msg);
								error = false;
							}
							if(data.error){
								error= true;
								alertify.error(data.error);
							}
						},
						
						
				}); 
				if(!error){ // IF there was no ERROR, hide the row.
					$(this).parent().parent().hide();
				}
		} // end IF sure..
		});
		
		$('.delete_contact').click(function(e){
				var tar = $(e.currentTarget);
				//console.log(tar);
				var contactID = tar.data('contactid');
				var payloadProfile = {
					"contactID" : contactID
				};
				var error;
				if(confirm("Are you SURE you want to delete this user?")){
				
				
				$.ajax({
						type: "POST",
						url: "php/local_api/delete_contact.php",
						data: payloadProfile,
						contentType: "application/x-www-form-urlencoded; charset=UTF-8'",
						dataType: "json",
						async: false,
						success: function(data){
							if(data.msg){
								alertify.success(data.msg);
								error = false;
							}
							if(data.error){
								error= true;
								alertify.error(data.error);
							}
						},
						
						
				}); 
				if(!error){ // IF there was no ERROR, hide the row.
					$(this).parent().parent().hide();
				}
		} // end IF sure..
		});
		
		
	$('.export_contact').click(function(e){
				var tar = $(e.currentTarget);
				var contactID = tar.data('contact_id');
				export_contact(contactID);
		});		

	$('input, textarea').keydown(function(e){
		//.console.log('keydown');
		
		if(e.keyCode === 188){  // prevent a ',' character..
			e.preventDefault();	
		}

		
	});
		
	
});

function export_contact(contact_id){
	
				var payloadProfile = {
					"contact_id" : contact_id
				};
				console.log('Has been clicked!');
				$.ajax({
						type: "GET",
						url: "php/local_api/export_contact.php",
						data: payloadProfile,
						contentType: "application/x-www-form-urlencoded; charset=UTF-8'",
						dataType: "json",
						async: true,
						success: function(data){
							if(data.status){
								alertify.success(data.msg);
							}
							else{
								alertify.error(data.msg);							
							}
						}
						
						
				}); 
		
	}
