<html>
<body>
	<h1><?=lang('system_reset_pass_for')?> <?php echo $identity;?></h1>
	<p><?=lang('system_please_click_link')?> <?php echo anchor('auth/reset_password/'. $forgotten_password_code, lang('system_reset_password'));?>.</p>
</body>
</html>