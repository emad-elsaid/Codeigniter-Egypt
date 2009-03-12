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
	$ci->app->add_info( "User added please return to the view page or add another user" );
}
