<?php if( $mode=='config' ){
//the plugin requirements as a JSON object is here ?>
{
	"id":{"type":"number"}
}


<?php }else if( $mode=='layout' ){ 
//replace 0 with number of cells your plugin has ?>
0


<?php }else if( $mode=='view' ){
//the real content of your plugin goes here ?>
<?php 
$instance = new Content();
$instance->get_by_id( $info->id );
echo $instance->render();
?>

<?php } ?>
