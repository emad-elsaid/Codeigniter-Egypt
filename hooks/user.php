<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if( ! function_exists('init_user')){
	function init_user(){
		$CI =& get_instance();
		if( $CI->session->userdata('level')===FALSE)
			$CI->session->set_userdata('level',0);
			$CI->session->set_userdata('mode','view');
		
	}
}
