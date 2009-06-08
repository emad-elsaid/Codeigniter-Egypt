<?php if( $mode=='config' ){
//the plugin requirements as a JSON object is here ?>
{
	"text":{"type":"editor"}
}


<?php }else if( $mode=='layout' ){ 
//replace 0 with number of cells your plugin has ?>
0


<?php }else if( $mode=='view' ){
//the real content of your plugin goes here ?>

<?php
$ci =& get_instance();
$ci->load->library( "gui" );
echo $ci->gui->error( $info->text );
?>

<?php } ?>
