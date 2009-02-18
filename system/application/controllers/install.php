<?php

class Install extends Controller {

	function Install()
	{
		parent::Controller();	
	}
	
	function index()
	{
		
		$this->load->dbforge();
		
		$tables = $this->config->item('objects');
		$tables_keys = array_keys($tables);
		
		foreach( $tables_keys as $item ){
				$this->dbforge->add_field($tables[$item]);
				$this->dbforge->create_table($item);
				$this->db->query("ALTER TABLE `".$item."` ADD COLUMN `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, ADD PRIMARY KEY (`id`)");
				
				
		}
		
	}
}

