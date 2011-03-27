<?=theme_doctype()?>
<?php
theme_pagetitle('Change Password');
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

		<div id="loginD" dojoType="dijit.Dialog" title="<?=lang('system_change_password')?>" >
		

<div id="infoMessage"><?php echo $message;?></div>

<?php echo form_open("auth/change_password");?>

      <p><?=lang('system_old_password')?><br />
      <?php echo form_input($old_password);?>
      </p>
      
      <p><?=lang('system_new_password')?><br />
      <?php echo form_input($new_password);?>
      </p>
      
      <p><?=lang('system_new_password_conf')?><br />
      <?php echo form_input($new_password_confirm);?>
      </p>
      
      <?php echo form_input($user_id);?>
      <p><?php echo form_submit('submit', lang('system_change_password'));?></p>
      
<?php echo form_close();?>
		</div>
		<?=theme_foot()?>
</body>
</html> 