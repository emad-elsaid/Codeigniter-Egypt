<?php
$ci =& get_instance();
$c  = new content();

$c->get_by_id( $ci->input->post( "id" ) );

$c->parent_section = $ci->input->post( "parent_section" );
$c->parent_content = $ci->input->post( "parent_content" );
$c->cell = $ci->input->post( "cell" );
$c->sort = $ci->input->post( "sort" );

$sec = new Section();
$sec->get_by_id( $c->parent_section );

$p = new Content();
$p->get_by_id( $c->parent_content );

if(	$p->can_addin() )
{
	$c->attach( $sec, $p, $c->cell, $c->sort );
	$ci->app->add_info('Content restored');
	$c->save();
	//redirect( $ci->app->app_url("data/{$c->id}") );
}
else
{
	$ci->app->add_error( 'Permission denied' );
}
