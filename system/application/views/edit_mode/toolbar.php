<?php
$ci =& get_instance();
add_js('dojo/dojo/dojo.js');
add_js( 'jquery/jquery.js' );
add_js( 'jquery/aqFloater.js' );
$ci->load->library('gui');
$local = base_url();
$url = site_url('admin/app').'/';
$logout = site_url( 'logout' );

$links = array(
"toggle images"=>"$('img:not(#adminToolBar img)').toggle();",
"toggle links"=>"$('a:not(#adminToolBar a)').toggle();",
"toggle tables"=>"$('table:not(#adminToolBar table)').toggle();",
"toggle vunsy edit buttons"=>"$('.vunsyCtrl').toggle();",
"toggle edit toolbar"=>"$('#adminToolBar,#showtoolbar').toggle();"
);
$links_text = '';
foreach( $links as $key=>$value )
{
	$links_text .= "<li><a href=\"#\" onclick=\"{$value}\" >
	<img 
		src=\"{$local}images/admin/jquery/{$key}.png\" 
		title=\"{$key}\"
		>{$key}</a></li>";
}
?>
<script type="text/javascript" >
function closeAdminTitlepane()
{
	alert("sdf");
	$('#adminToolBar,#showtoolbar').toggle();
	return false;
}
$("document").ready( function(){
	$("#adminToolBar").aqFloater({attach: 'nw', duration: 0.1, opacity: 0.8});
	$("#showtoolbar").aqFloater({attach: 'ne', duration: 0.1, opacity: 0.8});
	$('#adminToolBar').hide();
});
</script>
<style>
.linksTable{
	width:100%;
	border-collapse:collapse;
	border: 0px none;
	font-size: 12px;
}
.linksTable td{
	width: 25%;
	vertical-align: text-top;
}
.linksTable img, .linksTable a, .linksTable a:visited{
	border: 0px none;
	text-decoration: none;
	color: #000000;
	white-space: nowrap;
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
	padding:0px;
}
</style>
<img id="showtoolbar" onclick="$('#adminToolBar,#showtoolbar').toggle();" 
src="<?= "{$local}images/admin/jquery/show toolbar.png" ?>"
title="Show edit toolbar" style="z-index:999;" >

<div id="adminToolBar" style="align:center;width:100%;z-index:999;" >
<?php 
$text = <<<EOT
<table class="linksTable" >
	<tr>
		<th>Applications</th>
		<th>JQuery Buttons</th>
		<th></th>
		<th></th>
	</tr>
	<tr>
		<td>
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
		<td>
			<ul>
				{$links_text}
			</ul>
		</td>
		<td>
		</td>
		<td>
		</td>
	</tr>
</table>
EOT;
echo $ci->gui->titlepane( 'Admin tools', $text);
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
