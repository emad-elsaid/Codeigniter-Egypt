<?php
$ci =& get_instance();
$ci->load->library( 'gui' );

$id = $ci->uri->segment( 5 );

$s = new Section();
$s->get_by_id( $id );

$hidden = array( 
	'id'=>$id,
	'parent_section'=>$s->parent_section,
	'sort'=>$s->sort
);

echo $ci->gui->form(
	$ci->app->app_url( 'editaction' )
	,array(
			'Name :'=>$ci->gui->textbox( 'name', $s->name )
			,'view'=>$ci->gui->permission( 'view', $s->view )
			,''=>$ci->gui->button( '', 'Edit Section', array('type'=>'submit') )
	)
	,''
	,$hidden
);
