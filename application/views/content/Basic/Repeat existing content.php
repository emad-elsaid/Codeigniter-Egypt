<?php if( $mode=='config' ): ?>
id:
	type:number
<?php elseif( $mode=='layout' ): ?>
0


<?php elseif( $mode=='view' ): ?>
<?php 
$instance = new Content();
$instance->get_by_id( $info->id );
if( $instance->exists() )
	echo $instance->render();
else
{
	$ci->load->library('gui');
	echo $ci->gui->error('Content Choosen not found');
}
?>
<?php endif; ?>
