<?php if( $mode=='config' ){
//the plugin requirements as a JSON object is here ?>
{
	"stylesheet" : { "type":"file" },
	"class" : { "type":"textbox" },
	"width" : { "type":"dropdown","options":[ "container 16", "container 12" ] }
}


<?php }else if( $mode=='layout' ){ 
//replace 0 with number of cells your plugin has ?>
1


<?php }else if( $mode=='view' ){
//the real content of your plugin goes here ?>
<?php
add_css( "assets/960.gs/960.css" );
if( $info->stylesheet != "" ) 
	add_css( $info->stylesheet );
	
$cont_class = ( $info->width==0 )? "container_16" : "container_12";
$text = "\n<div class=\"{$cont_class} {$info->class}\">{$cell[0]}\n</div>";
echo $text;
?>

<?php } ?>
