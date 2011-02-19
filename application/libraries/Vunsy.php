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
			$this->user = new User();
			$this->user->from_session();
			
			// getting level
			$this->level = new Userlevel();
			if( $this->user->is_root())
			{
				$this->level->id = -1;
				$this->level->level = -1;
				$this->level->name = 'root';
			}
			else
			{
				$this->level->get_by_id( $this->user->level );
			}
			
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
		$CI->load->dbforge();
		
		// loading objects;
		$CI->config->load('objects');
		$tables = $CI->config->item('objects');
		$tables_keys = array_keys($tables);
		
		foreach( $tables_keys as $item)
		{
			if(! $CI->db->table_exists( $item ))
				return FALSE;
		}
		return TRUE;
	}
	
	
	function install()
	{
		$CI =& get_instance();
		$CI->load->dbforge();
		
		// loading objects;
		$CI->config->load('objects');
		$tables = $CI->config->item('objects');
		
		foreach( $tables as $table=>$fields )
		{
				$CI->dbforge->add_field('id');
				$CI->dbforge->add_field($fields);
				$CI->dbforge->create_table($table);
		}
	
		$CI->load->library('datamapper');
		//adding the default section
		$index = new Section();
		$index->parent_section = 0;
		$index->sort = 0;
		$index->save();
		
		$page_body = new Content();
		$page_body->sub_section = intval(TRUE);
		$page_body->cell = 0;
		$page_body->sort = 0;
		$page_body->parent_section = $index->id;
		$page_body->info = 'PAGE_BODY_LOCKED';
		$page_body->save();
		
		$default_layout = new Content();
		$default_layout->path = 'default.php';
		$default_layout->parent_content = 1;
		$default_layout->parent_section = 1;
		$default_layout->subsection = 0;
		$default_layout->cell = 0;
		$default_layout->sort = 0;
		$default_layout->save();
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
