<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * hook to set the preloading session data
 * 
 * i've put that code here for extra security
 *
 * @package	Vunsy
 * @subpackage	Vunsy
 * @category	hook file
 * @author	Emad Elsaid
 * @link	http://github.com/blazeeboy/vunsy
 */
if( ! function_exists('init_user')){
	function init_user(){
		$CI =& get_instance();
		if( $CI->session->userdata('id')==FALSE)
		{
			$CI->session->set_userdata('id',0);
			$CI->session->set_userdata('level',0);
			$CI->session->set_userdata('mode','view');
		}
	}
}
