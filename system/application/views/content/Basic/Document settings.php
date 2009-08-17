<?php if( $mode=='config' ){ ?>
{
	"background":{"type":"file"},
	"extra_class":{"type":"textbox"},
	"extra_style":{"type":"textarea"}
}
<?php } ?>

<?php if( $mode=='view' ){ ?>

<?php 
$ci =& get_instance();
if( $ci->vunsy->edit_mode() )
{
	$ci->load->library( 'gui' );
	echo $ci->gui->info( 'document sittings here' );
}

?>

<style>
body{
	<?= $info->extra_style ?>
	<?= (!empty($info->background))? "background-image:url(".base_url().$info->background.");":''; ?>
}
</style>
<?php
if(!empty($info->extra_class)){ 
	add_js('jquery/jquery.js'); ?>
<script language="javascript" >
	$(document).ready(
		function()
		{
			$(document).addClass('<?= $info->extra_class ?>');
		}
	);
</script>
<?php
	 }
}
 ?>
