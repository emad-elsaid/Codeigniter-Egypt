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
/**
 * this function initialize user session data 
 * if that's the first page he visit
 * : if it's the first page then he doesn't have 
 * session data, then wel initialize the guest session
 * using id and level = zero and in the view mode
 * */
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
