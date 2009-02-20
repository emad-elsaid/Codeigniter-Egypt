<?php
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
		return ! $this->is_guest();
	}
	
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
			$this->session->set_userdata('id', $this->id);
			$this->session->set_userdata('level', $this->level);
		}
		else 
		{
			// check if it is the root
			$g_name = $this->config->item('root');
			$g_pass = $this->config->item('root_password');
			if( $username == $g_name and $password== $g_pass )
			{
				$result = TRUE;
				$this->session->set_userdata('id', -1);
				$this->session->set_userdata('level', -1);
			}
		}
		
		// sync the user with the session
		$this->from_session();
		
		// return the login result
		return $result;
	}
}
