<?php if( $mode=='config' ): ?>
resourceFiles:
	type:file list
<?php elseif( $mode=='layout' ): ?>
0


<?php elseif( $mode=='view' ): ?>
<?php
theme_add( explode( "\n", $resourceFiles) );

if( $ci->system->mode=='edit' )
{	
	if( $ci->ion_auth->is_admin() )
	{
		$ci->load->library( 'gui' );
		echo $ci->gui->info( 'Resource '.nl2br($resourceFiles) );
	}
}
?>
<?php endif; ?>
