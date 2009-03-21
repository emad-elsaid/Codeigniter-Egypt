<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vunsy {
	
	var $js = array();
	var $css = array();
	var $dojo = array();
	var $dojoStyle = "";
	var $section = '';
	var $user = '';
	var $mode = '';
	
	function Vunsy()
	{
		if( $this->installed() )
		{
			$CI =& get_instance();
			
			// getting the current section 
			$this->section = $this->get_section();
			
			//getting the current user data
			$this->user = new User();
			$this->user->from_session();
			
			// getting the site mode
			$this->mode = $CI->session->userdata('mode');
			
			//getting the autoloading css and javascript paths
			$this->css = $CI->config->item('css');
			$this->js = $CI->config->item('js');
			$this->dojoStyle = $CI->config->item( 'dojoStyle' );
			
		}
	}
	
	/* function of checking site mode
	 * 
	 * */
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
		if( $this->installed() )
		{
			if( ! isset($CI->uri) )
				$CI->load->library('URI');
			$sec = new Section();
			$sec->get_by_id($CI->uri->rsegment(1));
			if( ! $sec->exists())
			{
				$sec->get();
			}
			
			return $sec;
		}
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
		$tables_keys = array_keys($tables);
		
		foreach( $tables_keys as $item )
		{
				$CI->dbforge->add_field($tables[$item]);
				$CI->dbforge->create_table($item);
				$CI->db->query("ALTER TABLE `".$item."` ADD COLUMN `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, ADD PRIMARY KEY (`id`)");
		}
	
		$CI->load->library('datamapper');
		//adding the default section
		$index = new Section();
		$index->paren_section = 0;
		$index->sort = 0;
		$index->save();
		
		// adding the default content Layouts
		$before_page = new Layout();
		$before_page->sub_section = intval(TRUE);
		$before_page->cell = 0;
		$before_page->sort = 0;
		$before_page->parent_section = $index->id;
		$before_page->info = 'BEFORE_PAGE_LOCKED';
		$before_page->save();
		
		$page_head = new Layout();
		$page_head->sub_section = intval(TRUE);
		$page_head->cell = 0;
		$page_head->sort = 0;
		$page_head->parent_section = $index->id;
		$page_head->info = 'PAGE_HEAD_LOCKED';
		$page_head->save();
		
		$page_body = new Layout();
		$page_body->sub_section = intval(TRUE);
		$page_body->cell = 0;
		$page_body->sort = 0;
		$page_body->parent_section = $index->id;
		$page_body->info = 'PAGE_BODY_LOCKED';
		$page_body->save();
		
		$after_page = new Layout();
		$after_page->sub_section = intval(TRUE);
		$after_page->cell = 0;
		$after_page->sort = 0;
		$after_page->parent_section = $index->id;
		$after_page->info = 'AFTER_PAGE_LOCKED';
		$after_page->save();
	}
	
	function css_text()
	{
		$css_t= '';
		foreach( $this->css as $item )
			$css_t .= "	".link_tag( $item )."\n";
		
		return $css_t;
	}
	
	function js_text()
	{
		$js_t = '';
		foreach( $this->js as $item )
			$js_t .= "\n\t<script type=\"text/javascript\" src=\"".$item."\" ></script>";
			
		return $js_t;
	}
	
	function dojo_text($force=FALSE)
	{
		
		// if force equal to FALSE
		if( count($this->dojo) == 0 and $force==FALSE )
			return "";
			
		
		// make the text even if there isn't any requirements
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
	
	
}

?>
