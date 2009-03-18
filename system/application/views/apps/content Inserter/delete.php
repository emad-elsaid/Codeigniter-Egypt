<?php
$ci =& get_instance();
$id = $ci->uri->segment(5);

$c = new Content();
$c->get_by_id( $id );


if( $c->exists() )
{
	if( $c->type = 'layout' )
	{
		$c = new Layout();
		$c->get_by_id( $id );
	}

	$c->delete();
	$ci->app->add_info('Content deleted');
}
else
{
	$ci->app->add_error( 'Content not found' );
}
