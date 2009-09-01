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

	/**
	 * users controller perform login and logout action
	 * you can use it like that :
	 * make a form contain inputs ( user, pass ) and
	 * the the action is site_url( 'users/login' ) OR for short site_url('login' )
	 * if you want to logout just direct your user to 
	 * site_url( 'users/logout' ) OR for short site_url( 'logout' )
	 * */
	function Users()
	{
		parent::Controller();	
	}
	
	/**
	 * login the user and return to index
	 * it takes the input from POST
	 * input needed 
	 * @param user : for user name
	 * @param pass : for the password
	 * */
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
			redirect();
			}
		}
		
		$this->load->view('login');
	}
	
	
	/**
	 * that function will logout from current user and return to
	 * index page
	 * */
	function logout()
	{
		$this->vunsy->user->logout();
		redirect();
	}
	
}
