<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/** \addtogroup Libraries
 * Codeigniter-Egypt main Class that store user , section, level, and all other main data
 *
 * @package	Codeigniter-Egypt
 * @subpackage	Codeigniter-Egypt
 * @category	library file
 * @author	Emad Elsaid
 * @link	http://github.com/blazeeboy/Codeigniter-Egypt
 */
class System {

	var $CI 		= NULL;
	var $section 	= NULL;
	var $user 		= NULL;
	var $level		= NULL;
	var $mode 		= 'view';

	function __construct(){

		$this->CI =& get_instance();
		$this->CI->load->database();
		$this->CI->load->library(array('datamapper','session','ion_auth'));
		$this->CI->load->helper(array('perm', 'html','url','theme'));

		// getting the current section
		$this->section = $this->get_section();

		if( $this->CI->ion_auth->logged_in()){
			//getting the current user data
			$this->user = $this->CI->ion_auth->get_user();
			// getting level
			$this->level = $this->CI->ion_auth->get_group($this->user->group_id);
		}

		// getting the site mode
		$this->mode = $this->CI->session->userdata('mode');
	}

	/**
	 *  function of checking site mode
	 *
	 * */
	function mode($mode=''){

		if( !empty($mode) ){
			$this->CI->session->set_userdata( 'mode', $mode );
			$this->mode = $mode ;
		}
		return $this->mode;
		
	}

	function get_section(){

		$sec = new Section($this->CI->uri->rsegment(3));

		if(!$sec->exists())
		$sec->get_by_id(1);
			
		return $sec;
	}
}

?>
