<?php

if( count($_POST)>0 )
{
	$db_hostname = $_POST['db_hostname'];
	$db_username = $_POST['db_username'];
	$db_password = $_POST['db_password'];
	$db_database = $_POST['db_database'];
	$db_dbdriver = $_POST['db_dbdriver'];
	$config_site_name = $_POST['config_site_name'];
}
else
{
	include('config.php');
}

if( count($_POST)>0 )
{
	$config_file = '<?php
$config_site_name		= "'.$config_site_name.'";

$db_hostname			= "'.$db_hostname.'";
$db_database			= "'.$db_database.'";
$db_username			= "'.$db_username.'";
$db_password			= "'.$db_password.'";
$db_dbdriver			= "'.$db_dbdriver.'";
';
file_put_contents( 'config.php', $config_file );
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>Vunsy websites kernel installer</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<link  href="assets/style/reset.css" rel="stylesheet" type="text/css" />
	<link  href="assets/style/text.css" rel="stylesheet" type="text/css" />
	<link  href="assets/style/style.css" rel="stylesheet" type="text/css" />

<style>
body{
	background-color: #E1E1E1;
	margin-left: 100px;
	margin-right: 100px;
	border-top: 5px solid #484848;
}
.header{
	background-color: #FF9345;
	padding-top: 20px;
	padding-bottom: 20px;
	padding-left: 50px;
	font-size: 3em;
	font-weight: bold;
	color: white;
}
.content{
	background-color: white;
	padding: 20px;
	font-size: 1.3em;
	color: #4D4D4D;
	border-top: 3px solid #883800;
}
.foot{
	background-color: #3E2000;
	font-size:0.9em;
	padding:7px;
	color: #FFF0DF;
	border-top: 3px solid #341500;
}
.label{
	padding-top: 15px;
	display: block;
	margin-bottom: 3px;
}
input{
	margin-left: 100px;
	width: 400px;
	font-size: 1.5em;
	border: 1px solid #4A4A4A;
	padding: 3px;
	background-color: #EBEBEB;
	color: #393939;
	font-family: "times new roman";
}
button{
	font-size: 1.5em;
	padding: 5px;
	font-family: "times new roman";
}
h1{
	display: block;
	clear: both;
}
.info, .error{ 
	clear: both;
	font-size: 0.8em;
}
hr
{
	margin-top:19px;
}
</style>
</head>

<body>
	<div class="header" >
		<img src="assets/admin/logo.png" style="float:left;" />
		Vunsy Installer
	</div>
	<div class="content" >
	
	<?php if( !is_writable( 'config.php' ) ): ?>
	
	<div class="error" >Config.php must be writable</div>
	
	<?php else: ?>
	
	<?php if( count($_POST)>0 ): ?>
	<div class="info" >
		configuration file written, you have to enter 
		<a href="<?=$config_base_url?>index.php" target="_blank" >your site index</a>
		to check if there is any error, 
		you can resubmit configuration tell you get the right values
	</div>
	<div class="error" >
		if no error then you have to delete that file from server (install.php)
	</div>
	<?php endif; ?>

	<form method="post" >
		<h1>Site config variables</h1>
			<div class="label">You site name :</div>
		<input type="text" value="<?=$config_site_name ?>" name="config_site_name" /><br />
		<hr />
		
		<h1>Database variables</h1>
		<div class="info" >you have to create your database at first, for mysql user you may use PHPmyadmin or alternative for other databases</div>
			<div class="label" >Database host name :</div>
		<input type="text" value="<?=$db_hostname ?>" name="db_hostname" /><br />
			<div class="label" >Database name :</div>
		<input type="text" value="<?=$db_database ?>" name="db_database" /><br />
			<div class="label" >Database user name :</div>
		<input type="text" value="<?=$db_username ?>" name="db_username" /><br />
			<div class="label" >Database password :</div>
		<input type="password" value="<?=$db_password ?>" name="db_password" /><br />
			<div class="label" >Database driver :</div>
		<input type="text" value="<?=$db_dbdriver ?>" name="db_dbdriver" /><br />
		<hr />
		
		<div style="text-align:center" ><button type="submit" >Write Configuration</button></div>
	</form>
	
	<?php endif; ?>
	
	</div>
	<div class="foot" >Vunsy is an opensource application built on codeigniter, dojo, jquery</div>
</body>
</html>
