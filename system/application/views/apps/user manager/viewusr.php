<?php

$ci =& get_instance();
$l = new user();
$l->get();
$ci->load->library('gui');


$levels = new userlevel();
$levels->get();
$lvls = array();
foreach( $levels->all as $item )
{
	$lvls[ $item->level ] = $item->name;
}

echo $ci->gui->tooltipbutton("Add user", "Add user", $ci->gui->form( $ci->app->app_url('addusraction')
,array(
		'level' => $ci->gui->dropdown( "level","",$lvls),
		'name' => $ci->gui->textbox( "name" ),
		'password' => $ci->gui->password( "password" ),
		'email' => $ci->gui->textbox( "email" ),
		'' => $ci->gui->button( "submit", "Add user", array( "type"=>"submit" ) )
)));

if( $l->count() <= 0 )
	$ci->app->add_info( " No users available" );
else
{
	$c = new userlevel();
	foreach( $l->all as $item )
	{
		$c->get_by_level( $item->level );
		$item->level = $c->name;
		$item->d = anchor($ci->app->app_url('delusr/'.$item->id), "Delete");
		$item->e = anchor($ci->app->app_url('editusr/'.$item->id), "Edit");
	}

	echo $ci->gui->grid(
			array('id'=>'ID','level'=>'Level','name'=>'Name','email'=>'Email','d'=>'Delete','e'=>'Edit')
			,$l->all);
	
}
