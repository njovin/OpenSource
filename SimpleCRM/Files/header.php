<?php require_once(dirname(dirname(__FILE__)).'/php/general.php'); $general = new General();?>
<!DOCTYPE html> 
<html lang="en">
<head>
<title><?php echo $title;?> : <?php echo $general->site_title;?></title>
    <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<link href="css/styles.css" rel="stylesheet" type="text/css" />
<!--<link href="js/jquery-ui-1.11.1.custom/jquery-ui.css" type="text/css" rel="stylesheet" />
<link href="js/jquery-ui-1.11.1.custom/jquery-ui.theme.min.css" type="text/css" rel="stylesheet" />-->

<meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
   <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
   <!--[if lt IE 9]>
     <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
     <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
   <![endif]-->
<link href="bootstrap-3.2.0-dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="bootstrap-3.2.0-dist/css/bootstrap-theme.min.css" rel="stylesheet" />
 
</head>
<body>
<div id="wrap" class="body">
<div id="<?php echo $title;?>_wrapper">
