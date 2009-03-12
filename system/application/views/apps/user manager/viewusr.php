<?php

$ci =& get_instance();
$l = new user();
$l->get();

if( $l->count() <= 0 )
	$ci->app->add_info( " No users available" );
	
$c = new userlevel();
foreach( $l->all as $item )
{
	$c->get_by_level( $item->level );
	echo $item->id,"&nbsp; Level:",$c->name,"&nbsp;Name:",$item->name,"&nbsp;Email:",$item->email,"&nbsp;",anchor($ci->app->app_url('delusr/'.$item->id), "Delete"),"<br>";
}
