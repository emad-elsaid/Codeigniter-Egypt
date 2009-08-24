<?php
if(! function_exists( "break_layout" ) )
{
	function break_layout( $layout )
	{
		$rows = explode( "\n", $layout );
		for( $i=0; $i<count( $rows ); $i++ )
		{
			$rows[$i] = explode( ",", $rows[$i] );
			$rows[$i] = array_map( 'trim', $rows[$i] );
		}
		return $rows;
	}
}
?>
<?php if( $mode=='config' ){
//the plugin requirements as a JSON object is here ?>
{
	"stylesheet" : { "type":"file" },
	"class" : { "type":"textbox" },
	"style" : { "type":"textarea" },
	"width" : { "type":"dropdown","options":{ "container_16":"16 Column", "container_12":"12 Column" } },
	"layout formation": " form columns with widths separated by comma to sum the containers width ex: 12,4 <br>another ex: 4,4,4,4<br>8,4,4 <br> that will form two rows first with 4 columns the second with 3 columns",
	"layout" : { "type":"textarea" },
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
if( ! empty($info->stylesheet) ) add_css( $info->stylesheet );
	
$arr = break_layout( $info->layout );
$text = "";

$count = 0;
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
	$text = "\n<div class=\"{$info->width} {$info->class}\" style=\"{$info->style}\">{$text}\n</div>\n";
	
echo $text;
?>
<?php } ?>
