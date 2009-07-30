<?php
$ci =& get_instance();
add_js('dojo/dojo/dojo.js');
add_js( 'jquery/jquery.js' );
add_js( 'jquery/jqDock.js' );
add_js( 'jquery/aqFloater.js' );
$ci->load->library('gui');
$local = base_url();
$url = site_url('admin/app').'/';
$logout = site_url( 'logout' );
?>
<script type="text/javascript" >
$("document").ready( function(){
	$("#adminToolBar").aqFloater({attach: 'n', duration: 0.3, opacity: 0.9});
});
</script>
<style>
.linksTable img, .linksTable a, .linksTable a:visited{
	border: 0px none;
	text-decoration: none;
	color: #000000;
}
.linksTable img{
	padding-bottom: 4px;
	vertical-align: middle;
	margin: 2px;
	margin-left: 5px;
}
.linksTable a, .linksTable a:visited{
	display: block;
	width: 100%;	
	border: 1px solid #BFBFBF;
}
.linksTable a:hover{
	border: 1px solid #4D4D4D;
}
.linksTable ul{
	list-style-type: none;
}
.linksTable li{
	margin: 2px;
	padding: 2px;
}
</style>
<div id="adminToolBar" style="align:center;width:100%;" >
<?php 
$text = <<<EOT
<table class="linksTable" width="100%" >
	<tr>
		<td width="33%" >
			<ul>
				<li><a href="{$local}kfm" target="_blank" >
					<img src="{$local}images/admin/kfm.png" title="My Computer" /> My computer
				</a></li>
				<li><a href="{$url}user manager" target="_blank" >
					<img src="{$local}images/admin/users.png" title="Users manager" /> User manager
				</a></li>
				<li><a href="{$url}section manager" target="_blank" >
					<img src="{$local}images/admin/section.png" title="Sections manager" /> Sections manager
				</a></li>
				<li><a href="{$url}software manager" target="_blank" >
					<img src="{$local}images/admin/software.png" title="Software manager" /> Software manager
				</a></li>
				<li><a href="javascript:admin_editmode_toolbar()" >
					<img src="{$local}images/admin/editmode.png" title="Editmode toggle" /> Editmode toggle
				</a></li>
				<li><a href="{$logout}" >
					<img src="{$local}images/admin/logout.png" title="Logout" /> Logout
				</a></li>
				</ul>
		</td>
		<td width="33%" >
		</td>
		<td width="33%" >
		</td>
	</tr>
</table>
EOT;
echo $ci->gui->titlepane( 'Admin tools', $text, 'open="false" ' );
?>
</div>
<script language="javascript" >
		function admin_editmode_toolbar()
		{
			dojo.xhrGet({
				url: "<?= site_url('admin/app/editmode/'.(($ci->vunsy->edit_mode())?'viewmode':'editmode')) ?>",
				load: function(args,response)
				{
					document.location.reload();
				}
			});
		}
	</script>
</div>
