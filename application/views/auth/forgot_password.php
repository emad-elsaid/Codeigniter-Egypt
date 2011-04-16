<?=theme_doctype()?>
<?php
theme_pagetitle(lang('system_forgot'));
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

		<div id="loginD" dojoType="dijit.Dialog" title="<?=lang('system_forgot')?>" >
		
<p><?=lang('system_please_enter_email')?></p>


<?php echo $message;?>

<?php echo form_open("auth/forgot_password");?>

      <p><?=lang('system_email')?><br />
      <?php echo form_input($email);?>
      </p>
      
      <p><?php echo form_submit('submit', lang('system_submit'));?></p>
      
<?php echo form_close();?>
		</div>
		<?=theme_foot()?>
</body>
</html> 