<?php

$ci =& get_instance();
$ci->load->library('gui');

$hidden = array();

$hidden['parent_section'] = $ci->input->post( "parent_section" );
$hidden['parent_content'] = $ci->input->post( "parent_content" );
$hidden['cell'] = $ci->input->post( "cell" );
$hidden['sort'] = $ci->input->post( "sort" );
$hidden['path'] = $ci->input->post( "path" );

$explodedPath = explode( '/', $hidden['path'] );
$hidden['type'] = $explodedPath[0];

echo $ci->gui->form(
	$ci->app->app_url('addaction')
	,array(
	"Show in subsections : " => $ci->gui->checkbox('subsection')
	,"View permissions : " => $ci->gui->textarea('view')
	,"Add in permissions : " => $ci->gui->textarea('addin')
	,"Edit permissions : " => $ci->gui->textarea('edit')
	,"Delete permissions : " => $ci->gui->textarea('del')
	,"" => $ci->gui->button( '','Add Content', array('type'=>'submit'))
	)
	,""
	,$hidden
);
