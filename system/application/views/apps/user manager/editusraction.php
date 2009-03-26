<?php
$ci =& get_instance();

$usr = new User();
$usr->get_by_id( $ci->input->post('id') );
$usr->name = $ci->input->post('name');
$usr->level = $ci->input->post('level');
$usr->set_password( $ci->input->post('password') );
$usr->email = $ci->input->post('email');
$usr->save();

redirect( $ci->app->app_url("viewusr") );
