<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>VUNSY LOGIN</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 0.15" />
	<link rel="stylesheet" href="<?= base_url() ?>jquery/theme/ui.all.css" type="text/css" />
	<script type="text/javascript" src="<?= base_url() ?>jquery/jquery.js" ></script>
	<script type="text/javascript" src="<?= base_url() ?>jquery/jquery-ui.js" ></script>
	<script type="text/javascript" >
	$(document).ready(function(){
    	$("#dialog").dialog({ closeOnEscape: false , draggable: false , modal: true , resizable: false});
  	});

	</script>
</head>

<body class=".ui-helper-reset">
		<div id="dialog" title="Dialog Title" >
			<p>
			<form method="POST" action="<?= site_url('page/login_action') ?>" >
			<label>User name</label> <input id='user' type="text" ><br />
			<label>Password</label> <input id='pass' type="text" >
			<input type="submit" value="Enter" >
			</form>
			</p>
		</div>
</body>
</html> 

