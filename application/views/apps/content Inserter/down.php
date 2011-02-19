<?php
$ci =& get_instance();
$id = $ci->uri->segment(5);

$c = new Content();
$c->get_by_id( $id );


if( $c->exists() )
{
	if( $c->can_edit() )
	{
		if( $c->move_down() )
		{
			$ci->app->add_info('Content moved down');
		}
		else
		{
			$ci->app->add_info('Content i already the last');
		}
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
 
 
