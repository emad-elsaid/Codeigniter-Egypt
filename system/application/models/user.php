<?php
/** \addtogroup Models
 * User class instance of datamapper that holds user data
 *
 * @package	Vunsy
 * @subpackage	Vunsy
 * @category	model file
 * @author	Emad Elsaid
 * @link	http://github.com/blazeeboy/vunsy
 */
class User extends DataMapper {
	var $table = 'user';
	var $ci;
	
    function User()
    {
        parent::DataMapper();
        $this->ci =& get_instance();
    }
	
	/**
	 * get user information from seesion
	 * that will get the current user information
	 **/
	function from_session()
	{
		$uid = $this->ci->session->userdata('id');
			
			if( $uid>0 )
				$this->get_by_id($uid);
			else if( $uid==-1 and $this->ci->session->userdata('level')==-1 )
			{
				$this->id = -1;
				$this->level = -1;
				$this->name = $this->ci->config->item('root');
			}
			else
			{
				$this->id = 0;
				$this->name = "Guest";
				$this->level = 0;
			}
	}
	
	/**
	 * return if the user is a root user
	 **/
	function is_root()
	{
		if( $this->id==-1 and $this->level==-1 )
			return TRUE;
		else
			return FALSE;
	}
	
	/**
	 * return true if it's a normal user and not a guest
	 * false if the user is root or guest
	 **/
	function is_user()
	{
		if( $this->id > 0 )
			return TRUE;
		else
			return FALSE;
	}
	
	/**
	 * return true is the user is guest false if not 
	 **/
	function is_guest()
	{
		if( $this->id == 0 )
			return TRUE;
		else
			return FALSE;
	}
	
	/**
	 * return true if the user is logged ( not a guest )
	 **/
	function logged()
	{
		return (! $this->is_guest() );
	}
	
	/**
	 * login with user name and password and assign the information
	 * in session
	 * returns true if logged and false if not
	 * @param	$username: user name 
	 * @param	$password: password of the user (normal not encrypted)
	 **/
	function login( $username='' ,$password='')
	{
		
		$result = FALSE;
		
		if( empty($username) or empty($password) )
			return FALSE;
			
		// check if it is a normal user
		$this->where( 'name', $username );
		$this->where( 'password' , md5($password) );
		$this->get();
		
		// if the user exists 
		if( $this->exists() )
		{
			$result = TRUE;
			
			$lvl = new Userlevel();
			$lvl->get_by_id( $this->level );
			if( $lvl->exists() )
				$this->ci->session->set_userdata( 'level', $lvl->level );
			else
				$this->ci->session->set_userdata( 'level', 0 );
			
			
				
			$this->ci->session->set_userdata( 'mode', 'view' );
			$this->ci->session->set_userdata( 'id', $this->id );
			$this->lastenter	= $this->curenter;
			$this->curenter	= $this->ci->input->ip_address();
			$this->save();
		}
		else 
		{
			// check if it is the root
			$g_name = $this->ci->config->item('root');
			$g_pass = $this->ci->config->item('root_password');
			if( $username == $g_name and $password== $g_pass )
			{
				$result = TRUE;
				$this->ci->session->set_userdata('mode', 'edit');
				$this->ci->session->set_userdata('id', -1);
				$this->ci->session->set_userdata('level', -1);
			}
		}
		
		// sync the user with the session
		$this->from_session();
		
		// return the login result
		return $result;
	}
	
	/**
	 * logout from current user and destroy the damn session ... Yahooooo
	 **/
	function logout()
	{
		$this->ci->session->set_userdata('id', 0);
		$this->ci->session->set_userdata('level', 0);
		$this->ci->session->set_userdata('mode', 'view');
		if( $this->logged() )
		{
			$this->lastenter = $this->curenter;
			$this->save();
		}
	}
	
	/**
	 * set a new password for that user... without saving
	 **/
	function set_password( $password="" )
	{
		$this->password = md5( $password );
	}
}
