<?php
$ci =& get_instance();
$l = new userlevel();
$l->get_by_id( $ci->uri->segment(5) );

if( ! $l->exists() )
	$ci->app->add_error( "level not found" );
else
{
	$usr = new User();
	$usr->get_by_level( $l->id );
	$usr->delete_all();
	
	$l->delete();
	$ci->app->add_info( " level deleted " );
}
