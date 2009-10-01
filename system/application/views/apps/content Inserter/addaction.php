<?php
$ci =& get_instance();
$ci->load->library('gui');
	

$c = new content();

if( $ci->input->post( "id" )!==FALSE )
{
	$c->get_by_id( $ci->input->post( "id" ) );
	$old_edit = $c->can_edit();
}

if( $ci->input->post( "id" )===FALSE )
{
	$c->user = $ci->vunsy->user->id;
}
$c->parent_section = $ci->input->post( "parent_section" );
$c->parent_content = $ci->input->post( "parent_content" );
$c->title = $ci->input->post("title");
$c->cell = $ci->input->post( "cell" );
$c->sort = $ci->input->post( "sort" );
$c->path = $ci->input->post( "path" );
$c->type = $ci->input->post( "type" );
$c->subsection = ($ci->input->post ( "subsection" )==FALSE)? FALSE:TRUE;
$c->view = $ci->input->post( "view" );
$c->addin = $ci->input->post( "addin" );
$c->edit = $ci->input->post( "edit" );
$c->del = $ci->input->post( "del" );
$c->info = $ci->input->post( "info" );
$c->filter = $ci->input->post( "filter" );

$sec = new Section();
$sec->get_by_id( $c->parent_section );

$p = new Content();
$p->get_by_id( $c->parent_content );

if(		( $p->can_addin() AND  $ci->input->post( "id" )===FALSE )
	OR  ( $ci->input->post( "id" )!==FALSE AND $old_edit ) )
{
	
	if( $ci->input->post( "id" )===FALSE )
	{
		$c->attach( $sec, $p, $c->cell, $c->sort );
		$ci->app->add_info('Content added');
	}
	else
	{
		$c->save();
		if( $ci->app->ajax )
			echo "Content Edited";
		else
			redirect( $ci->app->app_url("data/{$c->id}") );
		
	}
}
else
{
	if( $ci->app->ajax )
		echo 'Permission denied';
	else
		$ci->app->add_error( 'Permission denied' );
}
