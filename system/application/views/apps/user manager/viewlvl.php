<?php
$ci =& get_instance();
$l = new userlevel();
$l->get();
$ci->load->library('gui');


 echo $ci->gui->tooltipbutton( "Add level", "Add leve", $ci->gui->form( $ci->app->app_url('addlvlaction'), 
 array(
	"Level name" => $ci->gui->textbox( 'name' )
	,"Level number" => $ci->gui->number( 'level', 1 )
	,"" => $ci->gui->button( 'submit',  'Add level' , array( 'type'=>'submit' ) )
 )
 ));


$a = $l->all;
foreach( $a as $item )
{
	$item->d = anchor($ci->app->app_url('dellvl/'.$item->id), "Delete");
	$item->e = anchor($ci->app->app_url('editlvl/'.$item->id), "Edit");
}

echo $ci->gui->grid(
		array('id'=>'ID','level'=>'Level','name'=>'Name','d'=>'Delete','e'=>'Edit' )
		, $l->all);
?>
