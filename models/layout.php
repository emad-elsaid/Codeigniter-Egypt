<?php
class Layout extends Content {
	var $has_many = array('layout','plugin');
	
	function Layout(){
		parent::Content();
	}
	
	function cells(){
		$c = ($this->path!='' AND $this->path!=NULL)? $this->load->view($this->path,array('mode'=>'config'),TRUE) : 0;
		return intval($c);
	}
}
