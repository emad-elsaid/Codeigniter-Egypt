<?php

$ci =& get_instance();
$l = new user();
$l->get();
$ci->load->library('gui');

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
