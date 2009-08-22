<?php
/**
 * user class
 *
 * @package	Vunsy
 * @subpackage	Vunsy
 * @category	model file
 * @author	Emad Elsaid
 * @link	http://github.com/blazeeboy/vunsy
 */
class User extends DataMapper {
	var $table = 'user';
	
    function User()
    {
        parent::DataMapper();
    }
	
	function from_session()
	{
		$CI =& get_instance();
		$uid = $CI->session->userdata('id');
			
			if( $uid>0 )
				$this->get_by_id($uid);
			else if( $uid==-1 and $CI->session->userdata('level')==-1 )
			{
				$this->id = -1;
				$this->level = -1;
				$this->name = $CI->config->item('root');
			}
			else
			{
				$this->id = 0;
				$this->name = "Guest";
				$this->level = 0;
			}
	}
	
	function is_root()
	{
		if( $this->id==-1 and $this->level==-1 )
			return TRUE;
		else
			return FALSE;
	}
	
	
	function is_user()
	{
		if( $this->id > 0 )
			return TRUE;
		else
			return FALSE;
	}
	
	function is_guest()
	{
		if( $this->id == 0 )
			return TRUE;
		else
			return FALSE;
	}
	
	function logged()
	{
		return (! $this->is_guest() );
	}
	
	function login( $username='' ,$password='')
	{
		
		$result = FALSE;
		$CI =& get_instance();
		
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
				$CI->session->set_userdata( 'level', $lvl->level );
			else
				$CI->session->set_userdata( 'level', 0 );
			
			
				
			$CI->session->set_userdata( 'mode', 'view' );
			$CI->session->set_userdata( 'id', $this->id );
			$this->lastenter = $this->curenter;
			$this->curenter = $CI->input->ip_address();
			$this->save();
		}
		else 
		{
			// check if it is the root
			$g_name = $CI->config->item('root');
			$g_pass = $CI->config->item('root_password');
			if( $username == $g_name and $password== $g_pass )
			{
				$result = TRUE;
				$CI->session->set_userdata('mode', 'edit');
				$CI->session->set_userdata('id', -1);
				$CI->session->set_userdata('level', -1);
			}
		}
		
		// sync the user with the session
		$this->from_session();
		
		// return the login result
		return $result;
	}
	
	function logout()
	{
		$CI =& get_instance();
		$CI->session->set_userdata('id', 0);
		$CI->session->set_userdata('level', 0);
		$CI->session->set_userdata('mode', 'view');
		$this->lastenter = $this->curenter;
		$this->save();
	}
	
	function set_password( $password="" )
	{
		$this->password = md5( $password );
	}
}
