<?php require_once(dirname(dirname(__FILE__)).'/php/general.php'); $general = new General();?>
<!DOCTYPE html> 
<html lang="en">
<head>
<title><?php echo $title;?> : <?php echo $general->site_title;?></title>
    <meta charset="utf-8">
    <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<META NAME="GOOGLEBOT" CONTENT="NOINDEX, NOFOLLOW">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<link href="css/styles.css" rel="stylesheet" type="text/css" />
<link href="js/jquery-ui-1.11.1.custom/jquery-ui.css" type="text/css" rel="stylesheet" />
<!--<link href="js/jquery-ui-1.11.1.custom/jquery-ui.theme.min.css" type="text/css" rel="stylesheet" />-->

   <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
   <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
   <!--[if lt IE 9]>
     <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
     <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
   <![endif]-->
<link href="bootstrap-3.2.0-dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="bootstrap-3.2.0-dist/css/bootstrap-theme.min.css" rel="stylesheet" />
<link href="js/alertify.js-0.3.11/themes/alertify.core.css" rel="stylesheet" />
<link href="js/alertify.js-0.3.11/themes/alertify.bootstrap.css" rel="stylesheet" />
<script type="text/javascript" src="js/jquery-2.1.1.min.js" language="javascript"></script>
<script type="text/javascript" src="js/jquery-ui-1.11.1.custom/jquery-ui.min.js" language="javascript"></script>
<script type="text/javascript" src="bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
<script src="js/validator.js"></script>
<script type="text/javascript" src="js/alertify.js-0.3.11/src/alertify.js"></script>
<script type="text/javascript" src="js/jquery.autocomplete.js" language="javascript"></script>
<script type="text/javascript" src="js/js.js"></script>
 </head>
<body>
<div id="wrap<?php echo "safe_title";?>" class="body">
<?php if(isset($safe_title) && $safe_title != "login" && $safe_title != 'reset' && $safe_title != 'forgot'):?>
<nav id="usermenu" class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    
		  <a class="navbar-brand" href="index.php"><?php echo $general->logo_nav_html;?></a>
   
    </div>
   <?php include_once('inc/menu.php');?>
  </div><!-- /.container-fluid -->
</nav>
<?php endif;?>
<div id="<?php echo $title;?>_wrapper">
<?php if(isset($is_home) && $is_home == true):?>
	<?php include_once('inc/main_content.php');?>
<?php endif;?>
