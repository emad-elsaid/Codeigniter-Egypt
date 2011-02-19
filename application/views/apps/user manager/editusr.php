<?php
$ci =& get_instance();
$ci->load->library('gui');


//getting the level;
$id = $ci->uri->segment(5);
$user = new User();
$user->get_by_id($id);


$levels = new Userlevel();
$levels->get();
$lvls = array();
foreach( $levels->all as $item )
{
	$lvls[ $item->level ] = $item->name;
}

echo $ci->gui->form( $ci->app->app_url('editusraction')
	,array(
		'level' => $ci->gui->dropdown( "level", $user->level, $lvls),
		'name' => $ci->gui->textbox( "name", $user->name ),
		'password' => $ci->gui->password( "password" ),
		'email' => $ci->gui->textbox( "email", $user->email ),
		'' => $ci->gui->button( "submit", "Edit user", array( "type"=>"submit" ) )
	)
	,''
	,array('id'=>$user->id )
);

