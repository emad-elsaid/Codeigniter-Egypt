<?php
$ci =& get_instance();
add( 'assets/admin/edit panel/style.css' );
add( 'jquery/jquery.js' );
add( 'jquery/jquery-ui.js' );
add( 'jquery/theme/ui.all.css' );
add( 'assets/fancybox/jquery.fancybox.js' );
add( 'assets/fancybox/jquery.fancybox.css' );
$local = base_url().'/assets/admin/';
$logout = site_url( 'auth/logout' );
$XHR_URL = site_url('editmode/'.($ci->system->mode()=='edit'? 'view':'edit'));

add( <<<EOT

<script type="text/javascript">

function systemButtonToggler(){
		$(".panel").toggle("fast");
		$(this).toggleClass("active");
		return false;
}

$(function (){
	$(".trigger").click(systemButtonToggler);
	$('.trigger').draggable({axis:'y'});
	$("a.iframe").fancybox({
			frameWidth: 600,
			frameHeight: 450,
	});
});

// switching editmode
function admin_editmode_toolbar()
{
	$.get( "{$XHR_URL}", function(response){
			document.location.reload();
	});
}
</script>
EOT
);
?>


<div class="panel">

	<a href="<?=site_url('sectionEditor')?>" class="iframe"  title="Sections manager">
		<img src="<?=$local?>link.png" /> Sections manager
	</a>
	
	<a href="<?=site_url('auth')?>" class="iframe" title="Users manager" >
		<img src="<?=$local?>link.png" /> User manager
	</a>
	
	<a href="javascript:admin_editmode_toolbar()" >
		<img src="<?=$local?>link.png" title="Editmode toggle" /> Toggle Edit mode
	</a>
	
	<a href="<?=site_url('auth/change_password')?>" class="iframe" >
		<img src="<?=$local?>link.png" title="Change Password" />Change Password
	</a>
	
	<a href="<?=$logout?>" >
		<img src="<?=$local?>link.png" title="Logout" /> Logout
	</a>

</div>
<a class="ui-draggable trigger" href="#"></a>

