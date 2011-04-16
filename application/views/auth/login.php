<?=theme_doctype()?>
<?php
theme_pagetitle(lang('system_login'));
theme_add('dijit.form.Button');
theme_add('dijit.Dialog');
theme_add('dijit.form.TextBox');
theme_add('dijit.form.Button');
?>
<html>
<head>
	<?=theme_head()?>
<style type="text/css" >
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
	font-family: "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif;
	
	font-style:normal;
	font-variant:normal;
	font-weight:normal;
	line-height:normal
}
</style>
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
	
	<div id="infoMessage"><?php echo $message;?></div>
	
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