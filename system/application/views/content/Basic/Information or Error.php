<?php if( $mode=='config' ){
//the plugin requirements as a JSON object is here ?>
{
	"type":{"type":"dropdown","options":{"info":"info","error":"error"}},
	"text":{"type":"smalleditor"}
}


<?php }else if( $mode=='layout' ){ 
//replace 0 with number of cells your plugin has ?>
0


<?php }else if( $mode=='view' ){
//the real content of your plugin goes here ?>

<?php
$ci->load->library( "gui" );
$f = $info->type;
echo $ci->gui->$f( $info->text );
?>

<?php } ?>
