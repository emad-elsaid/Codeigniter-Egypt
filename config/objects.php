<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['objects'] = array(
	'content' => array(
		'section' => array( 'type'=>'BIGINT','null'=>TRUE ),
		'subsection' => array( 'type'=>'TINYINT' ,'default'=>TRUE),
		//'parent' => array( 'type'=>'BIGINT','null'=>TRUE ),
		'cell' => array( 'type'=>'TINYINT','null'=>TRUE ),
		'path' => array( 'type'=>'VARCHAR', 'constraint'=>100,'null'=>TRUE ),
		'sort' => array( 'type'=>'INT','null'=>TRUE ),
		'view' => array( 'type'=>'LONGTEXT','null'=>TRUE),
		'addin' => array( 'type'=>'LONGTEXT','null'=>TRUE),
		'edit' => array( 'type'=>'LONGTEXT','null'=>TRUE),
		'del' => array( 'type'=>'LONGTEXT','null'=>TRUE),
		'by' => array( 'type'=>'INT','null'=>TRUE ),
		'type' => array( 'type'=>'VARCHAR', 'constraint'=>30,'null'=>TRUE ),
		'info' => array( 'type'=>'LONGTEXT','null'=>TRUE)
	) ,


	'section' => array(
		'name' => array( 'type'=>'LONGTEXT') ,
		'parent_section' => array( 'type'=>'BIGINT','null'=>TRUE ),
		'sort' => array( 'type'=>'INT','null'=>TRUE ),
		'view' => array( 'type'=>'LONGTEXT','null'=>TRUE),
		'add' => array( 'type'=>'LONGTEXT','null'=>TRUE),
		'edit' => array( 'type'=>'LONGTEXT','null'=>TRUE)
	) ,


	'user' => array(
		'name' => array( 'type'=>'VARCHAR', 'constraint'=>30 ),
		'password' => array( 'type'=>'VARCHAR', 'constraint'=>50 ),
		//'level' => array( 'type'=>'TINYINT' ),
		'lastenter' => array( 'type'=>'VARCHAR', 'constraint'=>14,'null'=>TRUE ),
		'curenter' => array( 'type'=>'VARCHAR', 'constraint'=>14,'null'=>TRUE ),
		'email' => array( 'type'=>'VARCHAR', 'constraint'=>50,'null'=>TRUE ),
		'info' => array( 'type'=>'LONGTEXT','null'=>TRUE)
	) ,


	'userlevel' => array(
		'level' =>array( 'type'=>'TINYINT'),
		'name' => array( 'type'=>'VARCHAR', 'constraint'=>30 )
	) 
);
