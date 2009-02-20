<?php

class Users extends Controller {

	function Users()
	{
		parent::Controller();	
	}
	
	function login()
	{
		$this->load->view('login');
	}
	
	function login_action()
	{
		
	}
	
}
