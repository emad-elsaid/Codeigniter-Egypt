<?php
$ci =& get_instance();
$l = new userlevel();
$l->get_by_level( $ci->input->post( 'level' ) );

if( $l->exists() )
	$ci->app->add_error( "Level Exists please choose another level number" );
else
{
	$l->name = $ci->input->post( 'name' );
	$l->level = $ci->input->post( 'level' );
	$l->save();
	redirect( $ci->app->app_url('View levels'));
}
