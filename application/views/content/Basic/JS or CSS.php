<?php if( $mode=='config' ){
//the plugin requirements as a JSON object is here ?>
{
	"resourceFiles":{"type":"file list"}
}


<?php }else if( $mode=='layout' ){ 
//replace 0 with number of cells your plugin has ?>
0


<?php }else if( $mode=='view' ){
//the real content of your plugin goes here ?>
<?php
add( explode( "\n", $info->resourceFiles) );

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
<?php } ?>
