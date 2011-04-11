<?php
$ci =& get_instance();
theme_add( 'assets/admin/edit panel/style.css' );
theme_add( 'jquery/jquery.js' );
theme_add( 'assets/fancybox/jquery.fancybox.js' );
theme_add( 'assets/fancybox/jquery.fancybox.css' );
$local = base_url().'/assets/admin/';
$logout = site_url( 'auth/logout' );
$XHR_URL = site_url('editmode/'.($ci->system->mode=='edit'? 'view':'edit'));

theme_add( <<<EOT

<script type="text/javascript">

function systemButtonToggler(){
		$(".panel").toggle("fast");
		$(this).toggleClass("active");
		return false;
}

$(function (){
	$(".trigger").click(systemButtonToggler);
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

	<a href="<?=site_url('section_editor')?>" class="iframe"  title="<?=lang('system_sections_editor')?>">
		<img src="<?=$local?>link.png" /> <?=lang('system_sections_editor')?>
	</a>
	
	<a href="<?=site_url('users_editor')?>" class="iframe" title="<?=lang('system_users_editor')?>" >
		<img src="<?=$local?>link.png" /> <?=lang('system_users_editor')?>
	</a>
	
	<a href="javascript:admin_editmode_toolbar()" >
		<img src="<?=$local?>link.png" title="<?=lang('system_toggle_editmode')?>" /> <?=lang('system_toggle_editmode')?>
	</a>
	
	<a href="<?=site_url('auth/change_password')?>" class="iframe" >
		<img src="<?=$local?>link.png" title="<?=lang('system_change_password')?>" /> <?=lang('system_change_password')?>
	</a>
	
	<a href="<?=$logout?>" >
		<img src="<?=$local?>link.png" title="<?=lang('system_logout')?>" /> <?=lang('system_logout')?>
	</a>

</div>
<a class="ui-draggable trigger" href="#"></a>

