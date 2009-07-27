<?php if( $mode=='config' ){
//the plugin requirements as a JSON object is here ?>
{
	"background_image" : { "type" : "file" },
	"repeat" : { "type" : "dropdown", "options" : ["no-repeat","repeat","repeat-x","repeat-y"], "default" : "0" },
	"attachment" : { "type" : "dropdown", "options" : ["scroll","fixed"], "default": "0" },
	"horizontal_position" : { "type" : "textbox", "default" : "left" },
	"vertical_position" : { "type" : "textbox", "default" : "top" },
	"class" : { "type" : "textbox" },
	"style" : { "type" : "textarea" }
}


<?php }else if( $mode=='layout' ){ 
//replace 0 with number of cells your plugin has ?>
1


<?php }else if( $mode=='view' ){
//the real content of your plugin goes here ?>
<?php 
$local = base_url();
$style = '';
if( $info->background_image != '' )
	$style .= "background-image: url({$local}{$info->background_image});";
$style .= "background-position: {$info->horizontal_position} {$info->vertical_position};";

switch( $info->repeat )
{
	case '0':
		$t = 'no-repeat';
		break;
	case '1':
		$t = 'repeat';
		break;
	case '2':
		$t = 'repeat-x';
		break;
	case '3':
		$t = 'repeat-y';
		break;
}
$style .= "background-repeat: {$t};";

switch( $info->attachment )
{
	case '0':
		$t = 'scroll';
		break;
	case '1':
		$t = 'fixed';
		break;
}
$style .= "background-attachment: {$t};";
$style .= $info->style;
$style  = "style=\"{$style}\"";

$class = ( $info->class != '' )? "class=\"{$info->class}\"" : '';
?>
<div  <?= $class ?> <?= $style ?> ><?= $cell[0] ?></div>
<?php } ?>
