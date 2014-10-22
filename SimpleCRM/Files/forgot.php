<?php session_start(); $title = "Forgot";$safe_title = "forgot"; $access_level = 0;

if(isset($_SESSION['userID'])){
	header('Location: index.php');
}
elseif(isset($_POST['forgot_email'])){
	require_once('php/class/user.php');
	$user = new User();
	$login = $user->forgot($_POST['forgot_email']);
}
?>
<?php include('inc/header.php');?>
<div class="text-center">
<h1><?php echo $general->welcome_logo_html;?></h1>
<h2>Reset Password.</h2>
<?php if(!isset($_POST['forgot_email'])):?><p>Please type your email and we will send you a reset link to your email address.</p>

<form class="form-horizontal" action="" method="post">
<fieldset>

<!-- Form Name -->

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="forgot_email">Email</label>  
  <div class="col-md-4">
  <input id="forgot_email" name="forgot_email" placeholder="Email address" class="form-control input-md" required type="email">
  <span class="help-block">please type your email</span>  
  </div>
</div>
<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="singlebutton"></label>
  <div class="col-md-4">
    <button id="singlebutton" name="singlebutton" type="submit" class="btn btn-primary">Send</button>
  </div>
</div>
</fieldset>
</form>
<?php else:?>
<div>Your reset link has been sent to the email provided. If you do not recieve the email, please check your spam filter or add '<?php $general->server_email;?>' to your safe sender list.</div>
<?php endif;?>
</div>

<?php include('inc/footer.php');?>