<?php
$ci =& get_instance();

$s = new Section();
$s->get_by_id( $ci->input->post( 'id' ) );
$s->name = $ci->input->post( 'name' );
$s->view = $ci->input->post( 'view' );
$s->save();

redirect( $ci->app->app_url('view') );
