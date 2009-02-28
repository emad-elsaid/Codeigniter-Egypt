<?php
add_js( 'jquery/jquery.js' );
add_js( 'jquery/aqFloater.js' );
?>
<script type="text/javascript" >
$("document").ready( function(){
	$("#adminToolBar").aqFloater({attach: 'ne', duration: .3, opacity: 1});
	$("document").addClass( "nihilo" );
}
);
</script>
<div id="adminToolBar">
<img src="<?= base_url() ?>adminTheme/edit.gif" >
</div>

