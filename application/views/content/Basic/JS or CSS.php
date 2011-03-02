<?php if( $mode=='config' ): ?>
resourceFiles:
	type:file list
<?php elseif( $mode=='layout' ): ?>
0


<?php elseif( $mode=='view' ): ?>
<?php
theme_add( explode( "\n", $info->resourceFiles) );

if( $ci->system->mode()=='edit' )
{
	$c = new Content();
	$c->get_by_id( $id );
	
	if( $ci->ion_auth->is_admin() )
	{
		$ci->load->library( 'gui' );
		echo $ci->gui->info( 'Resource '.nl2br($info->resourceFiles) );
	}
}
?>
<?php endif; ?>
