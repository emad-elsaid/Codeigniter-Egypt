<?php if( $mode=='view' ){
add(<<<EOT
<style>
body{
	background-color: #C6C6C6;
	margin: 30px;
	font-family: tahoma;
}
.page{
	font-size: 13px;
	padding: 30px;

	width: 70%;
	
}
.title{
	font-size: 200%;
	color: #284128;
}
.title2{
	font-size: 80%;
	color: #284128;
}
.sep{
	width: 80%;
	border-top: 1px solid #284128;
	margin-top: 10px;
	margin-bottom: 10px;
}
</style>
EOT
);

add_css('jquery/theme/ui.all.css'); ?>
<table class="ui-corner-all ui-widget-content page" align="center" >
<tr>
	<td align="center" >
<?php 
$ci =& get_instance();
$ci->load->library( 'gui' );
echo $ci->gui->info( 'if you want to start just login using admin and remove that layout and enjoy' )
?>
<br>
		<img src="<?= base_url() ?>assets/admin/logo.png" >
		<br><h1 class="ui-helper-reset title" >Codeigniter-Egypt</h1>
		<h2 class="ui-helper-reset title2" >Virtual Univeral System</h2>
		<div class="sep"></div>
	</td>
</tr>
<tr>
	<td>
		<b>[program built on]</b><br>
<ul>
	<li>CodeIgniter PHP framework</li>
	<li>Datamapper</li>
	<li>Ion-Auth</li>
	<li>Dojo+Dijit</li>
	<li>Jquery</li>
</ul>
<br>
<b>[The idea]</b><br>
<ul>
<li>the idea is to make a site with fast way debending on the widgets and layouts
giving permissions to evey content for several things
viewing, deleting and so.</li>

<li>so as the section has the same thing
built on it some applications accessible by a task bar like facebook one</li>
</ul>

<b>[ Running ]</b><br>
<ol>
	<li>
	normal site: <br>
		<i>example.com/index.php</i><br>
	</li>
	<li>
	login page:<br>
		<i>example.com/index.php/login</i><br>
	</li>
	<li>
	logout page:<br>
		<i>example.com/index.php/logout</i><br>
	</li>
</ol>

<b>[ admin default logon ]</b><br>
<ol>
	<li>
		user name: <b>admin@admin.com</b><br>
		password: <b>password</b>
	</li>
	<li>
		can change it from:<br>
		<i>example.com/index.php/auth/change_password</i>
	</li>
</ol>

<b>[ How to help us ]</b><br>
<ul>
	<li>contact me and tell you want to help on github</li>
	<li>watch that repo to tell me that you are intersted [ click the watch button github.com ]</li>
	<li>fork that repo [ click the fork button on github.com ]</li>
</ul>

	</td>
</tr>
</table>
<?php } ?>
