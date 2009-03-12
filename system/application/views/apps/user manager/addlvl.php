<?php
 $ci =& get_instance();
 $ci->load->library('gui');
 
 echo $ci->gui->form( $ci->app->app_url('addlvlaction'), 
 array(
	"Level name" => $ci->gui->textbox( 'name' )
	,"Level number" => $ci->gui->number( 'level', 1 )
	,"" => $ci->gui->button( 'submit',  'Add level' , array( 'type'=>'submit' ) )
 )
 );
