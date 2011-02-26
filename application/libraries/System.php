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
	var $js 		= array();
	var $css_first 	= array();
	var $css 		= array();
	var $css_last	= array();
	var $dojo 		= array();
	var $header		= array();
	var $dojoStyle 	= '';
	var $section 	= NULL;
	var $user 		= NULL;
	var $level		= NULL;
	var $mode 		= 'view';

	function __construct(){

		$this->CI =& get_instance();
		$this->CI->load->database();
		$this->CI->load->library(array('datamapper','session','ion_auth'));
		$this->CI->load->helper(array('perm', 'html','url','page'));

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

		//getting the autoloading css and javascript paths
		$this->css = $this->CI->config->item('css');
		$this->js = $this->CI->config->item('js');
		$this->dojoStyle = $this->CI->config->item( 'dojoStyle' );
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

	function css_text(){
		$css_t= '';
		foreach( $this->css_first as $item )
		$css_t .= "\n\t".link_tag( $item );
			
		foreach( $this->css as $item )
		$css_t .= "\n\t".link_tag( $item );
			
		foreach( $this->css_last as $item )
		$css_t .= "\n\t".link_tag( $item );

		return $css_t;
	}

	function js_text(){
		
		// unset dojo if included
		if( count($this->dojo)>0 )
			$this->js = array_diff($this->js,array(base_url().'dojo/dojo/dojo.js'));

		$js_t = '';
		foreach( $this->js as $item )
			$js_t .= "\n\t<script type=\"text/javascript\" src=\"".$item."\" ></script>";
		
		return $js_t;
	}

	function dojo_text($force=FALSE){

		// if force equal to FALSE
		if( count($this->dojo) == 0 and $force==FALSE )
		return "";
			

		$text = "\n\t".link_tag( "dojo/dijit/themes/{$this->dojoStyle}/{$this->dojoStyle}.css" );
		$text .=
		"\n\t<script type=\"text/javascript\" src=\"".base_url()."dojo/dojo/dojo.js\" djConfig=\"parseOnLoad:true\"></script>";

		if( count($this->dojo) > 0 ){
			$text .= "\n\t<script type=\"text/javascript\">";
			foreach( $this->dojo as $item )
			$text .= "\n\t\tdojo.require(\"".$item."\");";
			$text .= "\n\t</script>";
		}

		return $text;
	}

	function header_text(){
		return implode( "\n", $this->header );
	}
}

?>
