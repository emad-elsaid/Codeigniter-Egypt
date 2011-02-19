<?php
$ci =& get_instance();

$level = new Userlevel();
$level->get_by_id( $ci->input->post('id') );
$level->name = $ci->input->post('name');
$level->level = $ci->input->post('level');
$level->save();

$ci->app->add_info('level updated');

