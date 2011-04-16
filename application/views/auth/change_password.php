<?=theme_doctype()?>
<?php
theme_pagetitle(lang('system_change_password'));
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

		<div id="loginD" dojoType="dijit.Dialog" title="<?=lang('system_change_password')?>" >
		
<?php if(strlen($message)>0 ): ?>
<div class="info"><?php echo $message;?></div>
<?php endif; ?>

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