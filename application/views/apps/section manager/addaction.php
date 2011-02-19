<?php 
$ci =& get_instance();
$ci->load->library( 'gui' );

$s = new Section();
$s->parent_section = $ci->input->post( 'parent_section' );
$s->name = $ci->input->post( 'name' );
$s->sort = $ci->input->post( 'sort' );
$s->view = $ci->input->post( 'view' );

$p = new Section();
$p->get_by_id( $s->parent_section );

$p->attach_section( $s );
redirect( $ci->app->app_url( 'View Sections' ) );
