<?php if( $mode=='config' ){
//the plugin requirements as a JSON object is here ?>
{
	"resourceFile":{"type":"file"}
}


<?php }else if( $mode=='layout' ){ 
//replace 0 with number of cells your plugin has ?>
0


<?php }else if( $mode=='view' ){
//the real content of your plugin goes here ?>
<?php 
$ci->load->library( 'gui' );
$found = add( $info->resourceFile );

if( $ci->vunsy->edit_mode() )
	echo $ci->gui->info( 'Resource '.((!$found)? 'not ':'').'found: '.$info->resourceFile );
?>
<?php } ?>
