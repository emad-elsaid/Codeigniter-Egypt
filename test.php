<?php
$a = array(
	'name' =>'Application',
	'ver' =>'1.0',
	'author' =>'Blaze Boy',
	'website' =>'http://blazeeboy.wordpress.com',
	
	// application run permissions
	'perm' =>'$this->vunsy->is_admin()',
	
	// pages and sections of the application
	'pages' => array(),
	'index' =>'',
	
	//css and js files
	'css_files' => array(),
	'js_files' => array(),
	
	// application view specifications
	'show_toolbar' => TRUE,
	'show_title' => TRUE,
	'show_statusbar' => TRUE
);
echo json_encode($a);
