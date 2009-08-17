<?php if( $mode=='config' ){
//the plugin requirements as a JSON object is here ?>
{
	"CSS_File":{"type":"file"}
}


<?php }else if( $mode=='layout' ){ 
//replace 0 with number of cells your plugin has ?>
0


<?php }else if( $mode=='view' ){
//the real content of your plugin goes here ?>
<?php 
add_css( $info->CSS_File );

$ci =& get_instance();
if( $ci->vunsy->edit_mode() )
{ 
?>
<div title="<?= $info->CSS_File ?>" >CSS</div>
<?php } ?>
<?php } ?>
