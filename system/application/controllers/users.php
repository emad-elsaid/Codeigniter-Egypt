<?php

class Users extends Controller {

	function Users()
	{
		parent::Controller();	
	}
	
	function login()
	{
		if( $this->input->post('user') != NULL )
		{
			if( $this->vunsy->user->login
				( 
					$this->input->post('user'),
					$this->input->post('pass')
				) == TRUE
			 )
			{
			//redirect( index_page() );
			}
		}
		
		$this->load->view('login');
	}
	
	function logout()
	{
		$this->vunsy->user->logout();
		redirect( index_page() );
	}
	
}
