<?php
add_js( 'jquery/jquery.js' );
add_js( 'jquery/jqDock.js' );
add_js( 'jquery/aqFloater.js' );
?>
<script type="text/javascript" >
$("document").ready( function(){
	$("#adminToolBar").aqFloater({attach: 'n', duration: .3, opacity: 1});
	$("#adminToolBarIcons").jqDock({align:'top',duration:200});
}
);
</script>
<div id="adminToolBar">
<div id='adminToolBarIcons'>
	<a href="<?= site_url('admin/app/user manager') ?>" title="Users Manager" target="_blank" >
    	<img src="<?= base_url() ?>images/admin/users.png" alt="" title="" />
	</a>
</div>
</div>
