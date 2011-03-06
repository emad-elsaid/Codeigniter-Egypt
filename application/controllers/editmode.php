<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Editmode extends Application {
	
	public function __construct(){
		
		parent::__construct();
		
		$this->perm 	= 'admin';
		$this->name 	= "Edit mode Changer";
		$this->author 	= "Emad Elsaid";
		$this->website 	= "http://blazeeboy.blogspot.com";
		$this->version 	= "0.1";
		$this->pages 	= array(
						'edit'=>'Edit Mode'
						,'view'=>'View Mode'
						);

	}
	
	public function edit(){
		
		$this->system->mode( 'edit' );
		$this->load->library( 'gui' );
		$text = anchor( site_url('editmode/view') , 'Go to View Mode' );
		$this->print_text('<center>'.$text.'</center>');
		
	}
	
	public function view(){
		
		$this->system->mode( 'view' );
		$this->load->library( 'gui' );
		$text = anchor( site_url('editmode/edit') , 'Go to Edit Mode' );
		$this->print_text('<center>'.$text.'</center>');
	
	}
}