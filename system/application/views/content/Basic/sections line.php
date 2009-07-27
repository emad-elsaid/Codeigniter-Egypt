<?php if( $mode=='config' ){
//the plugin requirements as a JSON object is here ?>
{
	"parent":{"type":"number"},
	"separator":{"type":"textbox", "default":" | "},
	"style":{"type":"textarea"}
}


<?php }else if( $mode=='layout' ){ 
//replace 0 with number of cells your plugin has ?>
0


<?php }else if( $mode=='view' ){
//the real content of your plugin goes here ?>
<?php 
$sections = new Section();
$sections->get_by_parent_section( $info->parent );
$hyperLinks = array();

foreach( $sections->all as $item )
{
	$local = site_url( $item->id );
	array_push( $hyperLinks, "<a href=\"{$local}\" style=\"{$info->style}\" >{$item->name}</a>" );
}

echo implode( $info->separator, $hyperLinks );
?>
<?php } ?>
