<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vunsy {
	
    function section()
    {
		$CI =& get_instance();
		if( $this->installed() )
		{
			if( ! isset($CI->uri) )
				$CI->load->library('URI');
			$sec = new Section();
			$sec->get_by_id($CI->uri->rsegment(1));
			if( ! $sec->exists()){
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
	
}

?>
