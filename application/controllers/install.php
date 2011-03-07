<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * controller that install database to current default database connection 
 *
 * that controller contains one page that reads mysql.sql file
 * parse it and execute all  statments.
 *
 * @copyright  2011 Emad Elsaid a.k.a Blaze Boy
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt   GPL License 2.0
 * @link       https://github.com/blazeeboy/Codeigniter-Egypt
 */ 
class Install extends CI_Controller{

	public function __construct(){
		
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
		
	}
	
	public function index(){
		
		if( count($this->db->list_tables())==0 ){
			$script = explode( ';', file_get_contents('mysql.sql') );
			$script = array_map( 'trim', $script );
			$script = array_filter( $script, 'count');
			foreach( $script as $line )
			if( $line!='' )
			$this->db->query($line);
		}
		redirect('');
		
	}
}

		