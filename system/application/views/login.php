<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>VUNSY LOGIN</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 0.15" />
	<link rel="stylesheet" href="<?= base_url() ?>jquery/theme/ui.all.css" type="text/css" />
	<link  href="<?= base_url() ?>dojo/dijit/themes/tundra/tundra.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="<?= base_url() ?>dojo/dojo/dojo.js"
	djConfig="parseOnLoad:true"></script>
	<script type="text/javascript">
		dojo.require("dojo.parser");
		dojo.require("dijit.form.Button");
		dojo.require("dijit.Dialog");
		dojo.require("dijit.form.TextBox");
		dojo.require("dijit.form.Button");
	</script>
<style>
body{
	font-size: 12px;
}

label{
	text-align: left;
	display: block;
	vertical-align: text-top;
}
</style>
<script language="javascript" >
dojo.addOnLoad( null, function(){
	dijit.byId( 'loginD' ).show();
});
</script>
</head>
	<body class="tundra">

		<div id="loginD" dojoType="dijit.Dialog" title="Login Dialog" >
			<?php if( $this->vunsy->user->logged() ){ ?>
			<div class="ui-state-highlight" style="width:300px;" >
				<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
				<strong>Logged in</strong></p>
			</div>
			<?php }else{ ?>
			
<form method="POST" action="<?= site_url('login') ?>" id="login" >
	<center><img src="<?= base_url() ?>images/admin/logo.png" align="center" ></center>
	<label>User name : </label>
	<input dojoType="dijit.form.TextBox" name="user" type="text" 
	style="	color: #555555;
	background:#FBFBFB none repeat scroll 0% 0%;
	border:1px solid #E5E5E5;
	font-size:24px;
	margin-bottom: 9px;
	margin-right:6px;
	margin-top:2px;
	padding:3px;
	width:97%;
	border-color:#DFDFDF;
	font-family: \"Lucida Grande\",Verdana,Arial,\"Bitstream Vera Sans\",sans-serif;
	
	font-size-adjust:none;
	font-style:normal;
	font-variant:normal;
	font-weight:normal;
	line-height:normal;" >
	<label>Password : </label>
	<input dojoType="dijit.form.TextBox" name="pass" type="password" 
	style="	color: #555555;
	background:#FBFBFB none repeat scroll 0% 0%;
	border:1px solid #E5E5E5;
	font-size:24px;
	margin-bottom: 9px;
	margin-right:6px;
	margin-top:2px;
	padding:3px;
	width:97%;
	border-color:#DFDFDF;
	font-family: \"Lucida Grande\",Verdana,Arial,\"Bitstream Vera Sans\",sans-serif;
	
	font-size-adjust:none;
	font-style:normal;
	font-variant:normal;
	font-weight:normal;
	line-height:normal;" >
	<div dir="rtl" ><button type="submit" dojoType="dijit.form.Button" >Login</button></div>
</form>
			<?php } ?>
		</div>
</body>
</html> 

