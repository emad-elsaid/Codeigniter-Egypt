<?php
$ci =& get_instance();
add_js( 'jquery/jquery.js' );
add_js( 'jquery/jqDock.js' );
add_js( 'jquery/aqFloater.js' );
?>
<script type="text/javascript" >
$("document").ready( function(){
	$("#adminToolBar").aqFloater({attach: 'n', duration: .3, opacity: 1});
	$("#adminToolBarIcons").jqDock({align:'top',duration:100});
}
);
</script>
<div id="adminToolBar" style="align:center">
<div id='adminToolBarIcons'style="align:center">
	<a href="<?= base_url() ?>kfm" target="_blank" >
    	<img src="<?= base_url() ?>images/admin/kfm.png" title="Software manager" />
	</a>
	<a href="<?= site_url('admin/app/user manager') ?>" target="_blank" >
    	<img src="<?= base_url() ?>images/admin/users.png" title="Users manager" />
	</a>
	<a href="<?= site_url('admin/app/section manager') ?>" target="_blank" >
    	<img src="<?= base_url() ?>images/admin/section.png" title="Sections manager" />
	</a>
	<a href="<?= site_url('admin/app/software manager') ?>" target="_blank" >
    	<img src="<?= base_url() ?>images/admin/software.png" title="Software manager" />
	</a>
	
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
	<a href="javascript: admin_editmode_toolbar()" target="_blank" >
    	<img src="<?= base_url() ?>images/admin/editmode.png" title="Editmode changer" />
	</a>
	<a href="<?= site_url('logout') ?>" >
    	<img src="<?= base_url() ?>images/admin/logout.png" title="Logout" />
	</a>
</div>
</div>
