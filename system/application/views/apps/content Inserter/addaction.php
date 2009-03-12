<?php
$ci =& get_instance();
$ci->load->library('gui');

$c = new content();

$c->parent_section = $ci->input->post( "parent_section" );
$c->parent_content = $ci->input->post( "parent_content" );
$c->cell = $ci->input->post( "cell" );
$c->sort = $ci->input->post( "sort" );
$c->path = $ci->input->post( "path" );
$c->type = $ci->input->post( "type" );
$c->subsection = ($ci->input->post ("subsection")==NULL)? FALSE:TRUE;
$c->view = $ci->input->post( "view");
$c->addin = $ci->input->post( "addin");
$c->edit = $ci->input->post( "edit");
$c->del = $ci->input->post( "del");

$p = new content();
$p->get_by_id( $c->parent_content );
$c->attach( $ci->vunsy->section , $p, $c->cell, $c->sort );

$c->save();
$ci->app->add_info( "content added " );
