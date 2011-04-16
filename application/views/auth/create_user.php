<?=theme_doctype()?>
<?php
theme_pagetitle(lang('system_register'));
theme_add('dijit.form.Button');
theme_add('dijit.Dialog');
theme_add('dijit.form.TextBox');
theme_add('dijit.form.Button');
theme_add('assets/style/auth_style.css');
theme_add('assets/style/style.css');
?>
<html>
<head>
	<?=theme_head()?>
<script type="text/javascript" >
dojo.addOnLoad( null, function(){
	dijit.byId( 'loginD' ).show();
});
</script>
</head>
	<body class="claro">

		<div id="loginD" dojoType="dijit.Dialog" title="<?=lang('system_register')?>" >
		
		<div class='mainInfo'>
		
	<p><?=lang('system_please_enter_info')?></p>
	<?php if(strlen($message)>0 ): ?>
	<div class="info"><?php echo $message;?></div>
	<?php endif; ?>
	
    <?php echo form_open("auth/create_user");?>
      <p><?=lang('system_first_name')?><br />
      <?php echo form_input($first_name);?>
      </p>
      
      <p><?=lang('system_last_name')?><br />
      <?php echo form_input($last_name);?>
      </p>
      
      <p><?=lang('system_email')?><br />
      <?php echo form_input($email);?>
      </p>

      <p><?=lang('system_username')?><br />
      <?php echo form_input($username);?>
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