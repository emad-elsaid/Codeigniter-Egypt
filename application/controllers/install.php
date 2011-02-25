<?php
class Install extends CI_Controller{

	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
	}
	
	function index(){
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

		