<?=theme_doctype()?>
<?php
theme_pagetitle('Register');
theme_add('dijit.form.Button');
theme_add('dijit.Dialog');
theme_add('dijit.form.TextBox');
theme_add('dijit.form.Button');
?>
<html>
<head>
	<?=theme_head()?>
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

		<div id="loginD" dojoType="dijit.Dialog" title="<?=lang('system_register')?>" >
		
		<div class='mainInfo'>
		
	<p><?=lang('system_please_enter_info')?></p>
	
	<div id="infoMessage"><?php echo $message;?></div>
	
    <?php echo form_open("auth/create_user");?>
      <p><?=lang('system_first_name')?><br />
      <?php echo form_input($first_name);?>
      </p>
      
      <p><?=lang('system_first_name')?><br />
      <?php echo form_input($last_name);?>
      </p>
      
      <p><?=lang('system_company')?><br />
      <?php echo form_input($company);?>
      </p>
      
      <p><?=lang('system_email')?><br />
      <?php echo form_input($email);?>
      </p>
      
      <p><?=lang('system_phone')?><br />
      <?php echo form_input($phone);?>
      </p>
      
      <p><?=lang('system_password')?><br />
      <?php echo form_input($password);?>
      </p>
      
      <p><?=lang('system_password_conf')?><br />
      <?php echo form_input($password_confirm);?>
      </p>
      
      
      <p><?php echo form_submit('submit', lang('system_register'));?></p>

      
    <?php echo form_close();?>

</div>
		</div>
		<?=theme_foot()?>
</body>
</html> 