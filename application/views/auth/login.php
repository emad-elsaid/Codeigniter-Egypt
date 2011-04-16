<?=theme_doctype()?>
<?php
theme_pagetitle(lang('system_login'));
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
<script type="text/javascript">
dojo.addOnLoad( null, function(){
	dijit.byId( 'loginD' ).show();
});
</script>
</head>
	<body class="<?=theme_dojotheme()?>">

		<div id="loginD" dojoType="dijit.Dialog" title="<?=lang('system_login')?>" >
		
		<div class='mainInfo'>

    <div class="pageTitleBorder"></div>
    <center><img alt=""  src="<?= base_url() ?>assets/admin/logo.png" align="center" ></center>
	<p><?=lang('system_please_login')?></p>
	
	<?php echo $message;?>
	
    <?php echo form_open("auth/login");?>
      <p>
      	<label for="email"><?=lang('system_username')?></label>
      	<?php echo form_input($username);?>
      </p>
      <p>
      	<label for="password"><?=lang('system_password')?></label>
      	<?php echo form_input($password);?>
      </p>
      <p>
	      <label for="remember"><?=lang('system_rememberme')?></label>
	      <?php echo form_checkbox('remember', '1', FALSE);?>
	  </p>
            
      <p><?php echo form_submit('submit', lang('system_login'));?></p>
    <?php echo form_close();?>
    <a href="<?=site_url('auth/forgot_password')?>" ><?=lang('system_forgot')?></a><br/>
    <a href="<?=site_url('auth/create_user')?>" ><?=lang('system_new_user_register')?></a>

</div>
		</div>
		<?=theme_foot()?>
		
</body>
</html> 