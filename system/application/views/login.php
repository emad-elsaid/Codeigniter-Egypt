<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>VUNSY LOGIN</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 0.15" />
	<link rel="stylesheet" href="<?= base_url() ?>jquery/theme/ui.all.css" type="text/css" />
	<script type="text/javascript" src="<?= base_url() ?>jquery/jquery.js" ></script>
	<script type="text/javascript" src="<?= base_url() ?>jquery/jquery-ui.js" ></script>
	<script type="text/javascript" >
	$(document).ready(function(){
    	$("#dialog").dialog(
			{  closeOnEscape: false ,
				draggable: true ,
				modal: true ,
				height: 350 ,
				width: 350 ,
				resizable: false,
				buttons: {
					Ok: function(){
						$('#login').submit();
					}
					}
			});
  	});
	</script>
</head>

<body style="font-size: 12px" >
		<div id="dialog" title="Login Dialog" >
			<p>
			<?php if( $this->vunsy->user->logged() ){ ?>
			<div class="ui-state-highlight" >
				<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
<strong>Logged in</strong></p>
			</div>
			<?php }else{ ?>
			
			<form method="POST" action="<?= site_url('login') ?>" id="login" >
				
			<table align="center" >
				<tr>
					<td colspan="2" align="center" >
					<img src="<?= base_url() ?>images/admin/logo.png" >
					</td>
				</tr>
				<tr>
					<td><label>User name</label></td>
					<td><input name="user" type="text" ></td>
				</tr>
				<tr>
					<td><label>Password</label></td>
					<td><input name="pass" type="password" ></td>
				</tr>
			</table>
			
			</form>
			<?php } ?>
			</p>
		</div>
</body>
</html> 

