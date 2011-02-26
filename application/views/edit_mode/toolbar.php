<?php
$ci =& get_instance();
add( 'assets/admin/edit panel/style.css' );
add('dojo/dojo/dojo.js');
add( 'jquery/jquery.js' );
add( 'jquery/jquery-ui.js' );
add( 'jquery/theme/ui.all.css' );
add( 'assets/fancybox/jquery.fancybox.js' );
add( 'assets/fancybox/jquery.fancybox.css' );
$local = base_url().'/assets/admin/';
$logout = site_url( 'auth/logout' );

$links = array(
"toggle vunsy edit buttons"=>"vunsyToggle('.vunsyCtrl').toggle();"
);
$links_text = '';
foreach( $links as $key=>$value )
	$links_text .= "<a href=\"#\" onclick=\"{$value}\" >
	<img 
		src=\"{$local}jquery/{$key}.png\" 
		title=\"{$key}\"
		>{$key}</a>";


$XHR_URL = site_url('editmode/'.(($ci->vunsy->mode()=='edit')?'view':'edit'));

add( <<<EOT

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


// initialise Iframe links
$(
	function()
	{
		$("a.iframe").fancybox({
			frameWidth: 600,
			frameHeight: 450,
		}); 
	}
);


// make trigger draggable to show content under it
$(function(){
	$('.trigger').draggable({axis:'y'});
});

// switching editmode
function admin_editmode_toolbar()
{
	dojo.xhrGet({
		url: "{$XHR_URL}",
		load: function(args,response)
		{
			document.location.reload();
		}
	});
}
</script>
EOT
);
?>


<div class="panel">

<a href="<?=site_url('sectionEditor')?>" class="iframe"  title="Sections manager">
	<img src="<?=$local?>section.png" /> Sections manager
</a>

<a href="<?=site_url('auth')?>" class="iframe" title="Users manager" >
	<img src="<?=$local?>users.png" /> User manager
</a>

<br />
<?=$links_text?>

<a href="javascript:admin_editmode_toolbar()" >
	<img src="<?=$local?>editmode.png" title="Editmode toggle" /> Toggle Edit mode
</a>
<a href="<?=$logout?>" >
	<img src="<?=$local?>logout.png" title="Logout" /> Logout
</a>
<a href="<?=site_url('auth/change_password')?>" >
	<img src="<?=$local?>logout.png" title="Change Password" />Change Password
</a>
</div>
<a class="ui-draggable trigger" href="#"></a>

