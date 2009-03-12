<?php
$ci =& get_instance();
$l = new userlevel();
$l->get();

foreach( $l->all as $item )
{
	echo $item->id,"&nbsp;",$item->level,"&nbsp;",$item->name,"&nbsp;",anchor($ci->app->app_url('dellvl/'.$item->id), "Delete"),"<br>";
}
