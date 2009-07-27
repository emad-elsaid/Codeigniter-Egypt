<?php
if(! function_exists( "break_layout" ) )
{
	function break_layout( $layout )
	{
		$rows = explode( "\n", $layout );
		for( $i=0; $i<count( $rows ); $i++ )
			$rows[$i] = explode( ",", $rows[$i] );
		return $rows;
	}
}
?>
<?php if( $mode=='config' ){
//the plugin requirements as a JSON object is here ?>
{
	"stylesheet" : { "type":"file" },
	"class" : { "type":"textbox" },
	"width" : { "type":"dropdown","options":[ "container 16", "container 12" ] },
	"layout" : { "type":"textarea", "default": "10,6" },
	"include_container" : { "type":"checkbox", "default":"true" }
}


<?php }else if( $mode=='layout' ){ 
//replace 0 with number of cells your plugin has ?>
<?php 
$arr = break_layout( $info->layout );
$count = 0;
foreach( $arr as $item ) $count += count( $item );
echo $count;
?>



<?php }else if( $mode=='view' ){
//the real content of your plugin goes here ?>
<?php
add_css( "assets/960.gs/960.css" );
if( $info->stylesheet != "" ) 
	add_css( $info->stylesheet );
	
$arr = break_layout( $info->layout );
$count = 0;
$text = "";
$cont_class = ( $info->width==0 )? "container_16" : "container_12";

foreach( $arr as $item )
{
	foreach( $item as $i )
	{
		if( $cell[$count] == '' ) $cell[$count] = '&nbsp;';
		$text .= "\n\t<div class=\"grid_{$i}\">{$cell[$count]}</div>";
		$count++;
	}
	$text .= "\n\t<div class=\"clear\">&nbsp;</div>";
}

if( $info->include_container==TRUE )
	$text = "\n<div class=\"{$cont_class} {$info->class}\">".$text."\n</div>";
	
echo $text;
?>
<?php } ?>
