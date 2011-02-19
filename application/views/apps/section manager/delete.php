<?php 
$ci =& get_instance();
$ci->load->library( 'gui' );

$s = new Section();
$s->get_by_id( $ci->uri->segment( 5 ) );

$p = new Section();
$p->get_by_id( $s->parent_section );

$p->deattach_section( $s );
$s->delete_with_sub();
redirect( $ci->app->app_url( 'View Sections' ) );
