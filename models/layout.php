<?php
class Layout extends Content {
	var $has_many = array('layout','plugin');
	
	function Layout(){
		parent::Content();
	}
	
	function cells(){
		$c = $this->load->view($this->path,array('mode'=>'config'),TRUE);
		return intval($c);
	}
}
