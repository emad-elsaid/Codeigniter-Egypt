<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/** \addtogroup Libraries 
 * Vunsy main Class that store user , section, level, and all other main data
 *
 * @package	Vunsy
 * @subpackage	Vunsy
 * @category	library file
 * @author	Emad Elsaid
 * @link	http://github.com/blazeeboy/vunsy
 */
class Vunsy {
	
	var $js 		= array();
	var $css_first 	= array();
	var $css 		= array();
	var $css_last	= array();
	var $dojo 		= array();
	var $header		= array();
	var $dojoStyle 	= '';
	var $section 	= '';
	var $user 		= '';
	var $level		= '';
	var $mode 		= '';
	
	function Vunsy()
	{
		if( ! $this->installed() )
		{
			$this->install();
			redirect();
		}
		
			$CI =& get_instance();
			
			// getting the current section 
			$this->section = $this->get_section();
			
			//getting the current user data
			$CI->load->library('ion_auth');
			$this->user = $CI->ion_auth->get_user();
			
			// getting level
			if( $CI->ion_auth->logged_in())
				$this->level = $CI->ion_auth->get_group($this->user->group_id);
			else
				$this->level = NULL;
			
			// getting the site mode
			$this->mode = $CI->session->userdata('mode');
			
			//getting the autoloading css and javascript paths
			$this->css = $CI->config->item('css');
			$this->js = $CI->config->item('js');
			$this->dojoStyle = $CI->config->item( 'dojoStyle' );
	}
	
	/**
	 *  function of checking site mode
	 * 
	 * */
	function mode($mode='')
	{
		if( empty($mode) )
			return $this->mode;
		else
		{
			$CI =& get_instance();
			$CI->session->set_userdata( 'mode', $mode );
			$this->mode = $mode ;
		}
	}
	
	function edit_mode()
	{
		return ($this->mode=='edit')? TRUE:FALSE;
	}
	
	function view_mode()
	{
		return ($this->mode=='view')? TRUE:FALSE;
	}
	
    function get_section()
    {
		$CI =& get_instance();
		$sec = new Section();
		$section_segment = $CI->uri->segment(1);
		
		if( substr( $section_segment, 0, 1 )== '+' )
			$sec->get_by_name( substr( $section_segment, 1 ) );
		else
			$sec->get_by_id( $section_segment );
		
		if( ! $sec->exists())
			$sec->get_by_id('1');
			
		return $sec;
    }
	
	function installed()
	{
		$CI =& get_instance();
		return ( count($CI->db->list_tables())>0 );
	}
	
	
	function install()
	{
		$CI =& get_instance();
		$script = explode( ';', file_get_contents('mysql.sql') );
		$script = array_map( 'trim', $script );
		$script = array_filter( $script, 'count');
		foreach( $script as $line )
			if( $line!='' )
				$CI->db->query($line);
	}
	
	function css_text()
	{
		$css_t= '';
		foreach( $this->css_first as $item )
			$css_t .= "\n\t".link_tag( $item );
			
		foreach( $this->css as $item )
			$css_t .= "\n\t".link_tag( $item );
			
		foreach( $this->css_last as $item )
			$css_t .= "\n\t".link_tag( $item );
		
		return $css_t;
	}
	
	function js_text()
	{
		// unset dojo if included
		if( in_array(base_url().'dojo/dojo/dojo.js',$this->js) && 
			count($this->dojo)>0 )
		{
			for($i=0; $i<count($this->js); $i++ )
			{
				if( $this->js[$i]==(base_url().'dojo/dojo/dojo.js') )
				{
					$this->js[$i] = '';
					break;
				}
			}
		}
		
		$js_t = '';
		foreach( $this->js as $item )
		{
			if($item!='')
				$js_t .= "\n\t<script type=\"text/javascript\" src=\"".$item."\" ></script>";
		}	
		return $js_t;
	}
	
	function dojo_text($force=FALSE)
	{
		
		// if force equal to FALSE
		if( count($this->dojo) == 0 and $force==FALSE )
			return "";
			
		
		// make the text even if there isn't any requirements
		// unset dojo if included cuz it makes trouble if included twice
		if( in_array(base_url().'dojo/dojo/dojo.js',$this->js) )
		{
			for($i=0; $i<count($this->js); $i++ )
			{
				if( $this->js[$i]==(base_url().'dojo/dojo/dojo.js') )
				{
					$this->js[$i] = '';
					break;
				}
			}
		}
		
		$text = "\n\t".link_tag( "dojo/dijit/themes/{$this->dojoStyle}/{$this->dojoStyle}.css" );
		$text .= 
		"\n\t<script type=\"text/javascript\" src=\"".base_url()."dojo/dojo/dojo.js\"
	djConfig=\"parseOnLoad:true\"></script>";
		
		if( count($this->dojo) > 0 )
		{
			$text .= "\n\t<script type=\"text/javascript\">";
			foreach( $this->dojo as $item )
				$text .= "\n\t\tdojo.require(\"".$item."\");";
				
			$text .= "\n\t</script>";
		}
		
		return $text;
	}
	
	function header_text()
	{
		return implode( "\n", $this->header );
	}
}

?>
