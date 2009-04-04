<?php
/**
 * user login and out controller
 *
 * @package	Vunsy
 * @subpackage	Vunsy
 * @category	controller file
 * @author	Emad Elsaid
 * @link	http://github.com/blazeeboy/vunsy
 */
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
			redirect( );
			}
		}
		
		$this->load->view('login');
	}
	
	function logout()
	{
		$this->vunsy->user->logout();
		redirect();
	}
	
}
