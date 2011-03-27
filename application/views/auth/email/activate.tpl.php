<html>
<body>
	<h1><?=lang('system_activate_for')?> <?php echo $identity;?></h1>
	<p><?=lang('system_please_click_link')?> <?php echo anchor('auth/activate/'. $id .'/'. $activation, lang('system_activate_account'));?>.</p>
</body>
</html>