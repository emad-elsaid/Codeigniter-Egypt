<?php
$ci =& get_instance();
$l = new user();
$l->get_by_id( $ci->uri->segment(5) );

if( ! $l->exists() )
	$ci->app->add_error( "User not found" );
else
{
	$l->delete();
	$ci->app->add_info( " user deleted " );
}
