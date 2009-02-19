<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vunsy {
	
    function section()
    {
		$CI =& get_instance();
		if( $this->installed() )
		{
			if( ! isset($CI->uri) )
				$CI->load->library('URI');
			$this->section = new Section();
			$this->section->get_by_id($CI->uri->rsegment(1));
			if( ! $this->section->exists()){
				$this->section->get();
			}
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
	}
	
	function install_content()
	{
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
		$before_page->info = 'BEFORE_PAGE_LOCKED';
		$before_page->save();
		$index->save( $before_page );
		
		$page_head = $before_page->copy();
		$page_head->info = 'PAGE_HEAD_LOCKED';
		$page_head->save();
		$index->save($page_head);
		
		$page_body = $page_head->copy();
		$page_body->info = 'PAGE_BODY_LOCKED';
		$page_body->save();
		$index->save($page_body);
		
		$after_page = $page_body->copy();
		$after_page->info = 'AFTER_PAGE_LOCKED';
		$after_page->save();
		$index->save($after_page);
	}
	
}

?>
