<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title> Deactivate</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 0.15" />
	<link rel="stylesheet" href="<?= base_url() ?>jquery/theme/ui.all.css" type="text/css" />
	<link  href="<?= base_url() ?>dojo/dijit/themes/claro/claro.css" rel="stylesheet" type="text/css" />
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
input[type="text"],input[type="password"]
{
	color: #555555;
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
	line-height:normal
}
</style>
<script language="javascript" >
dojo.addOnLoad( null, function(){
	dijit.byId( 'loginD' ).show();
});
</script>
</head>
	<body class="claro">

		<div id="loginD" dojoType="dijit.Dialog" title="Activate/Deactivate User" >
		
		<div class='mainInfo'>

    <div class="pageTitleBorder"></div>
	<p>Are you sure you want to deactivate the user '<?php echo $user->username; ?>'</p>
	
    <?php echo form_open("auth/deactivate/".$user->id);?>
    	
      <p>
      	<label for="confirm">Yes:</label>
		<input type="radio" name="confirm" value="yes" checked="checked" />
      	<label for="confirm">No:</label>
		<input type="radio" name="confirm" value="no" />
      </p>
      
      <?php echo form_hidden($csrf); ?>
      <?php echo form_hidden(array('id'=>$user->id)); ?>
      
      <p><?php echo form_submit('submit', 'Submit');?></p>

    <?php echo form_close();?>

</div>
		</div>
</body>
</html> 