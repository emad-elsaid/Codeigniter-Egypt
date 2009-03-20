<?php
 $ci =& get_instance();
 $ci->load->library('gui');
 
 
//getting the level;
$id = $ci->uri->segment(5);
$level = new Userlevel();
$level->get_by_id($id);

 echo $ci->gui->form( $ci->app->app_url('editlvlaction'), 
 array(
	"Level name" => $ci->gui->textbox( 'name' , $level->name)
	,"Level number" => $ci->gui->number( 'level', $level->level )
	,"" => $ci->gui->button( 'submit',  'Edit level' , array( 'type'=>'submit' ) )
	 )
	 ,""
	,array('id'=>$id)
 );
