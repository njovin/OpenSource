<?php session_start(); $title = "Login";$safe_title = "login"; $access_level = 0;

if(isset($_POST['login_email'])){
	require_once('php/class/user.php');
	$user = new User();
	$login = $user->login($_POST['login_email'],$_POST['login_password']);
	if($login):
		header("Location: index.php");
	else:
		$message = "Invalid User/Password";
	endif;
	
	
}
elseif(isset($_SESSION['userID'])){
	header('Location: index.php');
}
?>
<?php include('inc/header.php');?>

<div class="text-center">
	<div>
	<h1><?php echo $general->welcome_logo_html;?></h1>
	<?php if(isset($message)):?>
	<p class="error"><?php echo $message;?></p>
	<?php endif;?>
	</div>
	<div id="login_box">
	<div id="login_form">
	<form class="form-horizontal" action="" method="post" role="form">
<fieldset>

<!-- Form Name -->
<legend>Please Login.</legend>

<!-- Text input-->
<div class="control-group">
  <label class="control-label" for="login_email">Email</label>
  <div class="controls">
    <input id="login_email" name="login_email" placeholder="email address" class="input-xlarge" required type="text">
    <p class="help-block">(ex: email@example.com )</p>
  </div>
</div>

<!-- Password input-->
<div class="control-group">
  <label class="control-label" for="login_password">Password</label>
  <div class="controls">
    <input id="login_password" name="login_password" placeholder="your password" class="input-xlarge" required type="password">
    
  </div>
</div>

<!-- Button -->
<div class="control-group">
  <label class="control-label" for="login_submit_btn"></label>
  <div class="controls">
    <button id="login_submit_btn" name="login_submit_btn" class="btn btn-primary">Login</button>
  </div>
</div>

</fieldset>
</form>
<p style="line-height:50px;"><a href="forgot.php"><small>Forgot password?</small></a></p>
</div>
	</div>
	
<?php include('inc/footer.php');?>
