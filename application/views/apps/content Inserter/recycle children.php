<?php
$ci =& get_instance();
$id = $ci->uri->segment(5);

$c = new Content();
$c->get_by_id( $id );


if( $c->exists() )
{
	if( $c->can_delete() )
	{
		$children = new Content();
		$children->get_by_parent_content($c->id);
		foreach ($children->all as $child ) 
		{
			$child->deattach();
			$child->parent_content = 0;
			$child->save();
		}
		$ci->app->add_info('Children has been removed to recycle bin');
	}
	else
	{
		$ci->app->add_error( 'permission denied! please check your root adminstrator' );
	}
}
else
{
	$ci->app->add_error( 'Content not found' );
}
