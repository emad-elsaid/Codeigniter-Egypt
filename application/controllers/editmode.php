<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Editmode extends Application {
	
	function __construct(){
		parent::__construct();
		
		$this->perm = 'admin';

		$this->name 	= "Edit mode Changer";
		$this->author 	= "Emad Elsaid";
		$this->website 	= "http://blazeeboy.blogspot.com";
		$this->version 	= "0.1";

		$this->show_statusbar 	= FALSE;
		$this->show_toolbar 	= TRUE;
		$this->pages 			= array(
									'edit'=>'Edit Mode'
									,'view'=>'View Mode'
									);

	}
	
	function edit()
	{
		$this->vunsy->mode( 'edit' );
		$this->load->library( 'gui' );
		$text = anchor( site_url('editmode/view') , 'Go to View Mode' );
		$this->print_text('<center>'.$text.'</center>');
	}
	
	function view()
	{
		$this->vunsy->mode( 'view' );
		$this->load->library( 'gui' );
		$text = anchor( site_url('editmode/edit') , 'Go to Edit Mode' );
		$this->print_text('<center>'.$text.'</center>');
	
	}
}