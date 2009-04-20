<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Vunsy database Objects
 *
 * @package	Vunsy
 * @subpackage	Vunsy
 * @category	Config file
 * @author	Emad Elsaid
 * @link	http://github.com/blazeeboy/vunsy
 */
$config['objects'] = array(
	'content' => array(
		'parent_content' => array( 'type'=>'BIGINT','null'=>TRUE ),
		'parent_section' => array( 'type'=>'BIGINT','null'=>TRUE ),
		'subsection' => array( 'type'=>'TINYINT' ,'default'=>TRUE),
		'cell' => array( 'type'=>'TINYINT','null'=>TRUE ),
		'sort' => array( 'type'=>'INT','null'=>TRUE ),
		'path' => array( 'type'=>'VARCHAR', 'constraint'=>100,'null'=>TRUE ),
		'view' => array( 'type'=>'LONGTEXT','null'=>TRUE),
		'addin' => array( 'type'=>'LONGTEXT','null'=>TRUE),
		'edit' => array( 'type'=>'LONGTEXT','null'=>TRUE),
		'del' => array( 'type'=>'LONGTEXT','null'=>TRUE),
		'info' => array( 'type'=>'LONGTEXT','null'=>TRUE)
	) ,


	'section' => array(
		'parent_section' => array( 'type'=>'BIGINT','null'=>TRUE ),
		'name' => array( 'type'=>'VARCHAR', 'constraint'=>100,'null'=>TRUE) ,
		'sort' => array( 'type'=>'INT','null'=>TRUE ),
		'view' => array( 'type'=>'LONGTEXT','null'=>TRUE)
	) ,


	'user' => array(
		'level' => array( 'type'=>'BIGINT','null'=>TRUE ),
		'name' => array( 'type'=>'VARCHAR', 'constraint'=>30 ),
		'password' => array( 'type'=>'VARCHAR', 'constraint'=>50 ),
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
