<?php
$ci =& get_instance();
add( 'assets/edit panel/style.css' );
add('dojo/dojo/dojo.js');
add( 'jquery/jquery.js' );
$local = base_url();
$url = site_url('admin/app').'/';
$logout = site_url( 'logout' );

$links = array(
"toggle images"=>"vunsyToggle('img:not(.trigger img,.panel img)');",
"toggle links"=>"vunsyToggle('a:not(.trigger,.panel a)');",
"toggle tables"=>"vunsyToggle('table:not(.trigger table, .panel table)');",
"toggle vunsy edit buttons"=>"vunsyToggle('.vunsyCtrl').toggle();"
);
$links_text = '';
foreach( $links as $key=>$value )
{
	$links_text .= "<a href=\"#\" onclick=\"{$value}\" >
	<img 
		src=\"{$local}images/admin/jquery/{$key}.png\" 
		title=\"{$key}\"
		>{$key}</a>";
}
?>

<script type="text/javascript">

function vunsyButtonToggler(){
		$(".panel").toggle("fast");
		$(this).toggleClass("active");
		return false;
}
	
function vunsyToggle(selector)
{
	$(selector).toggle();
	vunsyButtonToggler();
}

$(function (){
	$(".trigger").click(vunsyButtonToggler);
});
</script>



<div class="panel">
<a href="<?="{$local}"?>kfm" target="_blank" >
	<img src="<?="{$local}"?>images/admin/kfm.png" title="My Computer" /> My computer
</a>
<a href="<?="{$url}"?>user manager" target="_blank" >
	<img src="<?="{$local}"?>images/admin/users.png" title="Users manager" /> User manager
</a>
<a href="<?="{$url}"?>section manager" target="_blank" >
	<img src="<?="{$local}"?>images/admin/section.png" title="Sections manager" /> Sections manager
</a>
<a href="<?="{$url}"?>Package manager" target="_blank" >
	<img src="<?="{$local}"?>images/admin/software.png" title="Package manager" /> Package manager
</a>
<a href="<?="{$url}"?>software manager" target="_blank" >
	<img src="<?="{$local}"?>images/admin/software.png" title="Software manager" /> Software manager
</a>

<br />
<?="{$links_text}"?>

<br />
<a href="javascript:admin_editmode_toolbar()" >
	<img src="<?="{$local}"?>images/admin/editmode.png" title="Editmode toggle" /> Editmode toggle
</a>
<a href="<?="{$logout}"?>" >
	<img src="<?="{$local}"?>images/admin/logout.png" title="Logout" /> Logout
</a>
</div>
<a class="trigger" href="#">Edit</a>

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
