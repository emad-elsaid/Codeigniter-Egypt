<?php
$ci =& get_instance();
$ci->load->library('gui');

$levels = new userlevel();
$levels->get();
$lvls = array();
foreach( $levels->all as $item )
{
	$lvls[ $item->level ] = $item->name;
}

echo $ci->gui->form( $ci->app->app_url('addusraction')
,array(
		'level' => $ci->gui->dropdown( "level","",$lvls),
		'name' => $ci->gui->textbox( "name" ),
		'password' => $ci->gui->password( "password" ),
		'email' => $ci->gui->textbox( "email" ),
		'' => $ci->gui->button( "submit", "Add user", array( "type"=>"submit" ) )
));
