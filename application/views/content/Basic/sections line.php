<?php if( $mode=='config' ): ?>	
parent:
	type:section
separator:
	type:textbox 
	default: "|"
style:
	type:textarea
<?php elseif( $mode=='layout' ): ?>
0
<?php elseif( $mode=='view' ): ?>
<?php 
$sections = new Section();
$sections->order_by( 'sort', 'asc' );
$sections->get_by_parent_section( $parent );

function remove_denied($sec )
{
	return $sec->can_view();
}

$secs = $sections->all;
$secs = array_filter( $secs, 'remove_denied' );
$hyperLinks = array();

foreach( $secs as $item )
{
	$local = site_url( $item->id );
	array_push( $hyperLinks, "<a href=\"{$local}\" style=\"{$style}\" >{$item->name}</a>\n" );
}

echo implode( $separator, $hyperLinks );
?>
<?php endif; ?>
