<?php
$ci =& get_instance();
$id = $ci->uri->segment(5);

$c = new Content();
$c->get_by_id( $id );


if( $c->exists() )
{
	if( $c->can_delete() )
	{
		$c->deattach();
		$c->parent_content = 0;
		$c->save();
		$ci->app->add_info('Content has been removed to recycle bin');
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
