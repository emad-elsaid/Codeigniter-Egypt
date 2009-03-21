<?php

$ci =& get_instance();

$u = new user();
$u->get_by_name($ci->input->post('name'));

if( $u->exists() )
	$ci->app->add_error( "user name duplication please choose another name " );

else
{
	$u->name = $ci->input->post('name');
	$u->set_password = $ci->input->post('password');
	$u->email = $ci->input->post('email');
	$u->level = $ci->input->post( 'level' );
	$u->save();
	redirect( $ci->app->app_url('View users'));
}
