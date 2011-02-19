<?php if( $mode=='config' ){
//the plugin requirements as a JSON object is here ?>
{
	"background attributes":"if you specified a background image all the related attributes would be wrriten as well in the rendered HTML, otherwise they will be ignored",
	"background_image" : { "type" : "file" },
	"repeat" : { "type" : "dropdown",
			"options" :{"no-repeat":"no-repeat",
				"repeat":"repeat",
				"repeat-x":"repeat-x",
				"repeat-y":"repeat-y"},
			 "default" : "0" },
	"attachment" : { "type" : "dropdown", "options" : {"scroll":"scroll","fixed":"fixed"}, "default": "0" },
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
{
	$style .= "background-image: url({$local}{$info->background_image});";
	$style .= "background-position: {$info->horizontal_position} {$info->vertical_position};";
	$style .= "background-repeat: {$info->repeat};";
	$style .= "background-attachment: {$info->attachment};";
}
$style .= $info->style;
$style  = "style=\"{$style}\"";

$class = ( $info->class != '' )? "class=\"{$info->class}\"" : '';
?>
<div  <?= $class ?> <?= $style ?> ><?= $cell[0] ?></div>
<?php } ?>
